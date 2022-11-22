<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Requests\Api\Client\CityToRequest;
use App\Http\Requests\Api\Client\GetStationTourRequest;
use App\Http\Requests\Api\Client\RoutesRequest;
use App\Models\City;
use App\Models\Order;
use App\Models\Route;
use App\Models\Setting;
use App\Models\Station;
use App\Models\Token;
use App\Models\Tour;
use App\Models\RouteStation;
use App\Models\Config;
use App\Repositories\SelectApiRepository;
use App\Repositories\SelectRepositoryIndex;
use App\Services\Order\StationCostIntervalService;
use App\Services\Order\StoreOrderService;
use App\Services\Order\StationIntervalsService;
use App\Services\Sale\SaleToOrderService;
use App\Services\Prettifier;
use App\Services\Route\GetFromStation;
use App\Services\Route\GetToStation;
use App\Services\Support\CheckTimestamp;
use App\Services\Pays\ServicePayService;
use App\Services\Support\HandlerError;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;

class RouteController extends Controller
{
    public function cityFrom()
    {
        $selectIndex = new SelectRepositoryIndex();
        $routes = $selectIndex->routes();
        $cityIds = array();

        foreach ($routes as $route) {
            $cityIds += $route->stationsActive->pluck('city_id', 'city_id')->toArray();
        }

        return City::whereIn('id', $cityIds)->get()->pluck('name', 'id');
    }

    public function cityTo(CityToRequest $request)
    {
        $cityFromId = $request->from;
        $tour = new Tour();
        $cities = $tour->getFromCityIdTo($cityFromId, '', 'cities');
        if (is_array($cities)) {
            $dataCities = City::whereIn('id', $cities)->get();
            foreach ($cities as $key => $city) {
                $name = $dataCities->where('id', $city)->first()->name;
                $cities[$key] = $name;
            }
            return $cities;
        }
    }


