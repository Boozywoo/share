<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderRequest;
use App\Models\City;
use App\Models\Client;
use App\Models\Currency;
use App\Models\Order;
use App\Models\OrderPlace;
use App\Models\Setting;
use App\Models\Station;
use App\Models\Tour;
use App\Models\Route;
use App\Models\RouteStation;
use App\Notifications\Client\AdminPromotionNotification;
use App\Notifications\Order\ActiveOrderNotification;
use App\Notifications\Order\ChangeOrderNotification;
use App\Repositories\SelectRepository;
use App\Services\Integrations\AvtovokzalRu\AvtovokzalRuService;
use App\Services\Order\ChildPlaceService;
use App\Services\Order\Integrations\IntegrationBookingOrderService;
use App\Services\Order\Integrations\IntegrationConfirmOrderService;
use App\Services\Order\Integrations\IntegrationUpdateOrderService;
use App\Services\Order\PrintOrderService;
use App\Services\Order\StationIntervalsService;
use App\Services\Order\StoreOrderService;
use App\Services\Order\StoreRentOrderService;
use App\Services\Order\AddServicesPriceService;
use App\Services\Pays\ServicePayService;
use App\Services\Pdf\ServicePdf;
use App\Services\Sms\SMSClientStatic;
use App\Services\Support\HandlerError;
use App\Services\Prettifier;
use App\Services\Route\GetFromStation;
use App\Traits\ClearPhone;
use Carbon\Carbon;
use App\Services\Station\CreateNewStationService;
use Dompdf\Dompdf;
use App\Models\OrderHistory;
use PDF;
use App\Services\Order\OrderImportService;
use Vinkla\Pusher\Facades\Pusher;

