<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Requests\Api\Client\AddOrderRequest;
use App\Http\Requests\Api\Client\CancelOrderRequest;
use App\Http\Requests\Api\Client\ConfirmOrderRequest;
use App\Http\Requests\Api\Client\ListOrderRequest;
use App\Http\Requests\Api\Client\TaxiOrderRequest;
use App\Http\Requests\Api\Client\OrderRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\ShowOrderRequest;
use App\Http\Requests\Api\Client\UpdateOrderRequest;
use App\Models\Client;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Station;
use App\Models\Token;
use App\Models\Tour;
use App\Models\Config;
use App\Notifications\Order\ActiveOrderNotification;
use App\Notifications\Order\DisableOrderNotification;
use App\Services\Log\TelegramLog;
use App\Services\Order\StoreOrderService;
use App\Services\Pays\ServicePayService;
use App\Services\Client\StoreClientService;
use App\Services\Pdf\ServicePdf;
use App\Services\Geo\GeoService;
use Carbon\Carbon;
use DateTime;
use App\Models\OrderHistory;
use Illuminate\Http\Request;
use Vinkla\Pusher\Facades\Pusher;
use GuzzleHttp\Client as HTTP;

class OrderController extends Controller
{
    public function index(ListOrderRequest $request)
    {
        $clientId = Token::where('api_token', $request->api_token)->first()->client_id;
        $orders = Order::with('orderPlaces', 'stationFrom', 'stationTo', 'tour', 'tour.route')->where('client_id', $clientId)->get();
        $data = [];

        foreach ($orders as $order) {
            if ($order->tour && $order->tour->route) {
                $data[] = [
                    'id' => $order->id,
                    'stationFromTime' => Carbon::createFromTimeString($order->tour->date_start->format('Y-m-d') . ' ' . $order->station_from_time)->toW3cString(),
                    'stationFromName' => $order->stationFrom->address . ', ' . $order->stationFrom->name,
                    'stationToTime' => Carbon::createFromTimeString($order->tour->date_start->format('Y-m-d') . ' ' . $order->station_to_time)->toW3cString(),
                    'stationToName' => $order->stationTo->address . ', ' . $order->stationTo->name,
                    'status' => $order->type == Order::TYPE_COMPLETED ? Order::TYPE_COMPLETED : $order->status,
                    'routeName' => $order->tour->route->name,
                    'price' => $order->price . ' ' . trans('admin_labels.currencies_short.' . ($order->tour->route->currency->alfa ?? 'BYN')),
                    'typePay' => $order->type_pay,
                ];
            }
        }
        return $data;
    }

    public function show(ShowOrderRequest $request)
    {
        $clientId = Token::where('api_token', $request->api_token)->first()->client_id;
        if ($clientId && ($order = Order::where('id', $request->order_id)->where('client_id', $clientId)->with('orderPlaces')->first())) {
            $currency = $order->tour->route->currency  ? $order->tour->route->currency->alfa : 'BYN';

            $orderPlaces = $order->orderPlaces->first();

            $stationFromTime = Carbon::createFromTimeString($order->tour->date_start->format('Y-m-d') . ' ' . $order->tour->time_start)->addMinutes($orderPlaces->start_min)->toW3cString();
            $stationToTime = Carbon::createFromTimeString($order->tour->date_start->format('Y-m-d') . ' ' . $order->tour->time_start)->addMinutes($orderPlaces->finish_min)->toW3cString();

            return [
                'id' => $order->id,
                'slug' => $order->slug,
                'status' => $order->status,
                'stationFromId' => $order->station_from_id,
                'stationFromTime' => $stationFromTime,
                'stationFromTimeOrder' => Carbon::parse($order->station_from_time),
                'stationFromName' => ($order->tour->route->is_transfer && $order->tour->route->flight_type == 'departure') ? 
                    $order->transferAddress(true) : $order->stationFrom->address.', '.$order->stationFrom->name,
                'stationToId' => $order->station_to_id,
                'stationToTime' => $stationToTime,
                'stationToName' => ($order->tour->route->is_transfer && $order->tour->route->flight_type == 'arrival') ? 
                    $order->transferAddress(true) : $order->stationTo->address.', '.$order->stationTo->name,
                'routeName' => $order->tour->route->name,
                'busId' => $order->tour->bus->id,
                'busName' => $order->tour->bus->name,
                'busNumber' => $order->tour->bus->number,
                'driverName' => $order->tour->driver->full_name,
                'driverNumber' => $order->tour->driver->work_phone,
                'places' => $order->orderPlaces->pluck('number'),
                'price' => $order->price.' '. trans('admin_labels.currencies_short.'.$currency),
                'comment' => $order->comment,
                'typePay' => $order->type_pay,
                'appeared' => (bool) $order->orderPlaces->where('appearance', '===', 1)->count(),
                'allIsNotAppeared' => $order->orderPlaces->where('appearance', '===', 0)->count() === $order->orderPlaces->count(),
                'isRouteTaxi' => (bool)$order->tour->route->is_route_taxi,
                'transferAddress' => $order->transferAddress(),
                'payUrl' => $order->pay_url,
                'ticketUrl' => route('index.profile.generatePDF', ["id" => $order->id]),
                'checkUrl' => null,
            ];
        }
        return [];
    }

