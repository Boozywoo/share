<?php

namespace App\Http\Controllers\Driver;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Tour;
use App\Models\Status;
use App\Models\Station;
use App\Models\OrderPlace;
use App\Models\DriverAppSetting;
use App\Models\Setting;
use App\Models\OrderHistory;
use App\Services\Order\AddServicesPriceService;

use App\Services\Pdf\ServicePdf;
use GuzzleHttp\Client as HTTP;

class PassengerController extends Controller
{
    public function __construct()
    {
        $this->middleware('isDriver');
    }

    public function index(Tour $tour)
    {
        return view('driver.passengers')->with('tour', $tour);
    }

    public function landing(Tour $tour)
    {
        $type_driver = 'collection';
        return $this->list_of_passengers($tour, $type_driver);
    }

    public function way(Tour $tour)
    {
        $type_driver = 'way';
        return $this->list_of_passengers($tour, $type_driver);
    }

    public function list_of_passengers($tour, $type_driver)
    {
        if ($tour->driver_id !== \Auth::guard('driver')->id())     {
            abort(404);
        }
        $statuses = [];
        $clients = [];

        $sum = 0;
        $is_all = true;

        $env = getenv('PUSHER_APP_KEY');
        $filterType = request('filterType');
        $d_a_setting = DriverAppSetting::first();
        $setting = Setting::first();

        $bus = $tour->bus;
        $places = $bus->places;
        $stations = $tour->route->stations;
        $tour->type_driver = $type_driver;
        $tour->save();

        $orders = $tour->ordersConfirm->where('is_finished', '0')->sortByDesc('from_date_time');
        if ($tour->route->is_transfer && $tour->route->flight_type == 'arrival') {
            $orders = $orders->sortBy('to_date_time');  // Сортировка по времени прибытия на адрес
        }

        if ($filterType == "all") {
            $orders = $tour->ordersConfirm;
        } elseif ($filterType == "disable") {
            $orders = $tour->ordersConfirm->where('is_finished', '1');
        }

        foreach ($orders as $order) {
            array_push($clients, $order->client);
            if (!empty($order->client->status)) {
                array_push($statuses, $order->client->status);
            }
            if ($is_all) {
                foreach ($order->orderPlaces as $o) {
                    if ($o->appearance == 0) {
                        $is_all = false;
                        break;
                    }
                }
            }
            if ($tour->route->is_transfer && $tour->route->flight_type == 'arrival') {    // Создаем виртуальные остановки на каждый адрес, только для отображения в вод. приложении, в базу их не сохраняем
                $st = new Station(['name' => $order->transferAddress(), 'city_id' => $order->stationTo->city_id, 'latitude' => $order->latitude, 'longitude' => $order->longitude]);
                $st->id = $order->id + 2000;
                $order->station_to_id = $st->id;
                $order->stationTo = $st;
                $stations->push($st);
            }
            if ($tour->route->is_transfer && $tour->route->flight_type == 'departure') {
                $st = new Station(['name' => $order->transferAddress(), 'city_id' => $order->stationFrom->city_id]);
                $st->id = $order->id + 2000;
                $order->station_from_id = $st->id;
                $order->stationFrom = $st;
                $stations->prepend($st);
            }
            if ($tour->route->is_taxi && !empty($order->custom_address_to)) {
                $st = new Station(['name' => $order->custom_address_to, 'city_id' => $order->stationTo->city_id, 'latitude' => $order->latitude_to ?? null, 'longitude' => $order->longitude_to ?? null]);
                $st->id = $order->id + 3000;
                $order->station_to_id = $st->id;
                $order->stationTo = $st;
                $stations->push($st);
            }
            if ($tour->route->is_taxi && !empty($order->custom_address_from)) {
                $st = new Station(['name' => $order->custom_address_from, 'city_id' => $order->stationFrom->city_id, 'latitude' => $order->latitude, 'longitude' => $order->longitude]);
                $st->id = $order->id + 4000;
                $order->station_from_id = $st->id;
                $order->stationFrom = $st;
                $stations->prepend($st);
            }
        }

        $client_sum = $orders->sum('count_places');

        foreach ($tour->ordersConfirm as $order) {
            foreach ($order->orderPlaces as $key => $op) {
                if ($op->appearance == 1) {
                    if ($setting->is_pay_on && $order->type_pay != 'success') {
                        $sum += $op->price;
                    } elseif (!$setting->is_pay_on) {
                        $sum += $op->price;
                    }
                }
            }
            if ($order->orderPlaces->whereIn('appearance', 1)->count() > 0) {
                $sum += AddServicesPriceService::getPrice($order);
            }
        }

        return view('driver.list_of_passengers', compact(
            'env', 'd_a_setting', 'is_all', 'filterType', 'sum', 'places', 'statuses', 'orders', 'clients',
            'tour', 'stations', 'client_sum'));
    }