use GuzzleHttp\Client as HTTP;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ClearPhone;

    protected $entity = 'orders';
    protected $select;

    public function __construct(SelectRepository $selectRepository)
    {
        $this->select = $selectRepository;
    }

    public function index()
    {
        $dataFilter = request()->except('routes');

        $dataFilter['which_date'] = request('which_date');

        $dataFilter['time_from'] = request('time_from');
        $dataFilter['time_to'] = request('time_to');

        if (\Auth::user()->isAgent || \Auth::user()->isMediator) {
            $dataFilter['created_user_id'] = \Auth::id();
        }
        if ($date = request('date')) {
            $dataFilter['date'] = Carbon::createFromFormat('d.m.Y', $date);
        } else {
            $dataFilter['between'] = ['dateFrom' => Carbon::now()->subDay(), 'dateTo' => Carbon::now()->addYear()];
        }

        if (request('id')) $dataFilter = ['id' => request('id')];
        if (request('id')) $dataFilter = ['slug' => request('id')];
        if ((isset($dataFilter['id']) && !empty($dataFilter['id']))
            || (isset($dataFilter['phone']) && !empty($dataFilter['phone'])))
            unset($dataFilter['date']);

        $settings = Setting::first();

        $orders = Order::filter($dataFilter + ['routes' => auth()->user()->routeIds])
            ->with('tour.route', 'client', 'stationFrom', 'coupon', 'smsLog', 'orderPlaces', 'history', 'stationTo')
            ->latest()
            ->paginate($settings->display_orders_quantity ?? 15);

        if (!request('_pjax') && request()->ajax()) {
            return $this->ajaxView($orders);
        }

        $routes = $this->select->routes(auth()->id(), true, true);
        $buses = $this->select->buses(auth()->user()->companyIds);

        $returnOrders = [];
        foreach ($orders as $item)   {
            if ($item->return_order_id) {
                $returnOrders[] = $item->return_order_id;
            }
        }

        return view('admin.orders.index',
            compact('orders', 'routes', 'buses', 'returnOrders') + ['entity' => $this->entity]);
    }

    protected function ajaxView($items, $type = 'orders', $order = null, $tour = null)
    {
        if ($type == 'tours') {
            $data = ['tours' => $items, 'order' => $order, 'tour' => $tour];
            $view = 'admin.' . $this->entity . '.edit.right.table';
        } else {
            $data = ['orders' => $items, 'order' => $order, 'tour' => $tour];
            $view = 'admin.' . $this->entity . '.index.table';
        }

        return response([
            'view' => view($view, $data + ['entity' => $this->entity])->render(),
            'pagination' => view('admin.partials.pagination', ['paginator' => $items])->render(),
        ])->header('Cache-Control', 'no-cache, no-store');
    }

    public function create(Order $order)
    {
        $routes = $this->select->routes(auth()->id(), true, true);
        $userRoutes =  \Auth::user()->routes->keyBy('id');

        $tour_id = request('tour_id');
        $tour = isset($tour_id) ? Tour::findOrFail($tour_id) : null;

        if ($tour && $tour->status == Tour::STATUS_VIRTUAL && $tour->schedule_id)  {       // На виртуальные рейсы не разрешаем создавать брони, а автоматом перекидываем на ближайший активный
            $redirect = $tour->nearestActive($tour->route->flight_type == 'departure' ? true : false);
            if (isset($redirect->id))   {
                return redirect()->route('admin.orders.create',['order' => null, 'tour_id' => $redirect->id, 'route' => $tour->route->id]);
            }
        }

        $dataFilter = request()->except('routes');

        $date = request('date') ? request('date') : date('d.m.Y');

        $incomming_phone = (isset(request()->incoming_phone)) ? request()->incoming_phone : NULL;

        $client = request('incoming_phone') ?
            Client::filter(['phone' => $incomming_phone])->first() : NULL;

        $settings = Setting::first();

        if (auth()->user()->timezone != '')
            $tz = auth()->user()->timezone;
        else
            $tz = $settings->default_timezone;

        $dataFilter['date'] = Carbon::createFromFormat('d.m.Y', $date, $tz);

        $dataFilter['status'] = Tour::STATUS_ACTIVE;
        $tours = Tour::filter($dataFilter + ['routes' => auth()->user()->routeIds])
            ->orderBy('time_start')
            ->with('route', 'driver', 'bus', 'orders', 'ordersReady')
            ->get();

        $routes = $this->select->routes(auth()->id(), false, true);
        $routeInterval = [];
        foreach ($routes as $key => $route) {
            if ($city_from_id = \request('city_from_id')) {
                $stationFrom = GetFromStation::index(Route::find($key), $city_from_id);
                $routeInterval[$key] = $stationFromInterval = $stationFrom ? $stationFrom->pivot->interval : 0;
            } else {
                $routeInterval[$key] = 0;
            }
        }
        foreach ($tours as &$tour) {
            $tour->time_start = $tour->time_start = Prettifier::prettifyDateTime($tour->prettyDateStart, $tour->time_start, $routeInterval[$tour->route_id]);
        }

        $tours = $tours->sortBy('time_start');
        $tours = new \Illuminate\Pagination\Paginator($tours, $tours->count());

        $tour = new Tour();
        if ($tourId = request('tour_id')) {
            $tour = Tour::find($tourId);
            if ($tour->route_id) {
                $this->authorize('route-id', $tour->route_id);
                //$stations = $this->select->stations($tour->route_id, null, true);
            }
        }
        $stations = [];

        if ($tour->is_collect)
            $stations = $this->select->cityWithStation($tour->route_id, null, null, request('city_from_id'));

        elseif ($tour->route_id)
            $stations = $this->select->cityWithStation($tour->route_id, NULL, 'active');

        $stationsTicketsFrom = [];
        $stationsTicketsTo = [];
        if ($tour->route && $tour->route->is_transfer) {
            foreach ($stations as $city_name => $city) {        // Оставляем только остановки отмеченные птичками "посадка на сайте" и "высадка на сайте" на странице редактирования направления
                foreach ($city as $station_id => $station) {
                    if (RouteStation::whereRouteId($tour->route_id)->whereStationId($station_id)->pluck('tickets_from')->first()) {
                        $stationsTicketsFrom[$city_name][$station_id] = $station;
                    }
                    if (RouteStation::whereRouteId($tour->route_id)->whereStationId($station_id)->pluck('tickets_to')->first()) {
                        $stationsTicketsTo[$city_name][$station_id] = $station;
                    }
                }
            };
        }

        $cities = $this->select->cities(true);
        $companies = $this->select->companies();

        $cityFromId = \request('city_from_id', empty($stations) ? null : Station::find(key(collect($stations)->first()))->city_id);
        $cityToId = \request('city_to_id', empty($stations) ? null : Station::find(key(collect($stations)->last()))->city_id);

        if (count($stations) == 1)   {
            end($stations);
            $stations[key($stations).' '] = $stations[key($stations)];
        }

        if (\request('order_return')) {
            $orderReturn = Order::findOrFail(\request('order_return'));
            $order->orderPlaces = $orderReturn->orderPlaces;
            $order->count_places = $orderReturn->count_places;
            $order->flight_number = $orderReturn->flight_number;
            $order->social_status_confirm = $orderReturn->social_status_confirm;
            $order->tour = $tour;
            $curPlaces = $tour->orderPlaces->pluck('number')->toArray();
            $placeNum = 1;
            foreach ($order->orderPlaces as $key => $place) {
                while (in_array($placeNum, $curPlaces))   {
                    $placeNum++;
                }
                $place->number = $placeNum;
                $place->id = '';
                $curPlaces[] = $placeNum;
            }
        }

        if (request()->ajax() && !request('_pjax')) return $this->ajaxView($tours, 'tours', $order, $tour);
        $required_inputs = isset($tour->route) ? explode(',', $tour->route->required_inputs) : [];
        $addServices = [];

        return view('admin.' . $this->entity . '.edit', compact('stations', 'tour', 'companies', 'cities',
                'order', 'routes', 'tours', 'stationsTicketsFrom', 'stationsTicketsTo', 'stations', 'client', 'cityFromId',
                'cityToId', 'required_inputs', 'incomming_phone', 'addServices', 'userRoutes') + ['entity' => $this->entity]);
    }

    public function edit(Order $order)
    {
        if (!$order->client) abort(404);
        $this->authorize('route-id', $order->tour->route_id);

        if ((\Auth::user()->isAgent || \Auth::user()->isMediator) && $order->created_user_id != \Auth::id()) return redirect()->route('admin.tours.index');

        $stations = [];
        if ($order->tour->route_id)
            $stations = $this->select->cityWithStation($order->tour->route_id, NULL, null);

        elseif ($order->tour->route_id)
            $stations = $this->select->cityWithStation($order->tour->route_id, NULL, 'active');
        if (count($stations) == 1)   {
            end($stations);
            $stations[key($stations).' '] = $stations[key($stations)];
        }

        $stationsTicketsFrom = [];
        $stationsTicketsTo = [];
        if ($order->tour->route->is_transfer) {
            foreach ($stations as $city_name => $city) {        // Оставляем только остановки отмеченные птичками "посадка на сайте" и "высадка на сайте" на странице редактирования направления
                foreach ($city as $station_id => $station) {
                    if (RouteStation::whereRouteId($order->tour->route_id)->whereStationId($station_id)->pluck('tickets_from')->first()) {
                        $stationsTicketsFrom[$city_name][$station_id] = $station;
                    }
                    if (RouteStation::whereRouteId($order->tour->route_id)->whereStationId($station_id)->pluck('tickets_to')->first()) {
                        $stationsTicketsTo[$city_name][$station_id] = $station;
                    }
                }
            };
        }

        $cityFromId = $order->stationFrom ? $order->stationFrom->city->id : null;
        $cityToId = $order->StationTo ? $order->StationTo->city->id : null;

        $companies = $this->select->companies();
        $addServices = $order->addServices->keyBy('id');
        $required_inputs = isset($order->tour->route) ? explode(',', $order->tour->route->required_inputs) : [];

        return view('admin.' . $this->entity . '.edit', compact('order', 'companies', 'stations', 'cityFromId', 'cityToId',
            'stationsTicketsFrom', 'stationsTicketsTo', 'required_inputs', 'addServices') + ['entity' => $this->entity]);
    }

    public function printOrder(Order $order)
    {
        $settings = Setting::first();
        $interval = StationIntervalsService::index($order->tour->route->id, $order->stationFrom->id, $order->stationTo->id);
        $timeStampToStation = \Carbon\Carbon::createFromTimeString($order->tour->date_start->format('Y-m-d') . ' ' . $order->tour->time_start)
            ->addMinutes($interval[1] - $interval[0])
            ->timestamp;

        return view('admin.' . $this->entity . '.print', compact('order', 'settings', 'timeStampToStation'));
    }

    public function generatePdf(Order $order)
    {
        return ServicePdf::generatePdf($order);
    }

    public function generatePdfOP(OrderPlace $order)
    {
        return ServicePdf::generatePdfOP($order);
    }

    public function pdf(Order $order)
    {
        if (env('MINSK_TRANS') && $order->tour->integration_id == 1) {
            $clientAvtovokzal = new AvtovokzalRuService();
            $orderIntegration = $clientAvtovokzal->get_order($order->uid);
            $url = $clientAvtovokzal->getUrlTicket($orderIntegration->tickets[0]);
            return redirect()->to($url);
        } else {
            return PrintOrderService::index($order);
        }

        $interval = StationIntervalsService::index($order->tour->route->id, $order->stationFrom->id, $order->stationTo->id);
        $timeStampToStation = \Carbon\Carbon::createFromTimeString($order->tour->date_start->format('Y-m-d') . ' ' . $order->tour->time_start)
            ->addMinutes($interval[1] - $interval[0])
            ->timestamp;
        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('admin.' . $this->entity . '.pdf', compact('order', 'settings', 'timeStampToStation'))->render());
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream();
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
                return $this->responseSuccess(['link' => $responseJSON->items[0]->receipt_url]);
            } else {
                return $this->responseError(['message' => 'У этой брони нет чека!']);
            }
        } catch (\Exception $e) {
            \Log::info($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            return $this->responseError(['message' => 'У этой брони нет чека!']);
        }
    }

    public function pay(Order $order)
    {
        $parentOrder = Order::where('return_order_id', $order->id)->first(); // Заказ сам является обратным билетом к другому
        if ($parentOrder) {
            abort(500);
        }
        $service = new ServicePayService();
        return $service->index($order, route('admin.tours.show', $order->tour->id));
    }

    public function getClientInfo()
    {
        $type = 'success';

        $tour = null;
        if (request('tour_id')) {
            $tour = Tour::where('id', request('tour_id'))->first();
        }

        $clientPhone = request('phone');
        $client = Client::filter(['phone' => $clientPhone])->first();
        $message = trans('messages.admin.order.client_loaded');
        $companies = $this->select->companies();
        if ($client && $tour && $tour->route && $tour->route->is_taxi) {
            $taxiHistory = Order::where('client_id', $client->id)
            ->with('stationTo', 'stationFrom')
            ->where(function ($query) {
                $query->whereHas('stationTo', function ($q1) {
                    $q1->where('status', Station::STATUS_TAXI);
                })
                ->orWhereHas('stationFrom', function ($q2) {
                    $q2->where('status', Station::STATUS_TAXI);
                });
            })
                ->get();
        } else {
            $taxiHistory = [];
        }

        if ($client && $tour && $tour->route && $tour->route->is_transfer && $client->order) {
            $lastOrder = $client->order;
            if ($lastOrder->tour->route->flight_type == 'departure')    {
                $lastAddress = ['street' => $lastOrder->address_from_street, 'house' => $lastOrder->address_from_house,
                    'building' => $lastOrder->address_from_building, 'apart' => $lastOrder->address_from_apart];
            };
            if ($lastOrder->tour->route->flight_type == 'arrival')    {
                $lastAddress = ['street' => $lastOrder->address_to_street, 'house' => $lastOrder->address_to_house,
                    'building' => $lastOrder->address_to_building, 'apart' => $lastOrder->address_to_apart];
            }
        }

        if (!$client) {
            $message = trans('messages.admin.order.client_created');
            $client = null;
        } elseif ($client->status == Client::STATUS_DISABLE) {
            $type = 'error';
            $message = trans('messages.admin.order.client_blacklisted');
        }

        return $this->responseSuccess([
            'viewClientInfo' => view('admin.orders.edit.left.user-info', compact('client', 'tour', 'companies', 'taxiHistory'))->render(),
            'message' => $message,
            'type' => $type,
            'status_id' => $client ? $client->status_id : null,
            'date_social' => $client && $client->date_social ? $client->date_social->format('d.m.Y') : null,
            'last_address' => $lastAddress ?? '',
        ]);

    }

    public function toTour(Tour $tour, Order $order)
    {
        $this->authorize('route-id', $tour->route_id);
        $cities = $this->select->cities();
        $client = null;
        if (request('tour_id')) $tour = Tour::where('id', request('tour_id'))->first();

        if (request('phone')) {
            $clientPhone = request('phone');
            $client = Client::filter(['phone' => $clientPhone])->first();
        }
        $clientPhone = request('phone');
        if (trim($clientPhone) != '')
            $client = Client::filter(['phone' => $clientPhone])->first();

        $cityFromId = \request()->get('city_from_id');
        $cityToId = \request()->get('city_to_id');

        if ($tour->is_collect)
            $stations = $this->select->cityWithStation($tour->route_id, null, null);

        elseif ($tour->route_id)
            $stations = $this->select->cityWithStation($tour->route_id, NULL, 'active');

        if (request('selection_places')) $order->places_with_number = 1;
        $changeTour = true;
        $required_inputs = isset($tour->route) ? explode(',', $tour->route->required_inputs) : [];
        $stationsTicketsFrom = [];
        $stationsTicketsTo = [];
        if ($tour->route && $tour->route->is_transfer) {
            foreach ($stations as $city_name => $city) {        // Оставляем только остановки отмеченные птичками "посадка на сайте" и "высадка на сайте" на странице редактирования направления
                foreach ($city as $station_id => $station) {
                    if (RouteStation::whereRouteId($tour->route_id)->whereStationId($station_id)->pluck('tickets_from')->first()) {
                        $stationsTicketsFrom[$city_name][$station_id] = $station;
                    }
                    if (RouteStation::whereRouteId($tour->route_id)->whereStationId($station_id)->pluck('tickets_to')->first()) {
                        $stationsTicketsTo[$city_name][$station_id] = $station;
                    }
                }
            };
        }

        $response = [
            'html' => view('admin.orders.edit.right.tour', compact('tour', 'order', 'cityFromId', 'cityToId', 'changeTour', 'required_inputs'))->render(),
            'tour_info' => view('admin.orders.edit.left.tour-info', compact('tour', 'cities', 'stations', 'order', 'stationsTicketsFrom', 'stationsTicketsTo'))->render(),
            'viewClientInfo' => view('admin.orders.edit.left.user-info', compact('client', 'tour'))->render(),
            'tour_id' => $tour->id,
            'flight_number' => in_array('flight_number', $required_inputs) ?
                view('admin.partials.form.panelText',
                    ['name' => 'flight_number', 'val' => '', 'class' => 'form-control', 'arr' => [], 'col' => false])->render() : ''
        ];

        if (\request('with_phone')) {
            $response['clientPhone'] = $tour->route->phone_code;
        }

        return $this->responseSuccess($response);
    }

    public function toTours(Order $order)
    {
        $routes = $this->select->routes(auth()->id());
        $cities = $this->select->cities();
        $dataFilter = request()->except('routes');
        $date = request('date') ? request('date') : date('d.m.Y');
        $dataFilter['date'] = Carbon::createFromFormat('d.m.Y', $date);
        $dataFilter['status'] = Tour::STATUS_ACTIVE;
        $tours = Tour::filter($dataFilter + ['routes' => auth()->user()->routeIds])
            ->orderBy('time_start')
            ->with('route', 'driver', 'bus', 'orders', 'ordersReady')
            ->paginate(100);
        $routes = $this->select->routes(auth()->id(), false, true);
        $routeInterval = [];
        foreach ($routes as $key => $route) {
            if ($city_from_id = \request('city_from_id')) {
                $stationFrom = GetFromStation::index(Route::find($key), $city_from_id);
                $routeInterval[$key] = $stationFromInterval = $stationFrom ? $stationFrom->pivot->interval : 0;
            } else {
                $routeInterval[$key] = 0;
            }
        }
        foreach ($tours as $tour) {
            $tour->time_start = $tour->time_start = Prettifier::prettifyDateTime($tour->prettyDateStart, $tour->time_start, $routeInterval[$tour->route_id]);
        }

        return $this->responseSuccess([
            'filter' => view('admin.orders.edit.left.filter', compact('routes', 'order', 'cities'))->render(),
            'html' => view('admin.orders.edit.right.tours', compact('tours', 'order'))->render(),
        ]);
    }

    public function store(OrderRequest $request)
    {
        try {
            $tour = Tour::find(request('tour_id'));
            $this->authorize('route-id', $tour->route_id);
            $countChildren = 0;

            $data = request()->all();
            if (\request('order_return') > 0) {
                $data['is_return_ticket'] = true;
            }

            if ($request->get('status') === Order::STATUS_RESERVE) {
                $data['pull'] = 1;
            }

            if (!$request->has('custom_address_from'))  {
                $data['custom_address_from'] = null;
            };
            if (!$request->has('custom_address_to'))  {
                $data['custom_address_to'] = null;
            };

            if ($data['is_new_stations'] ?? false == true) {
                $city_id = City::where('name', $data['city_from'])->first()->id;
                $data['station_from_id'] = CreateNewStationService::index(request('station_from_id'), $city_id, $tour->route->id);

                $city_id = City::where('name', $data['city_to'])->first()->id;
                $data['station_to_id'] = CreateNewStationService::index(request('station_to_id'), $city_id, $tour->route->id);
            }

            $id = array_get($data, 'id');
            $order = null;
            if ($id) {
                $order = Order::find(request('id'));
                $countChildren = $order->orderPlaces->where('is_child', 1)->count();
                $orderLast = $order;
                if (isset($request->places) && count($request->places) != $order->orderPlaces->count() && $order->type == Order::TYPE_WAITING) {
                    Pusher::trigger('driver-channel' . $order->tour->driver_id . $order->tour_id, 'my-event', [
                        'app_url' => env('APP_URL'),
                        'message' => 'Обновились места в брони!',
                    ]);
                }
            }

            //МОСГОРТРАНС
            if (env('MINSK_TRANS')) {
                if ($id && $order && $order->tour->integration_uid) {
                    IntegrationUpdateOrderService::index($order->uid, $data);
                } elseif ($tour->integration_uid) {
                    $data['uid'] = IntegrationBookingOrderService::index($tour, $data);
                    if ($data['confirm'] == 1) {
                        IntegrationConfirmOrderService::index($data['uid']);
                    }
                }
            }

            $data['slug'] = $request->client_id;
            $required_inputs = isset($tour->route) ? explode(',', $tour->route->required_inputs) : [];

            if ($tour->rent) {
                $order = StoreRentOrderService::index($tour, $data);
            } else {
                list($order, $error) = StoreOrderService::index($data, $tour);
                if ($error) {
                    $data['message'] = $error;
                    return $this->responseError($data);
                }

                $freeFragmentPlaces = config('app.FRAGMENTATION_RESERVED') ? $tour->freePlacesBetween($data['station_from_id'], $data['station_to_id']) : 0;

                if ($countChildren) {
                    list ($order, $childError) = ChildPlaceService::index($order, $countChildren);
                    $this->OrderSaveHistory($order, 'update');
                    $order->update();
                }

                if (isset($orderLast)) {
                    $this->SaveDataOrderPlacesData($orderLast, $order);
                }

                $order = AddServicesPriceService::index($order);
                $order->save();

                $tour = $tour->fresh();
                $cityFromId = Station::find($data['station_from_id'])->city->id;
                $cityToId = Station::find($data['station_to_id'])->city->id;

                if (\request('order_return')) {
                    $orderReturn = Order::findOrFail(\request('order_return'));
                    $orderReturn->update(['return_order_id' => $order->id]);
                    $places = $orderReturn->orderPlaces->toArray();
                    $allInputs = array_merge($required_inputs, ['name', 'surname', 'patronymic', 'is_child']);
                    foreach ($order->orderPlaces as $key => $place) {
                        foreach ($allInputs as $input) {
                            $place->$input = $places[$key][$input] ?? null;
                        }
                    }
                }
            }

            $data = [
                'id' => $order->id,
                'slug' => $order->slug,
                'freeFragmentPlaces' => $freeFragmentPlaces,
                'fp' => $tour->freePlacesCount,
                'view_tour' => view('admin.orders.edit.right.tour', compact('tour', 'order', 'cityFromId', 'cityToId', 'freeFragmentPlaces', 'required_inputs'))->render(),
            ];

            if ($order->type == Order::TYPE_WAITING && request('new_order') && $order->status != Order::STATUS_RESERVE) {
                if($request->get('is_send_sms') == 'on') {
                    $order->client->notify(new ActiveOrderNotification($order, $request->get('is_send_sms')));
                    $order->client->notify(new AdminPromotionNotification($order));
                }
            }

            if ($order->type == Order::TYPE_WAITING && !request('new_order') && $order->status != Order::STATUS_RESERVE)
                $order->client->notify(new ChangeOrderNotification($order, $request->get('is_send_sms')));
            /*if ($order->type == Order::TYPE_WAITING && !$order->places_with_number) {
            Это условие исключало вариант когда шел выбор места, и из-за этого строка последнего заказа не подсвечивалась
            */
            if ($order->type == Order::TYPE_WAITING) {
                $typeTour = $order->tour->rent ? 'rents' : 'tours';
                $data['redirect_url'] = route('admin.' . $typeTour . '.index', ['date' => $tour->date_start->format('d.m.Y'), 'route' => $tour->route_id, 'tour_id' => $tour->id]);
            }
            return $this->responseSuccess($data);
        } catch (\Exception $e) {
            \Log::info($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            HandlerError::index($e);
        }
    }

    protected function SaveDataOrderPlacesData($orderLast, $orderNew)
    {
        foreach ($orderLast->orderPlaces as $key => $orderPlace) {
            if (isset($orderNew->orderPlaces[$key])) {
                $orderNew->orderPlaces[$key]->passport = $orderPlace->passport;
                $orderNew->orderPlaces[$key]->surname = $orderPlace->surname;
                $orderNew->orderPlaces[$key]->name = $orderPlace->name;
                $orderNew->orderPlaces[$key]->patronymic = $orderPlace->patronymic;
                $birthDay = !empty($orderPlace->birth_day) ? $orderPlace->birth_day->format('Y-m-d') : null;
                $orderNew->orderPlaces[$key]->birth_day = $birthDay;
                $orderNew->orderPlaces[$key]->is_handler_price = $orderPlace->is_handler_price;
                $orderNew->orderPlaces[$key]->phone = $orderPlace->phone;
                $orderNew->orderPlaces[$key]->email = $orderPlace->email;
                $orderNew->orderPlaces[$key]->doc_type = $orderPlace->doc_type;
                $orderNew->orderPlaces[$key]->doc_number = $orderPlace->doc_number;
                $orderNew->orderPlaces[$key]->gender = $orderPlace->gender;
                $orderNew->orderPlaces[$key]->country_id = $orderPlace->country_id;
                $orderNew->orderPlaces[$key]->is_child = $orderPlace->is_child;
                if (!empty($orderNew->orderPlaces[$key]->is_handler_price)) {
                    $orderNew->orderPlaces[$key]->price = $orderPlace->price;
                }
                $orderNew->orderPlaces[$key]->save();
            }
        }

        $orderNew->price = $orderNew->orderPlaces->sum('price');

        $orderNew->save();

        //$this->OrderSaveHistory($orderNew, OrderHistory::ACTIVE_CREATE);
    }

    protected function SaveDataOrder()
    {
        $request = request()->get('order');
        $order = Order::find(array_keys($request)[0]);

        foreach($request[array_keys($request)[0]] as $key => $req) {
            $order->client->$key = $req;
            $order->client->save();
        }

        //$this->OrderSaveHistory($orderNew, OrderHistory::ACTIVE_CREATE);
    }

    public function children()
    {
        $curr_back = '';
        $order = Order::find(request('order_id'));

        list ($order, $error) = ChildPlaceService::index($order, request('count'));
        if (isset($order->tour->route->currency_id))
            $curr_back = Currency::where('id', $order->tour->route->currency_id)->first();
        $this->OrderSaveHistory($order, OrderHistory::ACTIVE_UPDATE);
        $order->update();
        $required_inputs = isset($order->tour->route) ? explode(',', $order->tour->route->required_inputs) : [];
        $setting = Setting::first();

        $data = [
            'message' => trans('messages.admin.order.for_children'),
            'view' => view('admin.orders.edit.right.prices', compact('order', 'curr_back', 'required_inputs', 'setting'))->render(),
        ];

        return $this->responseSuccess($data);
    }

    public function isCall()
    {
        $order = Order::find(request('id'));
        $this->OrderSaveHistory($order, OrderHistory::ACTIVE_UPDATE);
        $order->update(['is_call' => request('is_call')]);
        $message = (request('is_call') == 1) ? 'Клиент уведомлён!' : 'Клиент надо уведомить!';
        $data = [
            'message' => $message,
            'result' => 'success',
        ];
        return $this->responseSuccess($data);
    }

    public function delete()
    {
        //if (!count($order->old_places)) {
        if ($order = Order::find(request('id'))) {
            $this->OrderSaveHistory($order, OrderHistory::ACTIVE_DELETE);
            $order->delete();
            return $this->responseSuccess(['message' => trans('messages.admin.order.order_deleted')]);
        }
        return $this->responseError();
    }

    public function cancel(Order $order)
    {
        if (env('MINSK_TRANS') && $order->tour->integration_id == 1) {
            $clientAvtovokzal = new AvtovokzalRuService();
            $clientAvtovokzal->cancel_order($order->uid);
        }
        $this->OrderSaveHistory($order, OrderHistory::ACTIVE_CANCEL);


        $order->update(['status' => Order::STATUS_DISABLE, 'canceled_user_id' => \Auth::id(), 'pull' => 0]);

        return $this->responseSuccess();
    }

    public function restore(Order $order)
    {
        if (!$order->tour->freePlacesCount) {
            return $this->responseError(['message' => 'Нет свободных мест на рейсе!']);
        }
        if ($order->tour->reservation_by_place) {
            foreach ($order->orderPlaces as $place) {
                if ($place->number && $order->tour->reserved->contains('number', $place->number)) {
                    return $this->responseError(['message' => 'Место '.$place->number.' уже занято!']);
                }
            }
        }

        $this->OrderSaveHistory($order, OrderHistory::ACTIVE_RECOVER);

        $order->update(['status' => Order::STATUS_ACTIVE, 'modified_user_id' => \Auth::id(), 'pull' => 0]);

        return $this->responseSuccess();
    }

    public function delete_order(Order $order)
    {
        if (env('MINSK_TRANS') && $order->tour->integration_id == 1) {
            $clientAvtovokzal = new AvtovokzalRuService();
            $clientAvtovokzal->cancel_order($order->uid);
        }
        $this->OrderSaveHistory($order, OrderHistory::ACTIVE_DELETE);
        $order->delete();
        return $this->responseSuccess();
    }

    public function StationToId()
    {
        $stations = $this->select->cityWithStation(request('route_id'), request('station_from_id'));
        return view('admin.orders.edit.left.select_station_to_id',
            ['stations' => $stations, 'stationToId' => request('station_to_id')]);
    }

    public function ChangeFromTime()
    {
        $order = Order::find(request('id'));
        $order->station_from_time = request('station_from_time');
        $order->from_date_time = $order->from_date_time->setTimeFromTimeString(request('station_from_time'));
        $this->OrderSaveHistory($order, OrderHistory::ACTIVE_UPDATE, 'Изменено время отправления на '.request('station_from_time'));
        $order->save();
        return $this->responseSuccess(['message' => 'Время успешно изменено']);
    }

    private function OrderSaveHistory($order, $action, $comment = null)
    {
        $orderHistory = new OrderHistory();
        $orderHistory->order_id = $order->id;
        $orderHistory->action = $action;
        $orderHistory->source = Order::SOURCE_OPERATOR;
        $orderHistory->operator_id = \Auth::id();
        if ($comment) {
            $orderHistory->comment = $comment;
        }
        $orderHistory->save();
    }

    public function ChangePrice()
    {
        $message = '';
        $order = Order::where('id', request('id'))->first();
        $price = request('price');

        if ($price != $order->price) {
            $dif = $price - $order->price;
            $count = $order->orderPlaces->count();
            $part = $dif / $count;

            foreach ($order->orderPlaces as $orderPlace) {
                if ($count == 1) $orderPlace->price = $price;
                else $orderPlace->price += $part;
                $orderPlace->save();
            }

            $this->OrderSaveHistory($order, OrderHistory::ACTIVE_UPDATE, 'Изменена цена на '.$price);

            $order->update(['price' => $price]);
            $message = 'Цена успешна изменена';
        }
        return $this->responseSuccess(['message' => $message]);
    }

    public function SaveDataOrderPlaces(Request $request)
    {
        $order_places = $request->order_places;
        foreach ($order_places as $orderPlaceId => $order_place) {
            if ($place = OrderPlace::find($orderPlaceId)) {
                foreach ($order_place as $key => $item) {
                    if ($key == 'birth_day') {
                        $item = empty($item) ? null : new Carbon($item);
                    }

                    if ($key == 'price' && !empty($order_place['is_handler_price'])) {
                        $place->$key = $item;
                    }

                    $place->$key = (!isset($item) || '') ? null : $item;
                    $place->save();
                }
            }
        }

    }

    public function export()
    {
        $exportData = [];
        $dateStart = \request()->get('date', date('d.m.Y'));
        $dateStart = date('Y-m-d', strtotime($dateStart));
        if (\request('type') == 'created_date') {
            $orders = Order::with('client', 'stationFrom', 'stationTo', 'orderPlaces', 'tour', 'operator')
                ->whereDate('created_at', Carbon::createFromTimestamp(strtotime($dateStart)))
                ->whereStatus(Order::STATUS_ACTIVE)
                ->whereType(Order::TYPE_PAY_WAIT);
        } else {
            $orders = Order::with('client', 'stationFrom', 'stationTo', 'orderPlaces', 'tour', 'operator')
                ->whereHas('tour', function ($query) use ($dateStart) {
                    $query->where('date_start', $dateStart);
                })
                ->whereStatus(Order::STATUS_ACTIVE)
                ->whereType(Order::TYPE_PAY_WAIT);
        }
        if (\request('payed') == '1') {     // Только оплаченные онлайн брони
            $orders = $orders->where('type_pay', Order::TYPE_PAY_SUCCESS);
        }
        $orders = $orders->get();

        $payTypes = trans('admin.orders.pay_types');
        $invalidCharacters = array('*', ':', '/', '\\', '?', '[', ']', '!', '&', '$', '@', '#', '%', '(', ')', '+', '~', ';',',','.', '=');

        foreach ($orders as $order) {
            $exportData[] = [
                'id' => $order->id,
                'Дата' => empty($order->tour->date_start) ? '' : $order->tour->date_start->format('Y-m-d'),
                'Время' => $order->tour ? $order->tour->time_start : '',
                'ФИО' => $order->client ? str_replace($invalidCharacters, '', $order->client->FullName) : '',
                'Телефон' => $order->client ? $order->client->phone : '',
                'Паспорт' => $order->client ? $order->client->passport : '',
                'Дата рождения' => $order->client && $order->client->birth_day ? $order->client->birth_day->format('d.m.Y') : '',
                'Гражданство' => $order->client->country_id ?  trans('admin_labels.countries.' . ($order->client->country_id)) : '',
                'Откуда' => $order->stationFrom->city->name,
                'Куда' => $order->stationTo->city->name,
                'Стоимость' => $order->price,
                'Оплата' => $order->type_pay ? $payTypes[$order->type_pay] : '',
                'Кол-во мест' => $order->orderPlaces->count(),
                'Оператор' => $order->operator ? $order->operator->name : 'онлайн-бронирование',
                'Направление' => $order->tour->route->name,
            ];
        }

        if (!empty($exportData)) {
            $exportData = collect($exportData)->sortBy('Время')->groupBy('Направление')->toArray();
            \Excel::create('Статистика за ' . $dateStart, function ($excel) use ($exportData) {
                foreach ($exportData as $key => $items) {
                    if (mb_strlen($key) > 30) $key = substr($key, 0, 30);
                    $excel->sheet($key, function ($sheet) use ($items) {
                        $sheet->fromArray($items);
                    });
                }
            })->export('xlsx');
        } else return redirect()->route('admin.orders.index');
    }


    public function checkStations()
    {
        $orderId = request('order_id');
        $tour = Tour::find(request('tour_id'));
        $stationFromId = request('station_from_id');
        $stationToId = request('station_to_id');

        if (request('status') !== Order::STATUS_RESERVE) {
            if ($orderId) {
                \DB::table('orders')->where('id', $orderId)->update(['status' => Order::STATUS_DISABLE]);
            }

            if (request('count_places') > $tour->ordersFreeStations($stationFromId, $stationToId)) {
                $message = 'Данная остановка недоступна для ';
                $message .= request('destination') === 'from' ? 'посадки' : 'высадки';
                return $this->responseError(['message' => $message]);
            }
        }

        $order = null;
        if ($orderId) {
            $order = Order::find($orderId);
            if ($order) {
                $order->status = Order::STATUS_ACTIVE;
                $order->save();
            }
        }

        $cityFromId = Station::find($stationFromId)->city->id;
        $cityToId = Station::find($stationToId)->city->id;

        return $this->responseSuccess([
            'message' => trans('messages.admin.order.stop_updated'),
            'bus_tour' => view('admin.orders.edit.right.bus', compact('tour', 'order', 'cityFromId', 'cityToId'))->render(),
        ]);
    }

    public function reportPopup()
    {
        return ['html' => view('admin.orders.popups.report', ['entity' => $this->entity])->render()];
    }

    public function exportReport()
    {
        if ($dateStart = \request()->get('date_from')) {
            $dateStart = Carbon::parse(\request('date_from'));
        } else {
            $dateStart = Carbon::now()->addMonth(-1);
        }

        if ($dateFinish = \request()->get('date_to')) {
            $dateFinish = Carbon::parse(\request('date_to'));
        } else {
            $dateFinish = Carbon::now();
        }

        $typePay = \request()->get('type_pay');

        $between = [$dateStart->startOfDay()->format('Y-m-d'), $dateFinish->endOfDay()->format('Y-m-d')];
        $orders = Order::whereStatus(Order::STATUS_ACTIVE)->whereHas('tour', static function ($query) use ($between, $typePay) {
            $query->whereBetween('date_start', $between);
        });

        if($typePay) {
            $orders = $orders->where('type_pay', $typePay)->get();
        } else {
            $orders = $orders->get();
        }
        \Excel::create('Отчет с ' . $dateStart->format('d.m.Y') . ' по ' . $dateFinish->format('d.m.Y'), function ($excel) use ($orders) {
            if ($orders->count()) {

                $orders = $orders->transform(function ($order, $key) {
                    $invalidCharacters = array('*', ':', '/', '\\', '?', '[', ']', '!', '&', '$', '@', '#', '%', '(', ')', '+', '~', ';',',','.', '=');
                    $payTypes = trans('admin.orders.pay_types');

                    return [
                        $order->slug,
                        empty($order->tour->date_start) ? '' : $order->tour->date_start->format('d.m.Y'),
                        $order->date_of_payment ? $order->date_of_payment : $order->created_at->format('d.m.Y'),
                        str_replace($invalidCharacters, '', $order->tour->route->name),
                        $order->client ? str_replace($invalidCharacters, '', $order->client->FullName) : '',
                        $order->client ? str_replace($invalidCharacters, '', $order->client->phone) : '',
                        str_replace($invalidCharacters, '', $order->stationFrom->city->name),
                        str_replace($invalidCharacters, '', $order->stationTo->city->name),
                        $order->price,
                        $order->orderPlaces->count(),
                        $order->operator ? str_replace($invalidCharacters, '', $order->operator->first_name) : "Онлайн-бронирование",
                        $order->type_pay ? $payTypes[$order->type_pay] : '',
                        $order->created_at->format('d.m.Y') ?? '',
                        $order->created_at->format('H:i:s') ?? '',
                    ];

                });

                $sheetName = 'Отчет';
                $excel->sheet(mb_substr($sheetName, 0, 31), function ($sheet) use ($orders) {
                    $orders = $orders->toArray();
                    array_unshift($orders, ['#', 'Дата отправления', 'Дата оплаты', 'Направление', 'ФИО',
                        'Телефон', 'Откуда', 'Куда', 'Стоимость', 'Кол-во мест', 'Оператор', 'Тип оплаты', 'Дата бронирования', 'Время бронирования']);
                    $sheet->fromArray($orders);
                });
            }
        })->export('xls');
    }


}
