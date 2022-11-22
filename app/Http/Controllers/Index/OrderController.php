<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use App\Http\Requests\Index\Auth\AuthConfirmRequest;
use App\Http\Requests\Index\Order\StoreOrderRequest;
use App\Models\Client;
use App\Models\Order;
use App\Models\Route;
use App\Models\Setting;
use App\Models\Tour;
use App\Models\Bus;
use App\Models\Driver;
use App\Models\OrderPlace;
use App\Notifications\Order\ActiveOrderNotification;
use App\Repositories\SelectRepository;
use App\Services\Client\StoreClientService;
use App\Services\Code\CheckCodeService;
use App\Services\Code\SendCodeService;
use App\Services\Coupon\CouponToOrderService;
use App\Services\Order\CalculatePriceOrder;
use App\Services\Order\ChildPlaceService;
use App\Services\Order\FragmentationOrder;
use App\Services\Geo\GeoService;
use App\Services\Order\PrintOrderService;
use App\Services\Order\StationIntervalsService;
use App\Services\Order\StoreOrderService;
use App\Services\Pdf\ServicePdf;
use App\Services\Route\GetFromStation;
use App\Services\Route\GetToStation;
use App\Services\Pays\ServicePayService;
use App\Services\Prettifier;
use App\Services\Sale\SaleToOrderService;
use App\Services\Order\AddServicesPriceService;
use Carbon\Carbon;
use Session;
use App\Services\Station\CreateNewStationService;
use App\Validators\Order\StoreOrderValidator;
use Request;
use DateTime;

use GuzzleHttp\Client as HTTP;

class OrderController extends Controller
{

    public function index()
    {
        $error = false;
        $order = Order::where('status', Order::STATUS_ACTIVE)->find(session('order.id'));
        if (Session::has('order_return')) {
            $orderReturn = Order::where('status', Order::STATUS_ACTIVE)->find(session('order_return.id'));
        } else {
            $orderReturn = false;
        }

        if (!$order) return redirect(route('index.home'));
        if ($order) $error = StoreOrderValidator::order($order);
        if (!$error) $error = StoreOrderValidator::tour($order->tour);
        if ($error) return redirect($order->urlSchedules);
        $stationsFromReturn = [];
        $stationsToReturn = [];
        if ($orderReturn) {
            $error = StoreOrderValidator::order($orderReturn);
            if (!$error) $error = StoreOrderValidator::tour($orderReturn->tour);
            if ($error) return redirect($orderReturn->urlSchedules);
            $stationsFromReturn = SelectRepository::streetsWithStation($orderReturn->tour, $orderReturn->tour->route->id, $orderReturn->stationFrom->city_id);
            $stationsToReturn = SelectRepository::streetsWithStation($orderReturn->tour, $orderReturn->tour->route->id, $orderReturn->stationTo->city_id);
            $order->update(['return_order_id' => $orderReturn->id]);
            $data = $orderReturn->toArray();
            $data['places'] = $orderReturn->orderPlaces->pluck('number')->toArray();
            list($orderReturn, $error) = StoreOrderService::index($data, $orderReturn->tour);  // Делается для пересчета скидки
        }
        $stationsFrom = SelectRepository::streetsWithStation($order->tour, $order->tour->route->id, $order->stationFrom->city_id);
        $stationsTo = SelectRepository::streetsWithStation($order->tour, $order->tour->route->id, $order->stationTo->city_id);
        $data = $order->toArray();
        $data['places'] = $order->orderPlaces->pluck('number')->toArray();
        list($order, $error) = StoreOrderService::index($data, $order->tour);  // Делается для пересчета скидки

        $setting = Setting::first();
        $codes = $setting->phone_codes;
        return view('index.order.index', compact('order', 'stationsFrom', 'stationsTo', 'orderReturn', 'stationsFromReturn', 'stationsToReturn', 'codes') +
            //['discount_children' => Setting::getField('discount_children')]);
            ['discount_children' => $order->tour->route->discount_child]);

    }

    public function coupon()
    {
        if (!$orderId = session('order.id')) return $this->responseError(['message' => trans('messages.index.order.error')]);

        $order = Order::find($orderId);

        list ($order, $error) = CouponToOrderService::index($order, request('code'));
        $order->update();

        if ($error) {
            return $this->responseError(['message' => trans('messages.index.order.promo_not_found'), 'view' => view('index.order.order.prices', compact('order'))->render()]);
        } else {
            return $this->responseSuccess(['message' => trans('messages.index.order.promo_success'), 'view' => view('index.order.order.prices', compact('order'))->render()]);
        }
    }

