<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use App\Http\Requests\Index\Order\ScheduleFormRequest;
use App\Http\Requests\Index\Order\StorePlacesRequest;
use App\Http\Requests\Request;
use App\Models\City;
use App\Models\Client;
use App\Models\Order;
use App\Models\Route;
use App\Models\Setting;
use App\Models\Station;
use App\Models\Tour;
use App\Repositories\SelectRepository;
use App\Repositories\SelectRepositoryIndex;
use App\Services\Log\TelegramLog;
use App\Services\Order\FragmentationOrder;
use App\Services\Order\StationCostIntervalService;
use App\Services\Order\StationIntervalsService;
use App\Services\Order\StoreOrderService;
use App\Services\Prettifier;
use App\Services\Route\GetFromStation;
use App\Services\Route\GetToStation;
use App\Services\Support\HandlerError;
use App\Validators\Order\StoreOrderValidator;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    protected $select;
    protected $selectIndex;

    public function __construct(SelectRepository $selectRepository)
    {
        $this->select = $selectRepository;
        $this->selectIndex = new SelectRepositoryIndex();
    }

    public function getTourDates()
    {
        $city_from_id = request('city_from_id');
        $city_to_id = request('city_to_id');
        $routes = $this->selectIndex->routeSchedules($city_from_id, $city_to_id);

        $tours = Tour::where('date_start', '>=', Carbon::now()->format('Y-m-d'))
            ->where('status', Tour::STATUS_ACTIVE)
            ->whereIn('route_id', $routes->pluck('id')->toArray())
            ->get();

        $dates = [];
        foreach ($tours as $tour) {
            $date = $tour->date_start->format('Y-m-d');
            $dates[$date] = $date;
        }
        foreach ($routes as $route) {
            if ($route->allow_ind_transfer) {
                foreach (range(0, 14) as $day)  {
                    $dateplus = date('Y-m-d', strtotime('+'.$day.' day'));
                    $dates[$dateplus] = $dateplus;
                }
                break;
            }
        }
        sort($dates);
        return array_values($dates);
    }

    public function index(ScheduleFormRequest $request, Tour $tour)
    {
        $routes = array();
        $city_from_id = request('city_from_id');
        $city_to_id = request('city_to_id');
        $settings = Setting::first();
        $places = $request->places ?? 1;

        if (env('TIME_ZONE')) {
            if (!empty(auth()->user()->client_id) && Client::where("id", auth()->user()->client_id)->first()->timezone != '') {
                $tz = Client::where("id", auth()->user()->client_id)->first()->timezone;
            }
            else {
                $tz = $settings->default_timezone;
            }
            $date = Carbon::createFromFormat('d.m.Y', request('date'), $tz);
        } else {
            $date = Carbon::createFromFormat('d.m.Y', request('date'));
        }
        $origDate = $date->copy(); 

        if (Carbon::now()->format('d.m.Y') != request('date')) {        // Если дата сегодняшняя, то отображаем рейсы с текущего времени, иначе с 0 часов
            if (Carbon::now()->addMinutes($settings->time_hidden_tour_front)->format('d.m.Y') == request('date')) {     // Если дата завтрашняя, но до завтра меньше минут, чем в настройке "Время, за которое отключать бронирование на сайте", то отображаем рейсы с текущего времени
                $date->subDay();
            } else {
                $date->setTime(0, 0, 0);
                $settings->time_hidden_tour_front = -1;
            }
        }

        $return_ticket = request('return_ticket') ? 1 : 0;
        $curOrder = $return_ticket ? 'order_return' : 'order';
        $order = Order::find(session($curOrder.'.id'));

        $MAIN_VIEW = view('index.schedules.partials.changeDate', compact('date'))->render();

        if ($city_from_id && $city_to_id) {
            $routes = $this->selectIndex->routeSchedules($city_from_id, $city_to_id, $date);
        } elseif (request('route_id')) {
            $routes = Route::with('tours', 'stations')->where('id', request('route_id'))->get();
            $city_from_id = Station::find(request('station_from_id'))->city->id;
            $city_to_id = Station::find(request('station_to_id'))->city->id;
        } elseif (empty($city_from_id)) {
            return $this->responseError(['message' => 'Не выбран город посадки']);
        } elseif (empty($city_to_id)) {
            return $this->responseError(['message' => 'Не выбран город высадки']);
        }

        session([$curOrder.'.city_from_id' => $city_from_id]);
        session([$curOrder.'.city_to_id' => $city_to_id]);

        $allTours = [];
        $indTransferRoutes = [];
        foreach ($routes as $route) {

            $stationFrom = GetFromStation::index($route, $city_from_id);
            $stationTo = GetToStation::index($route, $city_to_id);

            $stationStart = Station::find($route->stations->first()->id);
            $timeStart = new Carbon('today', City::find($stationStart->city_id)->timezone);
            $timeFrom = new Carbon('today', City::find($city_from_id)->timezone);
            $timeTo = new Carbon('today', City::find($city_to_id)->timezone);
            $diffFrom = $timeStart->diffInMinutes($timeFrom, false);
            $diffTo = $timeStart->diffInMinutes($timeTo, false);

            $stationFromInterval = $stationFrom->pivot->interval - $diffFrom;
            $stationToInterval = $stationTo->pivot->interval - $diffTo;


            $dateTimeStart = $date->copy()->subMinutes($stationFromInterval)->addMinutes($settings->time_hidden_tour_front)->addMinutes($settings->time_hidden_tour_front == -1 ? 0 : $route->time_hidden_tour_front);  // Суммируются два разных параметра time_hidden_tour_front - общий в settings и для каждого направления

            $tours = Tour::with('route', 'bus', 'schedule', 'bus.amenities')
                ->where('route_id', $route->id)
                /*->where(function ($query) use ($date, $dateTimeStart) {
                    $query->where(function ($query) use ($date) {
                        $query->whereNull('date_time_start')
                            ->where('date_start', $date->format('Y-m-d'))
                            ->where('time_start', '>=', $date->format('H:i:s'));
                    })
                    ->orWhere(function ($query) use ($date, $dateTimeStart) {
                        $query->where('date_time_start', '>=', $dateTimeStart)
                            ->whereBetween('date_start', [$date->copy()->subDays(1)->format('Y-m-d'), $date->format('Y-m-d')]);
                    });
                })*/
                ->whereBetween('date_start', [$date->copy()->subDays(1)->format('Y-m-d'), $origDate->format('Y-m-d')])        // Раскомментировать после обновления старых рейсов
                ->where('date_time_start', '>=', $dateTimeStart)
                ->where('is_show_front', true)
                //->where('status', Tour::STATUS_ACTIVE)
                ->where(function ($query) {
                    $query->where('status', Tour::STATUS_ACTIVE)
                        ->orWhere('status', Tour::STATUS_VIRTUAL);
                })
                ->orderBy('time_start')
                ->get();

            if ($tours->count()) {
                foreach ($tours as $key => $tour) {
                    $price = StationCostIntervalService::index($tour, $stationFrom->id, $stationTo->id);
                    $tour->price = $price;
                    $tour->freeCountPlacesNew = env('FRAGMENTATION_RESERVED') ? $this->selectIndex->freePlaces($tour, $stationFrom, $stationTo) : $tour->freePlacesCount;
                    $tour->freeCountPlacesNew = $tour->freeCountPlacesNew < 1 ? 0 : $tour->freeCountPlacesNew;
                    $tour->stationFromInterval = $stationFromInterval;
                    $tour->stationToInterval = $stationToInterval;
                    $tour->time_start = Prettifier::prettifyTime($tour->time_start, $stationFromInterval);
                    $tour->time_finish = Prettifier::prettifyTime($tour->time_start, $stationToInterval - $stationFromInterval);
                    $tour->datetime_start = Prettifier::prettifyDateTime($tour->prettyDateStart, $tour->getOriginal('time_start'), $stationFromInterval);
                    $tour->datetime_start_iso = Carbon::createFromFormat('Y-m-d H:i:s', $tour->getOriginal('date_start') . ' ' . $tour->getOriginal('time_start'))->addMinutes($stationFromInterval);
                    $tour->datetime_finish = Prettifier::prettifyDateTime($tour->prettyDateStart, $tour->getOriginal('time_start'), $stationToInterval);
                    if ($tour->schedule_id && $tour->schedule->flight_time) {
                        $secs = strtotime($tour->schedule->flight_offset) - strtotime("00:00:00");
                        if ($tour->route->flight_type == 'departure')   {       // Если трансфер к отправлению рейса, то отнимаем сдвиг
                            $secs *= -1;
                        }
                        $tour->airport_time = date("H:i", strtotime($tour->schedule->flight_time) + $secs);
                    }

                    $allTours[] = $tour;
                }
            }
            if ($route->allow_ind_transfer) {
                $indTransferRoutes[] = $route;
            }
        }
        $tours = collect($allTours)->sortBy('datetime_start_iso');
        $firstRoute = $tours->first()->route ?? $indTransferRoutes[0] ?? null;
            $MAIN_VIEW .= view('index.schedules.partials.schedule_block', compact('date', 'stationFromInterval',
                'stationToInterval', 'order', 'settings', 'stationFrom', 'stationTo', 'tours', 'return_ticket', 'firstRoute', 'places'));
        if (empty($MAIN_VIEW)) {
            return $this->responseError(['message' => 'Свободные рейсы не найдены на ' . $date->format('Y-m-d')]);
        }

        return $this->responseSuccess(['html' => $MAIN_VIEW]);
    }


    public function getBus(Tour $tour)
    {
        $settings = Setting::first();

        if ($tour->status == Tour::STATUS_VIRTUAL && $tour->schedule_id)   {
            $flightNum = $tour->schedule->flight_ac_code . '-' . $tour->schedule->flight_number; 
            $tour = $tour->nearestActive($tour->route->flight_type == 'departure' ? true : false);
        }
        
        $error = StoreOrderValidator::tour($tour);

        if ($error) return $this->responseError(['message' => trans('messages.index.order.error')]);

        $curOrder = request('return_ticket') == 1 ? 'order_return' : 'order';
        $curBackOrder = request('return_ticket') == 1 ? 'order' : 'order_return';
        $order = Order::find(session($curOrder.'.id'));
        if ($order) $error = StoreOrderValidator::order($order, $tour->id);

        if (!$order || $error) $order = new Order();
        if (!session()->has($curOrder))  {
            session([$curOrder.'.city_to_id' => session($curBackOrder)['city_from_id']]);
            session([$curOrder.'.city_from_id' => session($curBackOrder)['city_to_id']]);
        }
        
        $stationFrom = GetFromStation::index($tour->route, session($curOrder)['city_from_id']);
        $stationTo = GetToStation::index($tour->route, session($curOrder)['city_to_id']);

        session([$curOrder.'.station_from_id' => $stationFrom->id]);
        session([$curOrder.'.station_to_id' => $stationTo->id]);
        session([$curOrder.'.flight_number' => $flightNum ?? '']);
        $places = request('places') ?? 1;
        $order->count_places = $places;

        $tour->freeCountPlacesNew = $this->selectIndex->freePlaces($tour, $stationFrom, $stationTo); // Разбиение мест рейса
        return $this->responseSuccess([
            'view' => view('index.schedules.partials.places',
                compact('tour', 'order', 'settings', 'stationTo', 'stationFrom'))->render(),
        ]);
    }

    public function storePlaces(StorePlacesRequest $request, Tour $tour)
    {
        try {
            $curOrder = request('return_ticket') == 1 ? 'order_return' : 'order';
            $sessionOrder = session($curOrder);
            $stationFrom = GetFromStation::index($tour->route, $sessionOrder['city_from_id']);
            $stationTo = GetToStation::index($tour->route, $sessionOrder['city_to_id']);

            if (!$stationFrom) {
                return $this->responseError();
            }

            session([$curOrder.'.station_from_id' => $stationFrom->id]);
            session([$curOrder.'.station_to_id' => $stationTo->id]);

            $settings = Setting::first();
            if (isset($sessionOrder['id']) && !Order::find($sessionOrder['id'])) {
                session([$curOrder.'.id' => null]);
            }

            $data = [
                    'tour_id' => $tour->id,
                    'type' => Order::TYPE_NO_COMPLETED,
                    'source' => Order::SOURCE_SITE,
                    'places_with_number' => $tour->reservation_by_place,
                    'places' => request('places', []),
                    'client_id' => auth()->user() ? auth()->user()->client_id : null,
                    'status' => Order::STATUS_ACTIVE,
                ] + session($curOrder, []);

            list($order, $error) = StoreOrderService::index($data, $tour);

            if ($error) {
                return $this->responseError(['message' => $error]);
            }

            session([$curOrder.'.id' => $order->id]);

            $tour = $tour->fresh();
            $order->load('orderPlaces');

            return $this->responseSuccess([
                'view' => view('index.schedules.partials.places', compact('tour', 'order', 'settings'))->render(),
                'view_continue' => view('index.schedules.partials.bus.continue', compact('order', 'settings'))->render(),
                'return_ticket' => intval($request->return_ticket),
            ]);
        } catch (\Exception $e) {
            HandlerError::index($e);
        }
    }

    public function embeddedForm()
    {
        $routes = $this->selectIndex->routes();
        $cityIds = array();

        foreach ($routes as $route) {
            $cityIds += $route->stationsActive->pluck('city_id', 'city_id')->toArray();
        }

        if (!count($routes)) abort(404);
        $cities = City::whereIn('id', $cityIds)->orderBy('name')->get()->pluck('name', 'id');

        return view('index.home.partials.reservation-embedded', compact('routes', 'cities'));
    }
}