    public function completed()
    {
        $tour = Tour::find(request('tour_id'));
        $tour->type_driver = 'completed';
        $tour->status = 'completed';
        $tour->save();
    }

    public function switchAppearance()
    {
        $op = OrderPlace::find(request('orderId'));

        if ($op->order->is_finished != true) {
            if ($op->appearance === 1) {
                $op->appearance = 0;
                $op->save();

                $orderPlaces = $op->order->orderPlaces;

                if ($orderPlaces->whereIn('appearance', 1)->count() == 0) {
                    $op->order->appearance = 0;
                    $op->order->save();
                }

                return $op->appearance;
            } elseif ($op->appearance === 0) {
                $op->appearance = 1;
                $op->save();

                $op->order->appearance = 1;
                $op->order->save();

                return $op->appearance;
            } else {
                $op->appearance = 1;
                $op->save();

                $op->order->appearance = 1;
                $op->order->save();

                $this->setEkamCheck($op->order);

                return 2;
            }
        }
    }

    public function switchAppearanceAll()
    {
        $tour = Tour::find(request('tour_id'));

        if (request('is_all')) {
            foreach ($tour->orders as $order) {
                if ($order->is_finished != true) {
                    $order = $tour->ordersConfirm->where('id', $order->id)->first();
                    $order_places = $order->orderPlaces;

                    foreach ($order_places as $op) {
                        $op->appearance = 0;
                        $op->save();
                    }

                    $order->appearance = 0;
                    $order->is_pay = 0;
                    $order->save();
                }
            }
        } else {
            foreach ($tour->orders as $order) {
                if ($order->is_finished != true) {
                    $order = $tour->ordersConfirm->where('id', $order->id)->first();
                    $order_places = $order->orderPlaces;

                    foreach ($order_places as $op) {
                        $op->appearance = 1;
                        $op->save();
                    }

                    $order->appearance = 1;
                    $order->is_pay = 1;
                    $order->save();

                    $this->setEkamCheck($order);
                }
            }
        }
    }

    public function switchAppearanceOnStation()
    {
        $tour = Tour::find(request('tour_id'));
        $ordersId = request('orders_id');
        if ($is_transfer = $tour->route->is_transfer || $is_transfer = $tour->route->is_taxi)  {
            $station = new Station();
            $station->id = request('station_id');
        } else {
            $station = Station::find(request('station_id'));
        }

        $d_a_setting = DriverAppSetting::first();
        if (request('is_all') == 'true') {      // Кнопка Неявка для всех
            foreach ($ordersId as $orderId) {
                $order = $tour->ordersConfirm->where('id', $orderId)->first();
                $order_places = $order->orderPlaces;

                if ($order->station_from_id == $station->id || $is_transfer) {
                    $order->appearance = 0;
                    $order->is_pay = 0;
                    foreach ($order_places as $op) {    // Ставим неявку для всех мест у кого нету статуса Явка
                        if ($op->appearance != 1) {
                            $op->appearance = 0;
                            $op->save();
                        } else {        // Если хотя бы у одного места из заказа стоит Явка, то ставим флаг Явка (appearance) на весь заказ
                            $order->appearance = 1;
                            $order->is_pay = 1;
                        }
                    }

                    if ($d_a_setting->is_display_finished_button && $order->appearance === 0) {
                        $order->is_finished = true;
                        $order->type = 'completed';
                    }

                    $order->save();
                }
            }
        } else {
            foreach ($ordersId as $orderId) {
                $order = $tour->ordersConfirm->where('id', $orderId)->first();
                $order_places = $order->orderPlaces;

                if ($order->station_from_id == $station->id || $is_transfer) {
                    foreach ($order_places as $op) {
                        if ($op->appearance !== 0) {
                            $op->appearance = 1;
                            $op->save();
                        }
                    }

                    $order->appearance = 1;
                    $order->is_pay = 1;
                    $order->save();
                    $this->setEkamCheck($order);
                }
            }
        }
    }