    public function add(AddOrderRequest $request)
    {
        $tour = Tour::find($request->tour_id);
        $clientId = Token::where('api_token', $request->api_token)->first()->client_id;

        $data = [
            'slug' => $clientId,
            'tour_id' => $tour->id,
            'station_from_id' => $request->station_from_id,
            'station_to_id' => $request->station_to_id,
            'source' => Order::SOURCE_APP,
            'places_with_number' => 0,
            'places' => array_fill(0, $request->count_places, null),
            'status' => Order::STATUS_ACTIVE,
            'confirm' => 1,
            'type' => Order::TYPE_WAITING,
            'client_id' => $clientId,
        ];

        list ($order, $error) = StoreOrderService::index($data, $tour);
        if ($error) $this->responseJsonError(['errors' => ['order' => [$error]]], 400);
        $client = Client::where('id', $clientId)->first();
        $client->notify(new ActiveOrderNotification($order));
        return $this->responseSuccess();
    }

    public function cancel(CancelOrderRequest $request)
    {
        $clientId = Token::where('api_token', $request->api_token)->first()->client_id;

        $order = Order::where('id', $request->order_id)
            ->where('client_id', $clientId)
            ->first();
        if ($order) {
            $order->status = Order::STATUS_DISABLE;

            $orderHistory = new OrderHistory();
            $orderHistory->order_id = $order->id;
            $orderHistory->action = OrderHistory::ACTIVE_CANCEL;
            $orderHistory->source = Order::SOURCE_APP;
            $orderHistory->client_id = $clientId;
            $orderHistory->save();
            $order->save();
            return $this->responseSuccess();
        } else {
            return $this->responseJsonError(['errors' => ['order' => [trans('validation.no_exist')]]], 400);
        }
    }

    public function confirm(ConfirmOrderRequest $request)
    {
        $clientId = Token::where('api_token', $request->api_token)->first()->client_id;
        $order = Order::where('id', $request->order_id)->where('client_id', $clientId)->first();
        $client = Client::find($clientId);
        $mail = [];

        $orderPlaces = $order->orderPlaces->first();
        Tour::find($request->station_from_id);

        if ($order) {
            if ($request->type_pay !== null && in_array($request->type_pay, Order::TYPE_PAYS, true)) {
                $order->type_pay = $request->type_pay;
            }

            $order->status = Order::STATUS_ACTIVE;
            $order->confirm = true;
            $order->type = Order::TYPE_WAITING;
            $order->save();

            if (Setting::all()->first()->is_pay_on && env('TYPE_PAY') == 'WEBPAY') {
                if ($request->has('payment_back_url')) {
                    $order->payment_baсk_url = $request->get('payment_back_url');
                }
                $result = ServicePayService::webpayGetPaymentInvoice($order);
                if ($result['status'] == 'ok') {
                    $order->update(['pay_url' => $result['url']]);
                }
            }

            $was_pushed = 0;
            if (Setting::all()->first()->is_send_to_email && $order->confirm == 1 && $order->type != 'no_completed') {
                $mail["name"] = (empty($client->first_name) ? 'Не введено имя' : $client->first_name) . " " .
                    (empty($client->middle_name) ? ' ' : $client->middle_name)
                . " " . (empty($client->last_name) ? ' ' : $client->last_name);
                $mail["phone"] = empty($order->client->phone) ? 'Не введен телефон' : $order->client->phone;
                $mail["tour"] = $order->tour->route->name;
                $mail["from"] = $order->stationFrom->name . ", " . $order->stationFrom->city->name;
                $mail["to"] = $order->stationTo->name . ", " . $order->stationTo->city->name;
                $mail["date"] = $order->tour->date_start->format('d.m.Y');
                $mail["time"] = Carbon::createFromTimeString($order->tour->date_start->format('Y-m-d') . ' ' . $order->tour->time_start)->addMinutes($orderPlaces->start_min)->toW3cString();
                $mail["subject"] = "Новая бронь!";
                $mail["main_email"] = Setting::all()->first()->main_email;
                $mail["count"] = $order->count_places;
                $mail["price"] = $order->price;
                $mail["currency"] = ($order->tour->route->currency) ? $order->tour->route->currency->alfa : 'BYN';
                $one = new DateTime(date("Y-m-d H:i:s"));
                $two = new DateTime($order->created_at);
                $interval = $one->diff($two)->format('%i');
                if ($was_pushed == 1) return;
                if ($order->status == 'active' && $interval <= 1) {
                    \Mail::send('admin.mail.mail', ['data' => $mail], function ($message) use ($mail) {
                        $message->to($mail["main_email"])
                            ->subject($mail["subject"]);
                    });
                    $was_pushed = 1;
                }
            }

            $order->client->notify(new ActiveOrderNotification($order));
            return $this->responseSuccess();
        } else {
            return $this->responseJsonError(['errors' => ['order' => [trans('validation.no_exist')]]], 400);
        }
    }