    public function tours(RoutesRequest $request)
    {
        $city_to_id = request('to');
        $city_from_id = request('from');

        if ($city_from_id && $city_to_id) {
            $this->selectIndex = new SelectRepositoryIndex();
            $settings = Setting::first();

            $dataFilter = request()->except('routes', 'date');
            $dataFilter['city_to_id'] = $city_to_id;
            $dataFilter['city_from_id'] = $city_from_id;
            $date = Carbon::createFromTimestamp($request->date);
            $dataFilter['between'] = ['dateFrom' => $date->copy()->subDays(1)->format('Y-m-d'), 'dateTo' => $date->format('Y-m-d')];
            $tours = Tour::filter($dataFilter)
              ->orderBy('date_time_start')
              ->with('route', 'rent', 'driver', 'bus', 'ordersReady', 'ordersPull', 'ordersPullReserve', 'schedule', 'orderPlaces')
              ->where('is_show_front', true)
              ->where('status', Tour::STATUS_ACTIVE)
              ->withCount('ordersReady')
              ->get();

            $routes = $this->selectIndex->routes();

            $routeInterval = [];
            $stationFrom = [];
            $stationTo = [];
            $routeStations = [];

            foreach ($routes as $key => $route) {
                if ($city_from_id) {
                    $stationFrom = GetFromStation::index(Route::find($route->id), $city_from_id);
                    $routeInterval[$route->id] = $stationFromInterval = $stationFrom ? $stationFrom->pivot->interval : 0;
                    $stationFrom[$route->id] = GetFromStation::index($route, $city_from_id);
                    $stationTo[$route->id] = GetToStation::index($route, $city_to_id);

                    $routeStations[$route->id] = ['stationFromId' => $stationFrom[$route->id]['id'], 'stationToId' => $stationTo[$route->id]['id']];
                } else {
                    $routeInterval[$route->id] = 0;
                    $stationFrom[$route->id] = 0;
                    $stationTo[$route->id] = 0;
                }
            }

            $dataTours = [];

            $dateFrom = Carbon::createFromTimestamp(request('date'));
            $dateFrom->setTime(0, 0, 0);
            $dateNow = Carbon::now()->addMinutes($settings->time_hidden_tour_front);        //Добавляем время, за которое отключать бронирование на сайте (мин)

            if ($dateNow->gt($dateFrom))    {   // Интервал в котором искать рейсы должен начинаться 0-00 выбранной даты только если (текущее время + time_hidden_tour_front) не больше 0-00 выбранной даты
                $dateFrom = $dateNow; 
            }

            if ($tours->count()) {
                foreach ($tours as &$tour) {
                    $stations = $routeStations[$tour->route_id];
                    if ($tour->freePlacesCount < $request->count_places) {
                        unset($tours[$key]); // убирать где нет свободных мест
                        continue;
                    }
                    $cityFromStations = City::find($city_from_id)->stations()->pluck('id');
                    if ($tour->route->stations->where('id',$stations['stationFromId'])->first()->pivot->tickets_from == false) {
                        $boardCount = RouteStation::whereRouteId($tour->route_id)
                            ->whereIn('station_id', $cityFromStations)
                            ->where('tickets_from', true)->count();    // Кол-во остановок в городе посадки, где разрешена посадка
                        if ($boardCount == 0) {
                            unset($tours[$key]); // убирать рейс, где НЕ стоит ни одного флага tickets_from (отображать на этом машруте остановку клиентам и продавать С нее билеты)
                            continue;
                        }
                    }
                    $cityToStations = City::find($city_to_id)->stations()->pluck('id');
                    if ($tour->route->stations->where('id',$stations['stationToId'])->first()->pivot->tickets_to == false) {
                        $landCount = RouteStation::whereRouteId($tour->route_id)
                            ->whereIn('station_id', $cityToStations)
                            ->where('tickets_to', true)->count();    // Кол-во остановок в городе прибытия, где разрешена высадка
                        if ($landCount == 0) {
                            unset($tours[$key]); // убирать рейс, где НЕ стоит ни одного флага tickets_to (отображать на этом машруте остановку клиентам и продавать ДО нее билеты)
                            continue;
                        }
                    }

                    $tourCost = StationCostIntervalService::index($tour, $stations['stationFromId'], $stations['stationToId']);

                    $_date_start = Carbon::createFromTimeString($tour->getOriginal('date_start').' '.$tour->time_start);
                    $_date_start->addMinutes($routeInterval[$tour->route_id]);
                    $_tour_date_now = $dateNow->copy()->addMinutes($tour->route->time_hidden_tour_front);  //Добавляется настройка "время, за которое отключать бронирование на сайте (мин)" для каждого направления (помимо основой для всего сайта: $settings->time_hidden_tour_front) 
                    if ($_date_start->lt($_tour_date_now) || $_date_start->lt($dateFrom)) {
                        continue;
                    }

                    $dataTours[] = [
                        'id' => $tour->id,
                        'reservation_by_place' => $tour->reservation_by_place,
                        'date_start' => Carbon::createFromTimeString($tour->getOriginal('date_start').' '.$tour->getOriginal('time_start'))->addMinutes($routeInterval[$tour->route_id])->toW3cString(),
                        'date_finish' => Carbon::createFromTimeString($tour->date_start->format('Y-m-d') . ' ' . $tour->time_start)->addMinutes($tour->route->interval)->toW3cString(),
                        'route_name' => $tour->route->name,
                        'route_interval' => $tour->route->interval,
                        'bus_id' => $tour->bus->id,
                        'bus' => $tour->bus->name,
                        'bus_number' => $tour->bus->number,
                        'bus_places' => $tour->bus->places,
                        'driver' => $tour->driver ? $tour->driver->full_name : '',
                        'driver_phone' => $tour->driver ? $tour->driver->phone : '',
                        'price' => $tourCost,
                        'currency' => $tour->route->currency->alfa,
                        'is_transfer' => $tour->route->is_transfer,
                        'flight_type' => $tour->route->flight_type,
                        'flight_ac_code' => $tour->schedule->flight_ac_code ?? '',
                        'flight_number' => $tour->schedule->flight_number ?? '',
                        'flight_time' => $tour->schedule->flight_time ?? '',
                        'flight_offset' => $tour->schedule->flight_offset ?? ''
                    ];

                }
            }

            $dataTours = collect($dataTours)->sortBy('date_start')->toArray();

            session(['order.city_from_id' => $city_from_id]);
            session(['order.city_to_id' => $city_to_id]);


        } elseif (empty($city_from_id)) {
            return $this->responseError(['message' => trans('messages.index.order.city_from')]);
        } elseif (empty($city_to_id)) {
            return $this->responseError(['message' => trans('messages.index.order.city_to')]);
        }


        return ['tours' => $dataTours];
    }