    public function setPresence()
    {
        $order = Order::find(request('order_id'));
        foreach ($order->orderPlaces as $op) {
                $op->appearance = 1;
                $op->save();
        }
        $order->appearance = 1;
        $order->is_pay = 1;
        $order->save();
        $this->setEkamCheck($order);
    }

    public function cancelOrder()
    {
        $tour = Tour::find(request('tour_id'));

        $order = $tour->ordersConfirm->where('id', request('orderId'))->first();
        $order->status = "disable";
        $orderHistory = new OrderHistory();
        $orderHistory->order_id = $order->id;
        $orderHistory->action = OrderHistory::ACTIVE_CANCEL;
        $orderHistory->source = Order::SOURCE_DRIVER;
        $orderHistory->operator_id = 0;
        $orderHistory->save();
        $order->save();
    }

    public function switchPay()
    {
        $tour = Tour::find(request('tour_id'));

        $order = $tour->ordersConfirm->where('id', request('orderId'))->first();
        $order->is_pay = request('isChecked');
        $order->save();
    }

    public function switchCall()
    {
        $tour = Tour::find(request('tour_id'));

        $order = $tour->ordersConfirm->where('id', request('orderId'))->first();
        $order->is_call = request('isChecked');
        $order->save();
    }

    public function setFinished()
    {
        $d_a_setting = DriverAppSetting::first();
        $order = Order::find(request('order_id'));
        if ($d_a_setting->is_display_finished_button) {
            $order->is_finished = true;
            $order->type = 'completed';
        }

        $order->save();
    }

    public function unsetFinished()
    {
        $d_a_setting = DriverAppSetting::first();
        $order = Order::find(request('order_id'));
        if ($d_a_setting->is_display_finished_button) {
            $order->is_finished = false;
        }
        $order->save();
    }

    public function setFinishedAll()
    {
        $tour = Tour::find(request('tour_id'));
        $d_a_setting = DriverAppSetting::first();

        foreach (request('orders_id') as $orderId) {
            $order = $tour->ordersConfirm->where('id', $orderId)->first();
            if ($d_a_setting->is_display_finished_button) {
                $order->is_finished = true;
                $order->type = 'completed';
            }
            $order->save();
        }
    }

    public function unsetFinishedAll()
    {
        $tour = Tour::find(request('tour_id'));
        $d_a_setting = DriverAppSetting::first();

        foreach (request('orders_id') as $orderId) {
            $order = $tour->ordersConfirm->where('id', $orderId)->first();
            if ($d_a_setting->is_display_finished_button) {
                $order->is_finished = false;
            }
            $order->save();
        }
    }

    public function add(Tour $tour)
    {
        $env = getenv('PUSHER_APP_KEY');
        $clients_phone = [];

        $stations = $tour->route->stations;
        $orders = $tour->ordersConfirm;

        $client_sum = $orders->pluck('count_places')->toArray();
        $places = $tour->bus->places;

        $d_a_setting = DriverAppSetting::first();

        $statuses = Status::all();

        $required_inputs = isset($tour->route) ? explode(',', $tour->route->required_inputs) : [];

        foreach ($orders as $order) {
            array_push($clients_phone, $order->client->phone);
        }

        return view('driver.add_passengers', compact(
            'env',
            'statuses',
            'd_a_setting',
            'required_inputs',
            'places',
            'tour',
            'stations',
            'clients_phone'
        ))->with('client_sum', array_sum($client_sum));
    }