    public function update(UpdateOrderRequest $request)
    {
        $clientId = Token::where('api_token', $request->api_token)->first()->client_id;
        $order = Order::where('id', $request->order_id)
            ->where('client_id', $clientId)
            ->first();

        if ($order) {
            $data = $order->toArray();
            $data['station_from_id'] = $request->get('station_from_id', $order->station_from_id);
            $data['station_to_id'] = $request->get('station_to_id', $order->station_to_id);

            list ($order, $error) = StoreOrderService::index($data, $order->tour);
            if ($error) return $this->responseError(['message' => $error]);
            return [
                'station_from_time' => $order->station_from_time,
                'station_to_time' => $order->station_to_time
            ];
        } else {
            return $this->responseJsonError(['errors' => ['order' => [trans('validation.no_exist')]]], 400);
        }
    }

    public function pay(OrderRequest $request)
    {
        $clientId = Token::where('api_token', $request->api_token)->first()->client_id;

        $order = Order::where('id', $request->order_id)
            ->where('client_id', $clientId)
            ->first();

        if ($order) {
            $service = new ServicePayService();
            return $this->responseSuccess(['url' => $service->index($order)]);
        } else {
            return $this->responseJsonError(['errors' => ['order' => [trans('validation.no_exist')]]], 400);
        }
    }

    public function getBinding(OrderRequest $request)
    {
        $clientId = Token::where('api_token', $request->api_token)->first()->client_id;

        if (!$clientId) {
            return $this->responseJsonError(['errors' => ['client' => [trans('validation.no_exist')]]], 401);
        }

        $order = Order::where('id', $request->order_id)
            ->where('client_id', $clientId)
            ->first();

        if (!$order) {
            return $this->responseJsonError(['errors' => ['order' => [trans('validation.no_exist')]]], 400);
        }

        $service = new ServicePayService;

        $binding = $service->getBinding($order);

        if (!$binding) {
            return $this->responseError([]);
        }

        return $this->responseSuccess(['bindingId' => $binding]);
    }


    public function payBinding(OrderRequest $request)
    {
        $clientId = Token::where('api_token', $request->api_token)->first()->client_id;

        if (!$clientId) {
            return $this->responseJsonError(['errors' => ['client' => [trans('validation.no_exist')]]], 401);
        }

        $order = Order::where('id', $request->order_id)
            ->where('client_id', $clientId)
            ->first();

        if (!$order) {
            return $this->responseJsonError(['errors' => ['order' => [trans('validation.no_exist')]]], 400);
        }

        if (!$request->bindingId) {
            return $this->responseJsonError(['errors' => ['order' => [trans('validation.no_exist')]]], 400);
        }

        $service = new ServicePayService;

        try {
            $binding = $service->getBinding($order, $request->bindingId);
        } catch (\Exception $exception) {
            return $this->responseError(['errors' => ['message' => $exception->getTraceAsString()]]);
        }

        if (!$binding) {
            return $this->responseError([]);
        }

        return $this->responseSuccess(['bindingId' => $binding]);
    }