    public function children()
    {
        if (!$orderId = session('order.id')) return $this->responseError(['message' => trans('messages.index.order.error')]);

        $order = Order::find($orderId);
        list ($order, $error) = ChildPlaceService::index($order, request('count'));
        $order->update();

        if ($error) {
            return $this->responseError(['message' => trans('messages.index.order.error_two'), 'total' => $order->price, 'view' => view('index.order.order.prices', compact('order'))->render()]);
        } else {
            return $this->responseSuccess(['message' => trans('messages.admin.order.for_children'), 'total' => $order->price, 'view' => view('index.order.order.prices', compact('order'))->render()]);
        }
    }

    public function store(StoreOrderRequest $request)
    {
        $client = null;
        if (request('phone')) {
            $phone = preg_replace('/[^0-9.]+/', '', request('phone'));
            $client = Client::where('phone', $phone)->first();
        } elseif (auth()->user() && auth()->user()->client_id) {
            $client = Client::find(auth()->user()->client_id);
        }
        if (request('first_name') && !preg_match ("/^[a-zA-Zа-яёА-ЯЁ]+$/u", request('first_name'))) {
            return $this->responseError(['message' => trans('validation.first_name')]);
        }

        if (request('middle_name') && !preg_match ("/^[a-zA-Zа-яёА-ЯЁ]+$/u", request('middle_name'))) {
            return $this->responseError(['message' => trans('validation.middle_name')]);
        }

        if (request('last_name') && !preg_match ("/^[a-zA-Zа-яёА-ЯЁ]+$/u", request('last_name'))) {
            return $this->responseError(['message' => trans('validation.last_name')]); 
        }

        // клиент в черном списке
        if ($client && $client->status === Client::STATUS_DISABLE) {
            \Auth::logout();

            return $this->responseError(['message' => trans('validation.black_list'), 'redirect' => route('index.home')]);
        }

        session()->forget('payment');
        if (request('payment')) {
            session(['payment' => request('payment')]);
        }

        if ($client && !empty(request('doc_number')) && !($client->doc_number)){
            $client->doc_number = request('doc_number');
            $client->doc_type = request('doc_type');
            $client->save();
        }

        $order = $this->processOrder('order', $client);

        if (Session::has('order_return')) {
            $orderReturn = $this->processOrder('order_return', $client, true);   // Бронь с обратными билетами
        }

        if (!(auth()->user() && auth()->user()->client_id)) {
            $this->preLastStoreOrder();
        }

        if ($order->count_places > 1 && $order->tour->route->is_international) {
            return $this->responseSuccess(['redirect' => route('index.order.international', ['client'=>$client])]);
        }

        if (auth()->user() && auth()->user()->client_id) {
            $this->lastStoreOrder($order->id);
            if (!empty($orderReturn))   {
                $this->lastStoreOrder($orderReturn->id, 'order_return');
            }

            if (request('is_pay')) {
                return $this->responseSuccess(['redirect' => route('index.order.pay')]);
            }

            return $this->responseSuccess(['redirect' => route('index.order.result')]);
        }

        $this->preLastStoreOrder();
        SendCodeService::index(session('order.client.phone'));

        return $this->responseSuccess(['redirect' => route('index.order.confirm')]);
    }