    public function hasStationFrom($order, $station)
    {
        return $order->station_from_id == $station->id && $order->appearance === null;
    }

    public function hasStationTo($order, $station)
    {
        return $order->appearance == 1 && $order->station_to_id == $station->id && $order->is_finished == 0;
    }

    public function getRouteWaypoints(Request $request)
    {
        $this->validate($request, [
            'tour_id' => 'exists:tours,id',
            'order_id' => 'exists:orders,id',
        ]);

        if (!empty(request('order_id')))    {
            $order = Order::query()->where('id', request('order_id'))->select('longitude', 'latitude')->get();
            return response()->json($order);
        }

        $tour = Tour::find(request('tour_id'));
        $route = $tour->route;
        
        foreach ($route->stations as $station) {
            if (!empty($station) && !empty($station->getClients($tour->orders))) {
                foreach ($tour->orders as $order) {
                    if ($this->hasStationFrom($order, $station)) {
                        return $this->waypoints($station->id, $route->id);
                    } elseif ($this->hasStationTo($order, $station)) {
                        return $this->waypoints($station->id, $route->id);
                    }
                }
            }
        }

    }

    public function generatePdf()
    {
        $order = Order::find(request()->get('order'));
        return ServicePdf::generatePdf($order);
    }

    public function fillOrder()
    {
        $order = Order::find(request('order_id'));

        foreach ($order->orderPlaces as $op) {
            if ($op->appearance === null) {
                $op->appearance = 0;
                $op->save();
            }
        }
    }

    public function waypoints($stationId, $routeId)
    {
        $waypoint = Station::query()
            ->where('id', $stationId)
            ->select('longitude', 'latitude')
            ->leftJoin('route_station', 'route_station.station_id', '=', 'stations.id')
            ->where('route_station.route_id', $routeId)
            ->get();

        if ($waypoint) {
            return response()->json($waypoint);
        }
    }

    function getId($id)
    {
        $driver = \Auth::guard('driver')->user();
        if (!empty($driver)) {
            $d_id = $driver->id;

            $ids = $d_id . "" . $id;
            return $ids;
        }
    }

    public function setEkamCheck(Order $order)
    {
        if (env('EKAM') == true) {
            try {
                $body = json_encode([
                    'order_id' => $order->id,
                    'order_number' => $order->slug,
                    'type' => 'SaleReceiptRequest',
                    'phone_number' => $order->client->phone,
                    'should_print' => true,
                    'cash_amount' => ($order->type_pay == 'cash-payment' || $order->type_pay == null) ? intval($order->price) : 0,
                    'electron_amount' => $order->type_pay == 'success' ? intval($order->price) : 0,
                    "lines" => [
                        [
                            'price' => $order->price / $order->orderPlaces->count(),
                            'quantity' => $order->orderPlaces->count(),
                            'title' => 'Организация перевозок пассажиров и багажа по заказу',
                            'total_price' => intval($order->price),
                            'vat_rate' => null,
                            'fiscal_product_type' => 4,
                            'payment_case' => 4,
                        ]
                    ]
                ]);

                $client = new HTTP([
                    'base_uri' => 'https://app.ekam.ru/api/online/v2/receipt_requests',
                    'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json', 'X-Access-Token' => env('EKAM_TOKEN')],
                    'body' => $body,
                ]);

                $response = $client->request('POST');
                return \Redirect::back();
            } catch (\Exception $e) {
                \Log::info($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
                return \Redirect::back();
            }
        }
    }

    public function getEkamCheck()
    {
        try {
            $order = Order::find(request()->get('order'));

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
}