    public function stations(GetStationTourRequest $request)
    {
        $tour = Tour::where('id', $request->tour_id)->with('route')->get()->first();
        if ($tour->is_collect) {
            $statuses = [Station::STATUS_ACTIVE, Station::STATUS_COLLECT];
        } else {
            $statuses = [Station::STATUS_ACTIVE];
        }
        $stationsTo = $tour->route->stations->where('city_id', $request->to_city_id)
            ->whereIn('status', $statuses)
            ->transform(function ($item) use ($tour) {
            $dateTime = $tour->date_start->format('Y-m-d') . ' ' . $tour->time_start;
            return [
              'id' => $item->id,
              'name' => $item->name,
              'time' => Carbon::createFromTimeString($dateTime)->addMinutes($item->pivot->interval)->toW3cString()
            ];
        });

        $stationsFrom = $tour->route->stations->where('city_id', $request->from_city_id)
            ->whereIn('status', $statuses)
            ->transform(function ($item) use ($tour) {
            $dateTime = $tour->date_start->format('Y-m-d') . ' ' . $tour->time_start;
            return [
              'id' => $item->id,
              'name' => $item->name,
              'time' => Carbon::createFromTimeString($dateTime)->addMinutes($item->pivot->interval)->toW3cString()
            ];
        });

        if ($tour->route->is_international) {
            $requiredFields = explode(",", $tour->route->required_inputs);
        }

        return [
          'stationsFrom' => array_values($stationsFrom->toArray()),
          'stationsTo' => array_values($stationsTo->toArray()),
          'requiredFields' => $requiredFields ?? [],
        ];
    }


    public function taxiStations(Request $request)
    {
        $routes = Route::with('stationsActive')->where('is_taxi', false)->where('status', Route::STATUS_ACTIVE)->get();
        $prices = \DB::table('route_station_price')
            ->select('route_station_price.*')
            ->leftJoin('routes', 'routes.id', '=', 'route_station_price.route_id')
            //->where('is_taxi', false)
            ->where('status', Route::STATUS_ACTIVE)
            ->get();

        $stations = collect([]);

        foreach ($routes as $route) {
            $stations = $stations->merge($route->stationsActive);
        }

        $stations = Station::whereStatus(Station::STATUS_ACTIVE)->get();

        return \response()->json([
            'stations' => $stations,
            'prices' => $prices,
        ]);
    }

    public function storePlaces()
    {
        try {
            if (!$token = Token::where('api_token', \request()->get('api_token'))->first()) {
                return $this->responseJsonError(['message' => trans('validation.no_exist') . ' token']);
            }

            $tour = Tour::find(\request()->get('tour_id'));
            $statuses = [Station::STATUS_ACTIVE];
            if ($tour && $tour->is_collect) {
                $statuses[] = Station::STATUS_COLLECT;
            }
            /*
            if ($tour->route->stations->where('city_id', \request()->get('city_from_id'))->first()->pivot->order == 0) {
                $stationFrom = $tour->route->stations->whereIn('status', $statuses)
                  ->where('city_id', \request()->get('city_from_id'))->first();
            } else {
                $stationFrom = $tour->route->stations->whereIn('status', $statuses)
                  ->where('city_id', \request()->get('city_from_id'))->last();
            }

            if ($tour->route->stations->where('city_id', \request()->get('city_to_id'))->last()->pivot->order == ($tour->route->stations->count() - 1)) {
                $stationTo = $tour->route->stations->whereIn('status', $statuses)
                  ->where('city_id', \request()->get('city_to_id'))->last();
            } else {
                $stationTo = $tour->route->stations->whereIn('status', $statuses)
                  ->where('city_id', \request()->get('city_to_id'))->first();
            }

            if (empty($stationFrom)) return $this->responseError();
            $stationFromId = $stationFrom->id;

            $stationToId = $stationTo->id;
            */
            $stationFromId = \request()->get('station_from_id');
            $stationToId = \request()->get('station_to_id');

            if ($orderId = session('order.id')) {
                if (!Order::find($orderId)) {
                    session(['order.id' => null]);
                }
            }

            $data = [
                'tour_id' => $tour->id,
                'type' => Order::TYPE_NO_COMPLETED,
                'source' => Order::SOURCE_CLIENT_APP,
                'places_with_number' => $tour->reservation_by_place,
                'places' => request('places', []),
                'client_id' => $token->client_id,
                'status' => Order::STATUS_ACTIVE,
                'station_from_id' => $stationFromId,
                'station_to_id' => $stationToId,
                'type_pay' => request('type_pay', null),
                'id' => null,
            ];

            foreach (['comment', 'address_from_street', 'address_from_house', 'address_from_building', 'address_from_apart',
                         'custom_address_from', 'address_to_street', 'address_to_house', 'address_to_apart', 'custom_address_to'] as $field) {
                if (!empty(\request()->get($field))) {
                    $data[$field] = \request()->get($field);
                }
            }

            list ($order, $error) = StoreOrderService::index($data, $tour);
            if ($error) {
                return $this->responseError(['message' => $error]);
            }

            $tour = $tour->fresh();

            if (\request('passengers') && !empty(\request('passengers')) && is_array(\request('passengers'))) {
                $passengers = \request('passengers');
                foreach ($order->orderPlaces as $key => $place) {
                    if (!$key) continue;
                    elseif (isset($passengers[$key - 1])) {
                        $fields = ['passport', 'name', 'surname', 'patronymic'];
                        $passenger = $passengers[$key - 1];
                        foreach ($fields as $field) {
                            $place->$field = isset($passenger[$field]) ? $passenger[$field] : null;
                        }
                        $place->save();
                    }
                }
            }
            return [
              'id' => $order->id,
              'station_from_time' => $order->station_from_time,
              'station_to_time' => $order->station_to_time
            ];
        } catch (\Exception $e) {
            HandlerError::index($e);
        }
    }