    protected function processOrder($sessionName, $client, $is_return = false)
    {
        $orderId = session($sessionName.'.id');
        if (!$orderId) {
            return $this->responseError();
        }

        $order = Order::find($orderId);
        if (!$order) {
            session()->forget($sessionName);
            return $this->responseError(['message' => trans('validation.limit_time')]);
        }

        if (Setting::first()->limit_one_order_route && StoreOrderValidator::limitDayRoute($order, $client)) {
            return $this->responseError(['message' => trans('validation.limit_order_route')]);
        }

        //$order->client_id = $client->id;

        if (!(auth()->user() && auth()->user()->client_id)) {
            \DB::commit();

            $pricePlace = StoreOrderService::getPlacePrice($order->tour, $order, $order->station_from_id, $order->station_to_id);

            $childPlaces = $order->orderPlaces->where('is_child', 1)->pluck('number');
            $places = $order->orderPlaces->pluck('number');
            $order->orderPlaces()->delete();

            list($order, $oldPlaces, $error) = SaleToOrderService::index($order, $order->tour, $places, $pricePlace);

            if ($childPlaces->count()) {
                foreach ($childPlaces as $item) {
                    OrderPlace::where('order_id', $order->id)->where('number', $item)->update(['is_child' => true]);
                }
                
                list ($order, $childError) = ChildPlaceService::index($order, $childPlaces->count());
            }

            $oldPlaces['count_places'] = $order->count_places;
            $oldPlaces['price'] = $order->price;
            if ($order->type === Order::TYPE_WAITING) {
                $order->old_places = $oldPlaces;
            }

            if ($error) {
                \DB::rollBack();
                $order->fresh();
            }
        }

        $addServices = [];
        foreach (request('add_services') ?? [] as $key => $item)    {
            if ($item)  {
                $addServices[$key] = ['quantity' => $item];
            }
        }
        $order->addServices()->sync($addServices);           // Сохраняем доп. сервисы
        $order = AddServicesPriceService::index($order);

        foreach (['comment', 'address_from_street', 'address_from_house', 'address_from_building', 'address_from_apart',
                     'custom_address_from', 'address_to_street', 'address_to_house', 'address_to_apart', 'custom_address_to'] as $field) {
            if (request($field) !== null) {
                $order->$field = request($field);
            }
        }

        if (request('is_new_stations')) {
            $order->custom_address_from = request('new_from_station');
            $order->custom_address_to = request('new_to_station');
        } else {
            if (request('station_from_id') && $is_return) {
                $order->station_from_id = request('station_from_id_return');
            } else {
                $order->station_from_id = request('station_from_id');
            }

            if (request('station_to_id') && $is_return) {
                $order->station_to_id = request('station_to_id_return');
            } else {
                $order->station_to_id = request('station_to_id');
            }
        }

        list($stationFromInterval, $stationToInterval) = StationIntervalsService::index($order->tour->route_id,  $order->station_from_id, $order->station_to_id);
        $stationFromTime = Prettifier::prettifyTime($order->tour->time_start, $stationFromInterval);
        $stationToTime = Prettifier::prettifyTime($order->tour->time_start, $stationToInterval);
        $order->station_from_time = $stationFromTime;
        $order->from_date_time =  StationIntervalsService::getDepartureDateTimeFromStation($order->tour, $order->station_from_id, 'Y-m-d H:i:s');
        $order->to_date_time =  StationIntervalsService::getDepartureDateTimeFromStation($order->tour, $order->station_to_id, 'Y-m-d H:i:s');

        if (request('payment')) {
            $order->type_pay = request('payment') == Order::TYPE_PAY_CASH_PAYMENT ? Order::TYPE_PAY_CASH_PAYMENT : Order::TYPE_PAY_WAIT;
        } else {
            $order->type_pay = Setting::all()->first()->is_pay_on ? Order::TYPE_PAY_WAIT : Order::TYPE_PAY_CASH_PAYMENT;
        }

        if ($order->tour->route->is_transfer && $order->transferAddress())  {
            list($order->longitude, $order->latitude) = GeoService::getCoordinates($order->transferAddress(true));
        }

        $order->save();
        return $order;
    }

    protected function preLastStoreOrder()
    {
        $phone = preg_replace('/[^0-9.]+/', '', request('phone'));
        session(['order.client.phone' => $phone]);
        session(['order.client.card' => request('card')]);
        session(['order.client.first_name' => request('first_name')]);
        session(['order.client.middle_name' => request('middle_name')]);
        session(['order.client.last_name' => request('last_name')]);
        session(['order.client.passport' => request('passport')]);
        session(['order.client.doc_type' => request('doc_type')]);
        session(['order.client.doc_number' => request('doc_number')]);
        session(['order.client.gender' => request('gender')]);
        session(['order.client.country_id' => request('country_id')]);
        session(['order.client.birth_day' => request('birth_day')]);
        session(['order.flight_number' => request('flight_number')]);
        session(['order.client.client_id' => '']);
    }