    public function last(ListOrderRequest $request)
    {
        $clientId = Token::where('api_token', $request->api_token)->first()->client_id;
        $order = Order::with('orderPlaces', 'stationFrom', 'stationTo', 'tour')->where('client_id', $clientId)->latest()->first();

        if (!$order) {
            return [];
        }

        return [
            'id' => $order->id,
            'stationFromTime' => Carbon::createFromTimeString($order->tour->date_start->format('Y-m-d') . ' ' . $order->station_from_time)->toW3cString(),
            'stationFromName' => $order->stationFrom->address.', '.$order->stationFrom->name,
            'stationToTime' => Carbon::createFromTimeString($order->tour->date_start->format('Y-m-d') . ' ' . $order->station_to_time)->toW3cString(),
            'stationToName' => $order->stationTo->address.', '.$order->stationTo->name,
            'status' => $order->type == Order::TYPE_COMPLETED ? Order::TYPE_COMPLETED : $order->status,
            'routeName' => $order->tour->route->name,
            'price' => $order->price.' '. trans('admin_labels.currencies_short.'.$order->tour->route->currency->alfa),
            'typePay' => $order->type_pay,
            'source' => Order::SOURCE_DRIVER,
        ];
    }


    public function generatePdf(OrderRequest $request)
    {
        $clientId = Token::where('api_token', $request->api_token)->first()->client_id;

        if (!$clientId) {
            return $this->responseJsonError(['errors' => ['client' => [trans('validation.no_exist')]]], 401);
        }

        $order = Order::query()
            ->where('id', $request->order_id)
            ->where('client_id', $clientId)
            ->firstOrFail();

        return ServicePdf::generatePdf($order);
    }

    public function getEkamCheck(OrderRequest $request)
    {
        $message = 'К сожалению, у Вашей брони нет чека! По данному вопросу обратитесь к диспетчеру';

        try {
            $clientId = Token::where('api_token', $request->api_token)->first()->client_id;
            if (!$clientId) {
                return $this->responseJsonError(['errors' => ['client' => [trans('validation.no_exist')]]], 401);
            }

            $order = Order::query()
                ->where('id', $request->order_id)
                ->where('client_id', $clientId)
                ->firstOrFail();


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
                return view('errors.404', compact('message'));
            }
        } catch (\Exception $e) {
            \Log::info($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            return view('errors.404', compact('message'));
        }
    }

    public function taxiOrder(TaxiOrderRequest $request)
    {
        $stationFrom = Station::findOrFail($request->station_from_id);
        $stationTo = Station::findOrFail($request->station_to_id);
        $clientId = Token::where('api_token', $request->api_token)->first()->client_id;

        $tours = Tour::whereStatus(Tour::STATUS_ACTIVE)
            ->with('route', 'bus', 'bus.monitoring')
            ->where('type_driver', 'collection')
            ->where('date_time_start', ">", date("Y-m-d H:i:s", strtotime('-5 hours')))
            ->whereHas('route', function ($q) {
                $q->where('is_taxi', 1);
            })
            ->get();
        foreach ($tours as $item)   {
            $item->distance_to_client = GeoService::getDistance($stationFrom->latitude, $stationFrom->longitude, $item->bus->monitoring->last()->latitude, $item->bus->monitoring->last()->longitude);
        }

        $taxiTour = $tours->sortBy('distance_to_client')->first();  // Рейс ближайшей машины такси

        $data = [
            'tour_id' => $taxiTour->id,
            'station_from_id' => $stationFrom->id,
            'station_to_id' => $stationTo->id,
            'source' => Order::SOURCE_CLIENT_APP,
            'places' => array_fill(0, $request->places, ''),
            'status' => Order::STATUS_ACTIVE,
            'confirm' => true,
            'price' => $taxiTour->price,
            'type' => Order::TYPE_WAITING,
            'type_pay' => Order::TYPE_PAY_CASH_PAYMENT,
            'client_id' => $clientId,
        ];

        list ($order, $error) = StoreOrderService::index($data, $taxiTour);
        

      /*  Pusher::trigger('driver-taxi-channel2', 'new-taxi-order', [     // Будет использовано для отправки всем водителей сообщение о новом заказе 
            'app_url' => env('APP_URL'),
            'from' => $stationFrom->name,
            'from_id' => $stationFrom->id,
            'to' => $stationTo->name,
            'to_id' => $stationTo->id,
            'client_id' => $clientId,
            'places' => $request->places ?? 1,
            'message' => "Новый заказ такси!",
        ]);*/

        if ($error) {
            $data['message'] = $error;
            return $this->responseError($data);
        } else {
            return $this->responseSuccess();
        }
    }