    public function taxi(Request $request) {
        $date = Carbon::createFromTimestamp($request->date)->addMinutes(3);
        $station_from_id = $request->station_from_id;
        $station_to_id = $request->station_to_id;
        $dataFilter['between_time'] = ['dateTimeFrom' => $date->copy()->subHours(12)->format('Y-m-d H:i:s'), 'dateTimeTo' => $date->copy()->addHours(12)->format('Y-m-d H:i:s')];
        $dataFilter['status'] = Tour::STATUS_ACTIVE;

        $tours = Tour::filter($dataFilter)
            ->with('route', 'route.stations')
            ->whereHas('route', function ($q) {
                $q->where('is_regular', 1);
            })->orderBy('date_time_start')
            ->get();

        if ($tours->count()) {
            $fragmentOrder = config('app.FRAGMENTATION_RESERVED');

            foreach ($tours as $key => &$tour) {
                if (in_array($station_from_id, $tour->route->stations->pluck('id')->toArray()) &&
                    in_array($station_to_id, $tour->route->stationsFrom($station_from_id)->pluck('id')->toArray()) &&
                    ($fragmentOrder ? $tour->freePlacesBetween($station_from_id, $station_to_id) : $tour->freePlacesCount) >= $request->count_places) {  // убирать где нет свободных мест
                        $tour->station_from_time = StationIntervalsService::getDepartureDateTimeFromStation($tour, $station_from_id, 'Y-m-d H:i:s');
                } else {
                    unset($tours[$key]);
                }
            }

            foreach ($tours as $key => &$tour) {
                if ((new Carbon($tour->station_from_time))->greaterThanOrEqualTo($date))   {
                    $tour->price = StationCostIntervalService::index($tour, $station_from_id, $station_to_id) * $request->count_places;
                    $tour->bus_name = $tour->bus->name;
                    $tour->currency = $tour->route->currency->alfa ?? 'BYN';
                    Arr::forget($tour, ['route', 'bus', 'ordersReady']);
                } else {
                    unset($tours[$key]);
                }

            }
            $tours = $tours->sortBy('station_from_time');
        }

        return ['tours' => $tours->values()];
    }

    public function transfer(Request $request) {        // Возвращает список трансферных рейсов на ближайшие 12 часов 
        $date = Carbon::createFromFormat('Y-m-d', $request->date);
        $station_from_id = 4;       // Airport
        $station_to_id = 5;
        if (!$date->isToday()) {
            $date->startOfDay();
        }
        $dataFilter['between_time'] = ['dateTimeFrom' => $date->format('Y-m-d H:i:s'), 'dateTimeTo' => $date->copy()->addDay(1)->format('Y-m-d H:i:s')];
        $dataFilter['status'] = Tour::STATUS_ACTIVE;

        if (Config::getValue('global', 'enable_transfer_api', true) == false)  {
            return ['tours' => []];
        }
        
        $tours = Tour::filter($dataFilter)
            ->with('route', 'route.stations')
            ->where('is_show_front', true)
            ->whereHas('route', function ($q) {
                $q->where('is_transfer', 1);
            })->orderBy('date_time_start')
            ->get();

        if ($tours->count()) {
            foreach ($tours as $key => &$tour) {
                if (in_array($station_from_id, $tour->route->stations->pluck('id')->toArray()) &&
                    in_array($station_to_id, $tour->route->stationsFrom($station_from_id)->pluck('id')->toArray()) &&
                    $tour->freePlacesCount >= $request->places) {                 // убирать где нет свободных мест
                    $tour->station_from_time = StationIntervalsService::getDepartureDateTimeFromStation($tour, $station_from_id, 'Y-m-d H:i:s');
                    $tour->total_price = SaleToOrderService::tourPrice($tour, $request->places);        // Общая стоимость выбранного кол-ва мест с учетом скидок
                    $tour->total_price = $tour->total_price + 5;
                } else {
                    unset($tours[$key]);
                }
                $tour->free_places = $tour->freePlacesCount;
                unset($tour->route);
                unset($tour->ordersReady);
            }
            
        }

        return ['tours' => $tours->values()];
    }
}