    protected function lastStoreOrder($orderId, $sessionName = 'order')
    {
        if (Session::has($sessionName)) {
            $order = Order::find($orderId);
            $order->update([
                'confirm' => 1,
                'type' => Order::TYPE_WAITING,
                'client_id' => auth()->user()->client_id,
                'flight_number' => session($sessionName.'.flight_number', ''),
            ]);
            
            $was_pushed = 0;
            if(Setting::all()->first()->is_send_to_email && $order->confirm == 1 && $order->type != 'no_completed') {
                $mail["name"] = (empty($order->client->first_name) ? 'Не введено имя' : $order->client->first_name) . " " . (empty($order->client->middle_name) ? ' ' : $order->client->middle_name)
                . " " . (empty($order->client->last_name) ? ' ' : $order->client->last_name);
                $mail["phone"] = empty($order->client->phone) ? 'Не введен телефон' : $order->client->phone;
                $mail["tour"] = $order->tour->route->name;
                $mail["from"] = $order->stationFrom->name . ", " . $order->stationFrom->city->name;
                $mail["to"] = $order->stationTo->name . ", " . $order->stationTo->city->name;
                $mail["date"] = $order->tour->date_start->format('d.m.Y');
                $mail["time"] = $order->tour->time_start;
                $mail["subject"] = "Новая бронь!";
                $mail["main_email"] = Setting::all()->first()->main_email;
                $mail["count"] = $order->count_places;
                $mail["price"] = $order->price;
                $mail["currency"] = ($order->tour->route->currency) ? $order->tour->route->currency->alfa : 'BYN';
                $one = new DateTime(date("Y-m-d H:i:s"));
                $two = new DateTime($order->created_at);
                $interval = $one->diff($two)->format('%i');

                if ($was_pushed == 1) return;
                if($order->status == 'active' && $interval <= 1) {
                    \Mail::send('admin.mail.mail', ['data' => $mail], function ($message) use ($mail) {
                        $message->to($mail["main_email"])
                            ->subject($mail["subject"]);
                    });
                    $was_pushed = 1; 
                }  
            }

            session()->forget($sessionName);
            $order->client->notify(new ActiveOrderNotification($order));
        }
    }

    public function confirm()
    {
        if (session('order.client')) {
            return view('index.order.confirm');
        }
        return redirect(route('index.home'));
    }

    public function doConfirm(AuthConfirmRequest $request)
    {
        // Если по таймеру вышло время и код не ввели
        if (request('code') === "false") {
            Order::destroy(session('order.id', 0));
            Order::destroy(session('order_return.id', 0));
            return $this->responseError(['message' => trans('index.order.time_out')]);
        }

        $result = CheckCodeService::index(session('order.client.phone'), request('code'));

        if (!$result) {
            return $this->responseError(['message' => trans('index.order.not_correct')]);
        }

        $clientId = StoreClientService::index(session('order.client'), true, false);
        $client = Client::find($clientId);
        \Auth::login($client->user, true);
        $this->setStations(false);
        $this->lastStoreOrder(session('order.id'));
        $this->lastStoreOrder(session('order_return.id', 0), 'order_return');

        return $this->responseSuccess(['redirect' => route('index.order.result')]);
    }

    public function result()
    {
        list($order, $orderSecond) = $this->getReturnOrder(auth()->user()->client->order);

        if (!$order || !$order->confirm) return redirect(route('index.home'));
        $partial_prepaid = env('PARTIAL_PREPAID') ? $order->tour->route->partial_prepaid : 0;
        if ($orderSecond && $partial_prepaid != $orderSecond->tour->route->partial_prepaid)   {
            $partial_prepaid = false;
        }

        $arr_price = [];
        foreach($order->orderPlaces as $op){
            array_push($arr_price, $op->price);
        }

        $old_price = array_sum($arr_price);
        return view('index.order.result', compact('order', 'orderSecond', 'partial_prepaid', 'old_price'));
    }

    public function printing(Order $order)
    {
        if ($order->client_id != auth()->user()->client_id) return redirect(route('index.home'));
        $arr_price = [];
        foreach($order->orderPlaces as $op){
            array_push($arr_price, $op->price);
        }

        $old_price = array_sum($arr_price);
        return view('index.order.printing', compact('order', 'old_price'));
    }

    public function confirmInternational()
    {
        $data = [];
        $order = Order::find(session('order.id'));
        $client = Client::find(request('client'));
        $textInputs = $order->tour->route->textInputs;
        $requiredInputs = $order->tour->route->requiredInputsArray;
        if ($order) {
            foreach ($order->orderPlaces as $key => $orderPlace) {
                if ($key) {
                    $data[] = $orderPlace->number;
                }
            }
            return view('index.order.order.confirm_international', compact('data', 'textInputs', 'requiredInputs', 'client'));
        } else {
            return redirect(route('index.home'));
        }

    }