    public function cancelTaxiOrder(OrderRequest $request)
    {
        $clientId = Token::where('api_token', $request->api_token)->first()->client_id;
        Pusher::trigger('driver-taxi-channel2', 'close-taxi-order', [
            'client_id' => $clientId,
            'app_url' => env('APP_URL'),
        ]);
        return $this->responseSuccess();

    }

    public function addTransferOrder(Request $request)
    {
        try {
            if (Config::getValue('global', 'enable_transfer_api', true) == false)  {
                return $this->responseError(['message' => 'API отключено']);
            }
            $tour = Tour::findOrFail(request('tour_id'));
            $stationTo = $tour->route->stations->last();
            if (!empty($tour)) {
                if (!empty($request->name) && $request->phone) {
                    $clientId = StoreClientService::index(['phone' => $request->phone, 'first_name' => $request->name, 'client_id' => ''], false, true, false);
                } else {
                    $clientId = Client::whereEmail('QRCODE')->first()->id;
                }

                $data = [
                    'tour_id' => request('tour_id'),
                    'station_from_id' => $tour->route->stations->first()->id,
                    'station_to_id' => $stationTo->id,
                    'source' => Order::SOURCE_SITE,
                    'address_to_street' => $request->address,
                    'places' => array_fill(0, $request->count_places, null),
                    'status' => Order::STATUS_ACTIVE,
                    'confirm' => true,
                    'price' => $tour->price,
                    'type' => Order::TYPE_WAITING,
                    'type_pay' => Order::TYPE_PAY_WAIT,
                    'client_id' => $clientId,
                    'phone' => $request->phone,
                ];

                list ($order, $error) = StoreOrderService::index($data, $tour);

                $order->update(['price' => $order->price + 5]);
                
                if ($error) {
                    $data['message'] = $error;
                    return $this->responseError($data);
                } else {
                    $result = ServicePayService::webpayGetPaymentInvoice($order);
                    if ($result['status'] == 'ok') {
                        $client = Client::where('id', $clientId)->first();
                        $client->notify(new ActiveOrderNotification($order));
                        $order->update(['pay_url' => $result['url']]);
                        return $this->responseSuccess(['order_id' => $order->slug, 'paymentUrl' => $result['url']]);
                    } else {
                        return $this->responseError(['error' => $result['body']->error]);
                    }
                }

            }
        } catch (\Exception $e) {
            return $this->responseError(['message' => $e->getMessage()]);
        }
    }

    public function info(Request $request)
    {
        if ($order = Order::where('slug', $request->slug)->with('orderPlaces')->first()) {
            $currency = $order->tour->route->currency  ? $order->tour->route->currency->alfa : 'BYN';

            $orderPlaces = $order->orderPlaces->first();

            $stationFromTime = Carbon::createFromTimeString($order->tour->date_start->format('Y-m-d') . ' ' . $order->tour->time_start)->addMinutes($orderPlaces->start_min)->toW3cString();
            $stationToTime = Carbon::createFromTimeString($order->tour->date_start->format('Y-m-d') . ' ' . $order->tour->time_start)->addMinutes($orderPlaces->finish_min)->toW3cString();

            return [
                'id' => $order->id,
                'slug' => $order->slug,
                'status' => $order->status,
                'stationFromId' => $order->station_from_id,
                'stationFromTime' => $stationFromTime,
                'stationFromTimeOrder' => Carbon::parse($order->station_from_time),
                'stationFromName' => $order->stationFrom->address.', '.$order->stationFrom->name,
                'stationToId' => $order->station_to_id,
                'stationToTime' => $stationToTime,
                'stationToName' => $order->stationTo->address.', '.$order->stationTo->name,
                'routeName' => $order->tour->route->name,
                'busId' => $order->tour->bus->id,
                'busName' => $order->tour->bus->name,
                'busNumber' => $order->tour->bus->number,
                'driverName' => $order->tour->driver->full_name,
                'driverNumber' => $order->tour->driver->work_phone,
                'places' => $order->orderPlaces->pluck('number'),
                'price' => $order->price.' '. trans('admin_labels.currencies_short.'.$currency),
                'comment' => $order->comment,
                'typePay' => $order->type_pay,
                'appeared' => (bool) $order->orderPlaces->where('appearance', '===', 1)->count(),
                'transferAddress' => $order->transferAddress(),
            ];
        }
        return [];
    }

}