    public function doConfirmInternational()
    {
        $dataRequest = request()->all();
        $order = Order::find(session('order.id'));
        $orderReturn = Order::find(session('order_return.id'));
        $requiredInputs = $order->tour->route->requiredInputsArray;
        if ($order) {
            foreach ([$order, $orderReturn] as $curOrder) {
                $orderPlaces = $curOrder->orderPlaces ?? [];
                unset($orderPlaces[0]);
                foreach ($orderPlaces as $key => $orderPlace) {
                    $isFind = false;
                    foreach ($requiredInputs as $item)
                        if (isset($dataRequest[$item][$key])) {
                            $isFind = true;
                            $orderPlace->$item = $item == 'birth_day' ? new Carbon($dataRequest[$item][$key]) : $dataRequest[$item][$key];
                        }
                    if ($isFind)
                        $orderPlace->save();
                }
            }

            if (auth()->user() && auth()->user()->client_id) {
                $this->lastStoreOrder(session('order.id'));
                $this->lastStoreOrder(session('order_return.id', 0), 'order_return');
                return redirect()->route('index.order.result');
            } else {
                SendCodeService::index(session('order.client.phone'));

                return redirect(route('index.order.confirm'));
            }

            
        } else {
            return redirect(route('index.home'));
        }
    }

    public function setStations($render = true)
    {
        $curOrder = request('return_ticket') == 1 ? 'order_return' : 'order';
        if ($order = Order::find(session($curOrder.'.id'))) {
            $stationFromId = $render ? request('station_from_id') : session($curOrder.'.station_from_id');
            $stationToId   = $render ? request('station_to_id') : session($curOrder.'.station_to_id');

            if (env('FRAGMENTATION_RESERVED')) {
                \DB::table('orders')->where('id', session($curOrder.'.id'))->update(['status' => Order::STATUS_DISABLE]);
                if ($order->orderPlaces->count() > $order->tour->ordersFreeStations($stationFromId, $stationToId)) {
                    $message =  trans('messages.index.order.not_available');
                    $message .= request('destination') == 'from' ? trans('messages.index.order.from') : trans('messages.index.order.to');
                    \DB::table('orders')->where('id', session($curOrder.'.id'))->update(['status' => Order::STATUS_ACTIVE]);
                    return $this->responseError([
                        'message' => $message,
                        'station_from_id' => session($curOrder.'.station_from_id'),
                        'station_to_id' => session($curOrder.'.station_to_id')
                    ]);
                }

                \DB::table('orders')->where('id', session($curOrder.'.id'))->update(['status' => Order::STATUS_ACTIVE]);
            }

            list ($stationFromInterval, $stationToInterval) = StationIntervalsService::index($order->tour->route->id, $stationFromId, $stationToId);
            $dateFrom = Prettifier::prettifyDateTimeFull($order->tour->prettyDateStart, $order->tour->time_start, $stationFromInterval);
            $dateTo = Prettifier::prettifyDateTimeFull($order->tour->prettyDateStart, $order->tour->time_start, $stationToInterval);

            $order->station_from_id = $stationFromId;
            $order->station_to_id = $stationToId;
            $order->save();

            if ($render) {
                session([$curOrder.'.station_from_id' => $stationFromId]);
                session([$curOrder.'.station_to_id' => $stationToId]);

                $data = $order->toArray();
                $data['station_from_id'] = $stationFromId;
                $data['station_to_id'] = $stationToId;
                $data['places'] = $order->orderPlaces->pluck('number')->toArray();
                $data['source'] = Order::SOURCE_SITE;

                list($order, $error) = StoreOrderService::index($data, $order->tour);
                $htmlPrices = view('index.order.order.prices', compact('order'))->render();

                return [
                    'DateFrom' => $dateFrom,
                    'DateTo' => $dateTo,
                    'message' => trans('messages.admin.order.stop_updated'),
                    'htmlPrices' => $htmlPrices
                ];
            }
        }
    }

    public function pay($partial = false)
    {
        list($order, $orderSecond) = $this->getReturnOrder(auth()->user()->client->order);
        if  ($order->status == Order::STATUS_DISABLE || $order->type_pay == Order::TYPE_PAY_SUCCESS) {
            return abort(403);
        }
        if ($partial)   {
            $partial = $order->tour->route->partial_prepaid > 0;        // Можно ли включать частичную предоплату для заказа
        }
        $order->partial_prepaid = $partial;
        $order->prepaid_price = $partial ? $order->calcPrepaid() : null;
        $order->save();
        if ($orderSecond) {
            if ($partial)   {
                $partial = $orderSecond->tour->route->partial_prepaid > 0;
            }
            $orderSecond->partial_prepaid = $partial;
            $orderSecond->prepaid_price = $partial ? $orderSecond->calcPrepaid() : null;
            $orderSecond->save();
        }
        $service = new ServicePayService();
        return $service->index($order);
    }

    public function payOrder(Order $order)
    {
        list($order, $orderSecond) = $this->getReturnOrder($order);
        if  ($order->status == Order::STATUS_DISABLE || $order->type_pay == Order::TYPE_PAY_SUCCESS) {
            return abort(403);
        }
        $order->update(['partial_prepaid' => false]);
        if ($orderSecond) {
            $orderSecond->update(['partial_prepaid' => false]);
        }
        
        $service = new ServicePayService();
        return $service->index($order);
    }

    public function printOrder(Order $order)
    {
        $order = $order->id ? $order : auth()->user()->client->order;
        return PrintOrderService::index($order);
    }

    protected function getSignature($order)
    {
        $wsb_seed = "order-" . $order->id;
        $wsb_storeid = env('PAY_ID');
        $wsb_order_num = $order->slug;
        $wsb_test = "1";
        $wsb_currency_id = "BYN";
        $wsb_total = (string)$order->price;
        $SecretKey = env('PAY_KEY');
        $str = $wsb_seed . $wsb_storeid . $wsb_order_num . $wsb_test . $wsb_currency_id . $wsb_total . $SecretKey;
        $wsb_signature = sha1($str);
        return $wsb_signature;
    }

    public function noticePayOrder(Request $request)
    {
        $data = request()->all();
        $signature = $this->getResponseSignature($data);
        if ($signature == $data['wsb_signature']) {
            if ($data['payment_type'] == '1' || $data['payment_type'] == '4' || $data['payment_type'] == '10') {
                \Log::info('Оплата подтверждена', $data);
                $order = Order::where('slug', $data['site_order_id'])->firstOrFail();
                $order->type_pay = Order::TYPE_PAY_SUCCESS;
                $order->is_pay = 1;
                $order->pay_id = $data['transaction_id'];
                $order->save();
                $this->notification($order);
                if ($order->returnOrder) {
                    $order->returnOrder->update(['pay_id' => $order->id, 'type_pay' => Order::TYPE_PAY_SUCCESS]);
                }
            }
            else {
                $order = Order::where('slug', $data['site_order_id'])->first();
                $order->type_pay = Order::TYPE_PAY_CANCEL;
                $order->is_pay = 0;
                $order->save();
                if ($order->returnOrder){
                    $order->returnOrder->update(['pay_id' => $order->id, 'type_pay' => Order::TYPE_PAY_CANCEL]);
                }
            }
        } else {
            \Log::info('Подписи не совпадают.', $data);
            abort(401);
        }
    }

    public function notification(Order $order) {
        $was_pushed = 0;
        if(Setting::all()->first()->turn_on_notification_if_order_paid && $order->confirm == 1 && $order->type != 'no_completed') {
            $mail["name"] = (empty($order->client->first_name) ? 'Не введено имя' : $order->client->first_name) . " " . 
                (empty($order->client->middle_name) ? ' ' : $order->client->middle_name)
            . " " . (empty($order->client->last_name) ? ' ' : $order->client->last_name);
            $mail["phone"] = empty($order->client->phone) ? 'Не введен телефон' : $order->client->phone;
            $mail["tour"] = $order->tour->route->name;
            $mail["from"] = $order->stationFrom->name . ", " . $order->stationFrom->city->name; 
            $mail["to"] = $order->stationTo->name . ", " . $order->stationTo->city->name;
            $mail["date"] = $order->tour->date_start->format('d.m.Y');
            $mail["time"] = Carbon::createFromTimeString($order->tour->date_start->format('Y-m-d') . ' ' . $order->tour->time_start)->addMinutes($order->orderPlaces->start_min)->toW3cString();
            $mail["subject"] = "Новая оплаченная бронь!";  
            $mail["main_email"] = Setting::all()->first()->main_email;
            $mail["count"] = $order->count_places;
            $mail["price"] = $order->price;
            $mail["currency"] = ($order->tour->route->currency) ? $order->tour->route->currency->alfa : 'BYN';
            
            if ($was_pushed == 1) return;
            if($order->status == 'active') {
                \Mail::send('admin.mail.mail', ['data' => $mail], function ($message) use ($mail) {
                    $message->to($mail["main_email"])
                        ->subject($mail["subject"]);
                });
                $was_pushed = 1; 
            }  
        }
    }

    public function indTransfer(\Illuminate\Http\Request $request)
    {
        $route = Route::findOrFail($request->route_id);
        $date_time_start = Carbon::createFromFormat('d.m.Y H:i', $request->date.' 00:00');
        $tour = Tour::create([
            'bus_id' => Bus::getSystemBus()->id,
            'route_id' => $route->id,
            'driver_id' => Driver::getSystemDriver()->id,
            'price' => 15,
            'date_start' => $request->date,
            'date_finish' => $date_time_start->copy()->addMinutes($route->interval)->format('d.m.Y'),
            'time_start' => Prettifier::prettifyTime($request->time),
            'time_finish' => '00:00',
            'date_time_start' => $date_time_start,
            'date_time_finish' => $date_time_start->copy()->addMinutes($route->interval),
            'status' => Tour::STATUS_ACTIVE,
            'reservation_by_place' => 0,
            'is_collect' => 0,
            'is_show_front' => 0,
            'is_individual' => 1,
        ]);
        $stationFrom = GetFromStation::index($route, $request->city_from_id);
        $stationTo = GetToStation::index($route, $request->city_to_id);
        session(['order.station_from_id' => $stationFrom->id]);
        session(['order.station_to_id' => $stationTo->id]);

        $data = ['tour_id' => $tour->id,
            'type' => Order::TYPE_NO_COMPLETED,
            'source' => Order::SOURCE_SITE,
            'places_with_number' => 0,
            'places' => [''],
            'client_id' => auth()->user() ? auth()->user()->client_id : null,
            'status' => Order::STATUS_ACTIVE,
            'station_from_id' => $stationFrom->id,
            'station_to_id' => $stationTo->id,
            'city_from_id' => $request->city_from_id,
            'city_to_id' => $request->city_to_id,
            ];

        list($order, $error) = StoreOrderService::index($data, $tour);
        if ($error) {
            return $this->responseError(['message' => $error]);
        }

        session(['order.id' => $order->id]);
        return redirect()->route('index.order.index');
    }

    protected function getResponseSignature($data)
    {
        $str = '';
        $str .= $data['batch_timestamp'];
        $str .= $data['currency_id'];
        $str .= $data['amount'];
        $str .= $data['payment_method'];
        $str .= $data['order_id'];
        $str .= $data['site_order_id'];
        $str .= $data['transaction_id'];
        $str .= $data['payment_type'];
        $str .= $data['rrn'];
        $str .= env('PAY_KEY');
        return md5($str);
    }

    protected function getReturnOrder($order)   //  Возвращает последний заказ и заказ на обратный билет (если есть)
    {
        $orderSecond = Order::where('return_order_id', $order->id)->first();
        if ($orderSecond) {       // Заказ сам является обратным билетом к другому
            $order = $orderSecond;
        }
        $orderSecond = $order->returnOrder;

        return [$order, $orderSecond];
    }

    public function getEkamCheck(Order $order)
    {
        try {
            $client = new HTTP([
                'base_uri' => 'https://app.ekam.ru/api/online/v2/receipt_requests',
                'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json', 'X-Access-Token' => env('EKAM_TOKEN')],
                'query' => ['order_id' => $order->id]
            ]);

            $response = $client->request('GET');
            if ($response->getStatusCode() == 200) {
                $responseJSON = json_decode($response->getBody());
                return redirect($responseJSON->items[0]->receipt_url);
            } else {
                return \Redirect::back();
            }
        } catch (\Exception $e) {
            \Log::info($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            return \Redirect::back();
        }
    }

    public function pdf($slug)
    {
        $order = Order::where('slug', $slug)->first();
        if ($order) {
            return ServicePdf::generatePdf($order);
        } else {
            abort(404);
        }
    }

    public function ticket($slug)
    {
        if ($slug) {
            $url = env('APP_URL').'/pdf/' . $slug;
            return view('index.print.order_pdf', compact('url'));
        } else {
            abort(404);
        }
    }

}
