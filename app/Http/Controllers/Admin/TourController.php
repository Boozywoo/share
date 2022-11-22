<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TourFromPullRequest;
use App\Http\Requests\Admin\TourRequest;
use App\Http\Requests\Admin\TourRentRequest;
use App\Http\Requests\Admin\TourToPullRequest;
use App\Models\Bus;
use App\Models\City;
use App\Models\Company;
use App\Models\Order;
use App\Models\OrderPlace;
use App\Models\Rent;
use App\Models\Route;
use App\Models\Tour;
use App\Models\Setting;
use App\Models\Package;
use App\Notifications\Order\ChangeOrderNotification;
use App\Repositories\SelectRepository;
use App\Services\Order\StationIntervalsService;
use App\Services\Prettifier;
use App\Services\Rent\CalculatePrice;
use App\Services\Route\GetFromStation;
use App\Services\Route\GetToStation;
use App\Services\Sms\SMSClientStatic;
use App\Services\Tour\DuplicateService;
use App\Services\Tour\OrderImportService;
use App\Services\Tour\TourPullService;
use App\Services\Tour\YandexRoutingService;
use App\Validators\Tour\StoreTourValidator;
use App\Services\Geo\GeoService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Client;

class TourController extends Controller
{
    protected $entity = 'tours';
    protected $select;

    public function __construct(SelectRepository $selectRepository)
    {
        $this->select = $selectRepository;
    }

    public function index()
    {
        $client = request('incomming_phone') ?
            Client::filter(['phone' => request()->incomming_phone])->first() : NULL;
        $userRoutes = \Auth::user()->routes->keyBy('id');

        $dataFilter = request()->except('routes', 'date');

        if (isset($dataFilter['status'])) {
            if ($dataFilter['status'] == Tour::STATUS_ACTIVE) {
                $dataFilter['exclude_type_driver'] = 'completed';
            } elseif ($dataFilter['status'] == 'active_all') {
                unset($dataFilter['status']);
                $dataFilter['statuses'] = [Tour::STATUS_ACTIVE, Tour::STATUS_REPAIR, Tour::STATUS_DUPLICATE];
            }
        }
        $date = Carbon::createFromFormat('d.m.Y H:i', (empty(request('date')) ? date('d.m.Y') : request('date')) . ' 00:00');

        if (empty(request('all_dates'))) {
            $dataFilter['between'] = ['dateFrom' => $date->copy()->subDays(1)->format('Y-m-d'), 'dateTo' => $date->format('Y-m-d')];
        }

        if (!empty(request('from')) && !empty(request('to'))) {
            $date = Carbon::createFromFormat('d.m.Y H:i', (request('from')) . ' 00:00');
            $dataFilter['between'] = ['dateFrom' => $date->copy()->subDays(1)->format('Y-m-d'),
                'dateTo' => Carbon::createFromFormat('d.m.Y', request('to'))->format('Y-m-d')];
        }

        $routeFilter = ['routes' => auth()->user()->routeIds];

        $routes = $this->select->routes(auth()->id(), true, true);
        $filterRouteTypes = [];
        foreach (trans('admin_labels.regular_transfer') as $routeType => $val) {       // Получаем кол-во направлений для каждого типа (обычный, трансфер, такси и т.д.)
            if (Route::whereIn('id', array_keys($routes->toArray()))->where($routeType, true)->whereHas('stations')->count() > 0) {
                $routeTypes[] = $routeType;
            }
            if ($dataFilter[$routeType] ?? 0 == 1) {       // Выбранные птичками типа направлений
                $filterRouteTypes[] = $routeType;
            }
        }

        if (!empty($filterRouteTypes)) {
            $routeFilter['routes'] = Route::whereIn('id', auth()->user()->routeIds)
                ->where(function ($q) use ($filterRouteTypes) {
                    foreach ($filterRouteTypes as $field) {
                        $q->orWhere($field, true);
                    }
                })
                ->pluck('id');
        }

        $tours = Tour::filter($dataFilter + $routeFilter)
            ->orderBy('date_time_start')
            ->with('packages', 'route', 'rent', 'driver', 'bus', 'ordersReady', 'ordersPull', 'ordersPullReserve', 'ordersCompleted', 'schedule', 'orderPlaces', 'route.currency', 'route.stations', 'route.stations.city')
            ->withCount('ordersReady')
            ->get();

        $routesFull = Route::with('stations', 'stations.city')->whereIn('id', array_keys($routes->toArray()))->whereHas('stations')->get()->keyBy('id');
        $routeIntervalFrom = [];
        $routeIntervalTo = [];
        $routeStartTz = [];
        $stats = ['totalOrders' => 0, 'passengers' => 0, 'totalPaid' => 0, 'totalPaidCash' => 0, 'totalWaitPay' => 0, 'totalPaidOnline' => 0, 'totalPaidBank' => 0, 'toursWithOrders' => 0];
        if (!empty(\request('city_from_id'))) {
            $cityFrom = City::find(\request('city_from_id'));
        }
        $cityToId = empty(\request('city_to_id')) ? null : \request('city_to_id');

        foreach ($routesFull as $key => $route) {
            $stationFirst = $route->stations->first();
            if ($city_from_id = \request('city_from_id')) {
                $stationFrom = GetFromStation::index($route, $city_from_id);
                $routeIntervalFrom[$key] = $stationFrom ? $stationFrom->pivot->interval : 0;
            } else {
                $routeIntervalFrom[$key] = 0;
            }
            $stationTo = GetToStation::index($route, $cityToId ?? $route->stations->last()->city->id);
            $routeIntervalTo[$key] = $stationTo ? $stationTo->pivot->interval : 0;

            $routeStartTz[$key] = new Carbon('today', $stationFirst->city->timezone);    // Таймзона первой остановки маршрута
        }

        foreach ($tours as &$tour) {
            if ($tour->route->status == 'active') {
                if (empty($tour->date_time_start)) {
                    $tour->date_time_start = Carbon::createFromFormat('Y-m-d H:i:s', $tour->date_start->toDateString() . ' ' . $tour->time_start);
                }
                $tour->station_time_start = new Carbon($tour->date_time_start);
                $tour->station_time_start->addMinutes($routeIntervalFrom[$tour->route_id]);

                if (!empty($cityFrom)) {
                    $timeFrom = new Carbon('today', $cityFrom->timezone);
                    $diffFrom = $routeStartTz[$tour->route_id]->diffInMinutes($timeFrom, false);  // Вычисляем разницу во времени между первой остановкой маршрута и остановкой посадки
                } else {
                    $diffFrom = 0;
                }

                $cityTo = empty($cityToId) ? $tour->route->stations->last()->city : City::find($cityToId);

                $timeTo = new Carbon('today', $cityTo->timezone);
                $diffTo = $routeStartTz[$tour->route_id]->diffInMinutes($timeTo, false);      // Вычисляем разницу во времени между первой остановкой маршрута и остановкой прибытия

                $tour->time_start_tz = $cityFrom->FullTimezone ?? $tour->route->stations->first()->city->FullTimezone;
                $tour->time_finish_tz = $cityTo->FullTimezone;
                $tour->time_start = Prettifier::prettifyDateTime($tour->prettyDateStart, $tour->time_start, $routeIntervalFrom[$tour->route_id] - $diffFrom, true);
                $tour->time_finish = Prettifier::prettifyDateTime($tour->prettyDateStart, $tour->getOriginal('time_start'), $routeIntervalTo[$tour->route_id] - $diffTo, true);

                if (Package::where('tour_id', $tour->id)->count()) {
                    $tour->package = true;
                } else {
                    $tour->package = false;
                }

            }
        }

        $tours = $tours->filter(function ($tour, $key) use ($date) {
            return $tour->station_time_start >= $date;
        });

        $stats['totalPricePackages'] = [];
        $stats['totalCountPackages'] = 0;

        $pricePackagesCurr = [];
        foreach ($tours as $tour) {
            foreach ($tour->packages as $item) {
                $stats['totalCountPackages'] += 1;
                $pricePackagesCurr[$item->currencyName->alfa][] = $item->price;
            }
        }

        foreach ($pricePackagesCurr as $key => $currentName) $stats['totalPricePackages'][$key] = array_sum($currentName);

        foreach ($tours as $tour) {
            $totalOrders = $tour->ordersReady->count() + $tour->ordersCompleted->count();
            $stats['passengers'] += $tour->ordersReady->sum('count_places') + $tour->ordersCompleted->sum('count_places');
            $stats['totalPaidCash'] += $tour->ordersReady()->where(\DB::raw('COALESCE(appearance, 1)'), '!=', 0)->where('type_pay', Order::TYPE_PAY_CASH_PAYMENT)->sum('price');
            $stats['totalWaitPay'] += $tour->ordersReady()->where(\DB::raw('COALESCE(appearance, 1)'), '!=', 0)->where('type_pay', Order::TYPE_PAY_WAIT)->sum('price');
            $stats['totalPaidOnline'] += $tour->ordersReady()->where(\DB::raw('COALESCE(appearance, 1)'), '!=', 0)->where('type_pay', Order::TYPE_PAY_SUCCESS)->sum('price');
            $stats['totalPaidBank'] += $tour->ordersReady()->where(\DB::raw('COALESCE(appearance, 1)'), '!=', 0)->where('type_pay', Order::TYPE_CHECKING_ACCOUNT)->sum('price');
            $stats['totalPaid'] += $tour->ordersReady()->where(\DB::raw('COALESCE(appearance, 1)'), '!=', 0)->whereNotIn('type_pay', [Order::TYPE_PAY_WAIT, Order::TYPE_PAY_CANCEL])->sum('price');
            $stats['toursWithOrders'] += $tour->ordersReady->count() ? 1 : 0;
            $tour->unpaidOrders = $totalOrders - $tour->ordersReady->whereNotIn('type_pay', [Order::TYPE_PAY_WAIT, Order::TYPE_PAY_CANCEL])->count() - $tour->ordersCompleted->count();
            $stats['totalOrders'] += $totalOrders;
        }

        if (!empty(request('mass_price_update'))) {
            $tours->each(function ($item) {
                Tour::find($item->id)->update(['price' => request('mass_price_update')]);   // Изменяем сразу в базе, не трогая основную коллекцию $tours
                $item->price = request('mass_price_update');
            });
        }

        $company_id = request('company');
        if (!empty($company_id) && in_array($company_id, auth()->user()->companyIds->toArray())) {
            $drivers = $drivers = $this->select->drivers([0 => $company_id]);
        } else {
            $drivers = $this->select->drivers(auth()->user()->companyIds);
        }

        $tours = $tours->sortBy('station_time_start');

        $settings = Setting::first();
        if (request()->ajax() && !request('_pjax')) return $this->ajaxView($tours, $settings, $stats);
        $cities = $this->select->cities(true);
        $buses = $this->select->buses(auth()->user()->companyIds, [Bus::STATUS_ACTIVE, Bus::STATUS_OF_REPAIR]);
        $companies = Company::whereIn('id', auth()->user()->companyIds)->whereHas('drivers')->pluck('name', 'id')->prepend(trans('admin.clients.sel_company'), '');
        $haveOrders = trans('admin.clients.have_orders');
        $visible = trans('admin.clients.visible');

        return view('admin.tours.index', compact('tours', 'routes', 'buses', 'drivers', 'routeTypes',
                'cities', 'companies', 'client', 'settings', 'userRoutes', 'stats', 'haveOrders', 'visible') + ['entity' => $this->entity]);
    }

    public function import(Tour $tour)
    {

        $results = \Excel::load(request()->file('file'))->get();

        $model = new OrderImportService($results, auth()->user(), $tour);
        $res = $model->import();

        if ($res['action'] == 1) {
            return $this->responseError(['message' => $res['msg']]);
        } else if ($res['action'] == 2) {

            if ($res['msg'] == '') {
                return $this->responseSuccess(['message' => "Файл успешно импортирован"]);
            } else {
                return $this->responseSuccess(['message' => $res['msg']]);
            }
        }
        return $this->responseSuccess(['message' => "Файл успешно импортирован"]);

    }

    protected function ajaxView($tours, $settings, $stats = [])
    {
        $userRoutes = \Auth::user()->routes->keyBy('id');

        return response([
            'view' => view('admin.' . $this->entity . '.index.table', compact('tours', 'settings', 'userRoutes', 'stats') + ['entity' => $this->entity])->render(),
        ])->header('Cache-Control', 'no-cache, no-store');
    }

    public function show(Tour $tour)
    {
        SMSClientStatic::checkStatus();
        $this->authorize('route-id', $tour->route_id);
        $required_inputs = isset($tour->route) ? explode(',', $tour->route->required_inputs) : [];

        return view('admin.' . $this->entity . '.show', compact('tour', 'required_inputs') + ['entity' => $this->entity]);
    }

    public function toPull(TourFromPullRequest $request, Tour $tour)
    {
        $this->authorize('route-id', $tour->route_id);
        $required_inputs = isset($tour->route) ? explode(',', $tour->route->required_inputs) : [];

        Order::filter([
            'ids' => request('orders'),
            'tour_id' => $tour->id,
            'status' => Order::STATUS_ACTIVE
        ])->update(['pull' => 1]);
        return $this->responseSuccess([
            'view' => view('admin.' . $this->entity . '.show.content', compact('tour', 'required_inputs') + ['entity' => $this->entity])->render()
        ]);
    }

    public function fromPull(TourToPullRequest $request, Tour $tour)
    {
        $this->authorize('route-id', $tour->route_id);
        if ($tour->status != Tour::STATUS_ACTIVE) {
            return $this->responseError(['message' => 'Рейс должен иметь активный статус']);
        }

        $ordersError = TourPullService::from(request('orders'), $tour);

        $tour = $tour->fresh();
        $required_inputs = isset($tour->route) ? explode(',', $tour->route->required_inputs) : [];

        $result = ['view' => view('admin.' . $this->entity . '.show.content', compact('tour', 'required_inputs') + ['entity' => $this->entity])->render()];
        if (count($ordersError)) {
            return $this->responseError($result + ['message' => 'Несовпадение или места заняты у ' . implode(', ', $ordersError)]);
        }

        return $this->responseSuccess($result);
    }

    public function store(TourRequest $request)
    {
        $this->authorize('route-id', request('route_id'));
        $this->authorize('bus-id', request('bus_id'));
        $this->authorize('driver-id', request('driver_id'));

        $tourDuplicate = DuplicateService::index(request('id'), request('driver_id'), request('bus_id'), request('route_id'), request('time_start') . ':00', request('date_start'));

        if ($tourDuplicate && request('action') != 'forceEdit') {
            if ($tourDuplicate->bus_id == request('bus_id')) {
                return $this->responseError(['message' => 'Автобус занят на это время<br>(В рейсе на ' . Prettifier::prettifyDateTime($tourDuplicate->date_time_start) . ')']);
            }
            if ($tourDuplicate->driver_id == request('driver_id')) return $this->responseError(['message' => 'Водитель занят на это время<br>(В рейсе на ' . Prettifier::prettifyDateTime($tourDuplicate->date_time_start) . ')']);
        } elseif ($tourDuplicate && request('action') == 'forceEdit') {
            $typeDuplicate = true;
        }

        if ($id = request('id')) {

            DB::beginTransaction();
            $tour = Tour::find($id);

            $error = StoreTourValidator::status($tour, request()->all());
            if ($error) return $this->responseError(['message' => $error]);

            $this->authorize('route-id', $tour->route_id);

            $dataStore = request()->except('bus_id');
            $dataStore['time_start'] = request('time_start') . ':00';
            $dataStore['time_finish'] = date("H:i:s", strtotime($dataStore['time_start']) + $tour->route->interval * 60);
            $change_time = $tour->time_start != $dataStore['time_start'];
            if (!$tourDuplicate && $tour->status == Tour::STATUS_DUPLICATE) {
                $dataStore['status'] = Tour::STATUS_ACTIVE;
            } elseif ($tourDuplicate && !empty($typeDuplicate)) {
                $driverDuplicateTour = DuplicateService::index(request('id'), request('driver_id'), null, request('route_id'), request('time_start') . ':00', request('date_start'));
                $busDuplicateTour = DuplicateService::index(request('id'), null, request('bus_id'), request('route_id'), request('time_start') . ':00', request('date_start'));
                DuplicateService::forceEdit($driverDuplicateTour, $busDuplicateTour);
            }

            if ($tour->bus_id != request('bus_id')) {
                $bus = Bus::find(request('bus_id'));
                $dataStore['bus_id'] = request('bus_id');
                if ($tour->bus->status != Bus::STATUS_SYSTEM) {
                    $dataStore['shift'] = 1;
                }
                $dataStore['status'] = $bus->status;
                $isDiffBus = true;
            } else $isDiffBus = false;

            if (env('EGIS') && (($tour->driver_id !== (int)request('driver_id')) || $isDiffBus)) {
                $dataStore['egis_status'] = null;
                $dataStore['egis_answer'] = 'Данные рейса были изменены. Требуется отправка сведений в ЕГИС.';
            }

            $tour->update($dataStore);

            if (request('bus_id') && $isDiffBus || request('calculation')) {
                $bus = Bus::find(request('bus_id'));
                $dataTour = [
                    'bus_id' => request('bus_id'),
                    'status' => $bus->status == Bus::STATUS_SYSTEM ? Bus::STATUS_ACTIVE : $bus->status,
                ];

                $tour->orders()->active()->update(['pull' => 1]);
                $tour->update($dataTour);
                $tour->bus = $tour->bus()->first();
                $ordersError = TourPullService::from($tour->orders->pluck('id'), $tour);
                if (request('calculation')) {
                    DB::rollBack();
                    $little = true;
                    $oldTemplate = view('admin.tours.show.partials.bus', compact('tour', 'little'))->render();
                    $tour = $tour->fresh();
                    return $this->response('warning', [
                        'view' => view('admin.tours.popups.edit.info', ['tour' => $tour, 'noCoincided' => count($ordersError)])->render(),
                        'view_sub' => $oldTemplate,
                    ]);
                }

                DB::commit();

                if (count($ordersError)) {
                    return $this->responseSuccess(['message' => 'Не все места совпали. Эти брони помещены в пул']);
                }
            }

            DB::commit();
        } else {
            $tour = Tour::create(request()->all());
        }
        $tour = $tour->fresh();
        $newDates['date_time_start'] = Carbon::createFromFormat('d.m.Y H:i:s', $tour->date_start->format('d.m.Y') . ' ' . request('time_start') . ':00');
        $newDates['date_time_finish'] = $newDates['date_time_start']->copy()->addMinutes($tour->route->interval);
        $newDates['date_finish'] = $newDates['date_time_start']->copy()->addMinutes($tour->route->interval)->format('d.m.Y');
        $tour->update($newDates);

        // Обновление времени отправления и прибытия броней
        if (isset($change_time) && $change_time) {
            $tour->load('ordersActive');

            foreach ($tour->ordersActive ?: [] as $order) {
                $order->update([
                    'from_date_time' => StationIntervalsService::getDepartureDateTimeFromStation($tour, $order->station_from_id, 'Y-m-d H:i:s'),
                    'to_date_time' => StationIntervalsService::getDepartureDateTimeFromStation($tour, $order->station_to_id, 'Y-m-d H:i:s')
                ]);
            }
        }

        return $this->responseSuccess();
    }

    public function storeRent(TourRentRequest $request)
    {
        if (request('driver_id') && request('bus_id')) {
            $this->authorize('bus-id', request('bus_id'));
            $this->authorize('driver-id', request('driver_id'));
            $tourDuplicate = DuplicateService::index(request('id'), request('driver_id'), request('bus_id'), request('route_id'), request('time_start') . ':00', request('date_start'), request('time_finish'));
            if ($tourDuplicate) {
                if ($tourDuplicate->bus_id == request('bus_id')) return $this->responseError(['message' => 'Автобус занят на это время']);
                if ($tourDuplicate->driver_id == request('driver_id')) return $this->responseError(['message' => 'Водитель занят на это время']);
            }
        }
        $data = request()->all();
        foreach (['bus_id', 'driver_id', 'route_id'] as $item)
            if (!request($item)) $data[$item] = null;

        if ($id = request('id')) {
            $tour = Tour::find($id)->first();
            $tour->update($data);
        } else {
            $tour = Tour::create($data);
        }
        CalculatePrice::index($tour);
        return $this->responseSuccess();
    }

    public function showPopup(Tour $tour)
    {

        $url_query = parse_url(request()->headers->get('referer'), PHP_URL_QUERY);
        parse_str($url_query, $output);

        $this->authorize('route-id', $tour->route_id);
        $buses = $this->select->buses(auth()->user()->companyIds, [Bus::STATUS_ACTIVE, Bus::STATUS_OF_REPAIR, Bus::STATUS_SYSTEM]);
        $routes = $this->select->routes(auth()->id(), true, true);
        $drivers = $this->select->drivers(auth()->user()->companyIds);

        $route_price = $tour->route ? \App\Models\RouteStationPrice::where('route_id', $tour->route->id)
            ->where('station_from_id', $tour->route->stations->first()->id)
            ->where('station_to_id', $tour->route->stations->last()->id)->first() : null;
        $price = $tour->route ? ($tour->route->is_line_price ? $tour->price : ($route_price ? $route_price->price : $tour->price)) : 10;

        return ['html' => view('admin.tours.popups.edit.content', compact('tour', 'buses', 'routes', 'drivers', 'output', 'price') +
            ['entity' => $this->entity])->render()];
    }

    public function showPopupRent(Tour $tour)
    {
        $this->authorize('route-id', $tour->route_id);
        $buses = $this->select->buses(auth()->user()->companyIds, [Bus::STATUS_ACTIVE, Bus::STATUS_OF_REPAIR], true);
        $routes = $this->select->routes(auth()->id(), true, true);
        $drivers = $this->select->drivers(auth()->user()->companyIds);
        return ['html' => view('admin.tours.popups.editRent.content', compact('tour', 'buses', 'routes', 'drivers') + ['entity' => $this->entity])->render()];
    }

    public function sendSmsPopup(Tour $tour)
    {
        $this->authorize('route-id', $tour->route_id);
        $orders = $tour->ordersReady;
        return ['html' => view('admin.tours.popups.send-sms.content', compact('tour', 'orders') + ['entity' => $this->entity])->render()];
    }

    public function sendSms(Tour $tour)
    {
        $this->authorize('route-id', $tour->route_id);

        if ($tour->is_edit) {
            $orders = $tour->ordersReady()->has('client')->whereIn('id', request('orders'))->get();
            $tour->is_edit = 0;
            $tour->save();
            foreach ($orders as $order) {
                $order->client->notify(new ChangeOrderNotification($order, 'on'));
            }
        }

        return $this->responseSuccess();
    }

    public function sendEgisPopup(Tour $tour)
    {
        $this->authorize('route-id', $tour->route_id);
        $orders = $tour->ordersReady;
        $places = [];
        foreach ($orders as $order) {
            foreach ($order->orderPlaces as $key => $place) {
                if ($key == 0) {
                    foreach (OrderPlace::FILLABLE_ALL as $field) {
                        $place->$field = $order->client->$field;
                    }
                    $place->save();
                }
                $places[] = $place;
            }
        }

        if ($tour->egis_status == 'sent' && Storage::disk('ftp_egis_feedback')->exists('/PD_AUTO_' . $tour->egis_file . '.ack')) {
            $statusFile = Storage::disk('ftp_egis_feedback')->get('/PD_AUTO_' . $tour->egis_file . '.ack');  // Забираем результат проверки данных пассажиров
            file_put_contents(storage_path('app/egis/tours/' . $tour->id . '/PD_AUTO_' . $tour->egis_file . '.ack'), $statusFile);
            if (strpos($statusFile, 'errCode="0"')) {
                $tour->update(['egis_status' => 'success', 'egis_answer' => 'Данные успешно приняты системой ЕГИС.']);
            } else {
                $errorStart = strpos($statusFile, '<fault description="') + 20;
                $errorLast = strpos($statusFile, '"', $errorStart);
                $errorLine = strpos($statusFile, 'line=', $errorStart);
                $tour->update(['egis_status' => 'error',
                    'egis_answer' => 'Данные не приняты ЕГИС. Ошибка: ' . substr($statusFile, $errorStart, $errorLast - $errorStart) .
                        ' (пассажир ' . intval(preg_replace('/[^0-9.]+/', '', substr($statusFile, $errorLine + 5, 4)) - 1) . ')']);
            }
        };

        return ['html' => view('admin.tours.popups.sendEgis', compact('tour', 'orders', 'places') + ['entity' => $this->entity])->render()];
    }

    public function sendEgis(Tour $tour)
    {
        $this->authorize('route-id', $tour->route_id);
        $places = $tour->orderPlacesFull->whereIn('id', request('places'));

        $columns = ['surname', 'name', 'patronymic', 'birthday', 'docType', 'docNumber', 'documentAdditionalInfo', 'departPlace', 'arrivePlace',
            'routeType', 'departDate', 'departDateFact', 'citizenship', 'gender', 'recType', 'rank', 'operationType', 'operatorId', 'placeId',
            'route', 'places', 'buyDate', 'termNumOrSurname', 'arriveDate', 'arriveDateFact', 'grz', 'model', 'registerTimeIS', 'operatorVersion'];

        $egisId = env('EGIS_ID');
        $path = storage_path('app/egis/tours/' . $tour->id . '/');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $fname = $egisId . '_' . date('Y_m_d_H_i_s_000') . '.csv';
        $file = fopen($path . $fname, 'w');
        fputcsv($file, $columns, ';');
        $fromCity = $tour->route->stations->first()->city->name;
        $toCity = $tour->route->stationsActive->last()->city->name;

        foreach ($places as $place) {
            $line = [$place->last_name, $place->first_name, $place->middle_name ?? 'NA', $place->birth_day ? $place->birth_day->format('Y-m-d') : '',
                $place->doc_type, $place->doc_number ?? $place->passport, '', $place->order->stationFrom->city->name, $place->order->stationTo->city->name,
                '0', $place->order->from_date_time->format('Y-m-d\TH:i') . $place->order->stationFrom->city->UTCOffset, '',
                trans('admin_labels.countries.' . ($place->country_id ?? '0')), $place->gender, '1', '', '18', $egisId, $egisId,
                $fromCity . ' - ' . $toCity, $place->number, $place->order->created_at->format('Y-m-d\TH:iP'), $tour->driver->company->name,
                $place->order->to_date_time->format('Y-m-d\TH:i') . $place->order->stationTo->city->UTCOffset, '', '', '', $tour->created_at->format('Y-m-d\TH:iP'), '20',
            ];
            fputcsv($file, $line, ';');
        }

        $driverLine = [$tour->driver->last_name, $tour->driver->full_name, $tour->driver->middle_name ?? 'NA', $tour->driver->birth_day ? $tour->driver->birth_day->format('Y-m-d') : '',
            $tour->driver->doc_type, $tour->driver->doc_number, '', $fromCity, $toCity, '0', $tour->prettyDateTimeStart->format('Y-m-d\TH:iP'), '',
            trans('admin_labels.countries.' . ($tour->driver->country_id ?? '0')), $tour->driver->gender, '0', 'Водитель', '50', $egisId, $egisId,
            $fromCity . ' - ' . $toCity, '', '', '', Carbon::parse($tour->prettyDateStart . ' ' . $tour->prettyTimeFinish)->format('Y-m-d\TH:iP'), '',
            $tour->bus->number, $tour->bus->name_tr, $tour->created_at->format('Y-m-d\TH:iP'), '20',
        ];
        fputcsv($file, $driverLine, ';');
        fclose($file);

        Storage::disk('ftp_egis_passengers')->put('/' . $fname, file_get_contents($path . $fname));    // Отправляем файл на FTP ЕГИС
        $tour->update(['egis_file' => $fname, 'egis_status' => 'sent', 'egis_answer' => 'Данные отправлены. Ответ от ЕГИС пока не получен.']);

        return $this->responseSuccess();

    }

    public function delete(Tour $tour)
    {
        if ($tour->orders()->count() || $tour->schedule) {
            return $this->responseError(['message' => trans('messages.admin.tours.delete.error')]);
        }

        $tour->delete();
        return $this->responseSuccess();
    }

    public function copy(Tour $tour)
    {
        return ['html' => view('admin.rents.popups.copy', compact('tour'))->render()];
    }

    public function print_page(Tour $tour)
    {
        return $this->dataPrint($tour);
    }

    public function print_document(Tour $tour)
    {
        return view('admin.' . $this->entity . '.print_doc',
            ["orders" => $tour->ordersReady->sortBy('station_from_time'), "tour" => $tour]);
    }

    public function print_page_excel(Tour $tour)
    {
        if ($tour->route->is_transfer) {
            $routeName = ($tour->rent ? 'Аренда ' : $tour->route->name) . ' - ' . ($tour->schedule->flight_number ?? '')
                . (($tour->driver->last_name ?? '') . ', ' . $tour->driver->full_name) . ', ' . $tour->bus->number
                . ', ' . ($tour->date_start->format('d.m.Y') . ' ' . \Carbon\Carbon::parse($tour->time_start)->format('H-i'));
        } else {
            $routeName = ($tour->rent ? 'Аренда ' : $tour->route->name) . ' ' . $tour->date_start->format('Y-m-d');
        }
        \Excel::create($routeName . ' [посадка]', function ($excel) use ($tour) {
            $excel->sheet($tour->route->is_transfer ? 'брони по посадке - ' . ($tour->schedule->flight_number ?? '') : 'брони по посадке', function ($sheet) use ($tour) {
                if ($tour->route->is_transfer) {
                    $sheet->fromArray($this->dataPrintPlacesInTransfer($tour));
                } else {
                    $sheet->fromArray($this->dataPrintPlaces($tour));
                }
            });
        })->export('xlsx');
    }

    public function print_page_template_excel(Tour $tour)
    {
        $routeName = $tour->rent ? 'Аренда ' : $tour->route->name;
        \Excel::create("[шаблон загрузки заказов] " . $tour->date_start->format('Y-m-d') . ' ', function ($excel) use ($tour) {
            $excel->sheet('брони по посадке', function ($sheet) use ($tour) {

                $places[] = [
                    '#' => '',
                    trans('admin_labels.last_name') => '',
                    trans('admin_labels.first_name') => '',
                    trans('admin_labels.middle_name') => '',
                    trans('admin_labels.passport') => '',
                    trans('admin_labels.phone') => '',
                    trans('admin_labels.station_from_time') => '',
                    trans('admin_labels.station_from_id') => '',
                    trans('admin_labels.station_to_id') => '',
                    trans('admin_labels.place') => '',
                    trans('admin_labels.order_success') => '',
                    trans('admin_labels.flight_number') => '',
                    trans('admin_labels.comment') => '',
                ];
                $sheet->fromArray($places);
            });
        })->export('xlsx');
    }

    public function print_page_reverse(Tour $tour)
    {
        return $this->dataPrint($tour, 'station_to_time');
    }

    public function print_page_reverse_excel(Tour $tour)
    {
        if ($tour->route->is_transfer) {
            $routeName = ($tour->rent ? 'Аренда ' : $tour->route->name) . ' - ' . ($tour->schedule->flight_number ?? '')
                . (($tour->driver->last_name ?? '') . ', ' . $tour->driver->full_name) . ', ' . $tour->bus->number
                . ', ' . ($tour->date_start->format('d.m.Y') . ' ' . \Carbon\Carbon::parse($tour->time_start)->format('H-i'));
        } else {
            $routeName = ($tour->rent ? 'Аренда ' : $tour->route->name) . ' ' . $tour->date_start->format('Y-m-d');
        }
        \Excel::create($routeName . ' [высадка]', function ($excel) use ($tour) {
            $excel->sheet($tour->route->is_transfer ? 'брони по высадке - ' . $tour->schedule->flight_number : 'брони по высадке', function ($sheet) use ($tour) {
                if ($tour->route->is_transfer) {
                    $sheet->fromArray($this->dataPrintPlacesInTransfer($tour, 'station_to_time'));
                } else {
                    $sheet->fromArray($this->dataPrintPlaces($tour, 'station_to_time'));
                }
            });
        })->export('xlsx');
    }

    public function dataPrint(Tour $tour, $sortBy = 'station_from_time')
    {
        if ($tour->route->is_transfer) {
            $places = $this->dataPrintPlacesInTransfer($tour, $sortBy);
        } else {
            $places = $this->dataPrintPlaces($tour, $sortBy);
        }
        return view('admin.' . $this->entity . '.print',
            ["orders" => $tour->ordersReady, "tour" => $tour, "places" => $places]);
    }

    public function dataPrintPlaces(Tour $tour, $sortBy = 'station_from_time')
    {
        $this->authorize('route-id', $tour->route_id);
        $orders = $tour->ordersReady;
        $places = [];
        $orders = $orders->sortBy($sortBy);
        $textInputs = ['first_name', 'middle_name', 'last_name', 'passport', 'doc_number', 'phone'];
        $requiredInputs = $tour->route->requiredInputsArray;
        foreach ($orders as $order) {
            if ($order->client) {
                if ($order->tour->rent) {
                    $stationFrom = $order->tour->rent->address;
                    $stationTo = $order->tour->rent->address_to;
                    $cityFrom = $order->tour->rent->cityFrom ? $order->tour->rent->cityFrom->name : '';
                    $cityTo = $order->tour->rent->cityTo ? $order->tour->rent->cityTo->name : '';
                } else {
                    $stationFrom = $order->stationFrom->city->name . " (" . $order->stationFrom->name . ")";
                    $stationTo = $order->stationTo->city->name . ' ' . " (" . $order->stationTo->name . ")";
                    $cityFrom = $order->stationFrom->city->name;
                    $cityTo = $order->stationTo->city->name;
                }

                $place = ['#' => $order->id];

                foreach ($textInputs as $input) {
                    if (in_array($input, $requiredInputs)) {
                        $place[trans('admin_labels.' . $input)] = $order->client->$input ?? '';
                    }
                }
                /*if (in_array('birth_day', $requiredInputs)) {
                    $place[trans('admin_labels.birth_day')] = $order->client->birth_day->format('d.m.Y') ?? '';
                }
                foreach (['gender', 'country_id', 'doc_type'] as $input) {
                    if (in_array($input, $requiredInputs)) {
                        $place[trans('admin_labels.' . $input)] = trans('admin_labels.' . $input . 's.' . ($order->client->$input));
                    }
                }
                if (in_array('doc_number', $requiredInputs)) {
                    $place[trans('admin_labels.doc_number')] = $order->client->doc_number ?? '';
                }*/
                $place[trans('admin_labels.place')] = empty($order->orderPlaces[0]) ? '-' : $order->orderPlaces[0]->number;
                $orderData = [
                    trans('admin_labels.station_from_time') => $order->station_from_time,
                    trans('admin_labels.station_from_id') => $stationFrom,
                    trans('admin_labels.station_to_id') => $stationTo,
                    trans('admin_labels.order_success') => $order->client->order_success,
                    trans('admin_labels.comment') => $order->comment,
                ];
                $place = array_merge($place, $orderData);

                $places[] = $place;
                if (!empty($order->orderPlaces)) {
                    unset($order->orderPlaces[0]);
                }

                if ($order->orderPlaces)
                    foreach ($order->orderPlaces as $orderPlace) {
                        $place = ['#' => $order->id];

                        foreach ($textInputs as $input) {
                            if (in_array($input, $requiredInputs)) {
                                $place[trans('admin_labels.' . $input)] = $orderPlace->$input ?? '';
                            }
                        }
                        /*if (in_array('birth_day', $requiredInputs)) {
                            $place[trans('admin_labels.birth_day')] = $orderPlace->birth_day ? $orderPlace->birth_day->format('d.m.Y') : '';
                        }
                        foreach (['gender', 'country_id', 'doc_type'] as $input) {
                            if (in_array($input, $requiredInputs)) {
                                $place[trans('admin_labels.' . $input)] = trans('admin_labels.' . $input . 's.' . ($orderPlace->$input));
                            }
                        }
                        if (in_array('doc_number', $requiredInputs)) {
                            $place[trans('admin_labels.doc_number')] = $orderPlace->doc_number ?? '';
                        }*/
                        $place[trans('admin_labels.place')] = $orderPlace->number ?: '-';
                        $place = array_merge($place, $orderData);
                        $places[] = $place;
                    }
            }
        }
        return $places;
    }

    public function dataPrintPlacesInTransfer(Tour $tour, $sortBy = 'station_from_time')
    {
        $this->authorize('route-id', $tour->route_id);
        $orders = $tour->ordersReady;
        $places = [];
        $orders = $orders->sortBy($sortBy);

        foreach ($orders as $order) {
            if ($order->client) {

                $place = ['#' => $order->id];
                $orderData = [
                    trans('admin_labels.first_name') => $order->client->first_name,
                    trans('admin_labels.middle_name') => $order->client->middle_name,
                    trans('admin_labels.last_name') => $order->client->last_name,
                    trans('admin_labels.phone') => $order->client->phone,

                    trans('admin_labels.address') => $order->addressFrom ?? $order->addressTo,
                    trans('admin_labels.station_from_time') => $order->station_from_time,
                    trans('admin_labels.amountRents') => $order->price,
                    trans('admin_labels.comment') => $order->comment,
                    trans('admin_labels.count_places') => $order->orderPlaces->count(),
                ];
                $place = array_merge($place, $orderData);

                $places[] = $place;
            }
        }
        return $places;
    }

    public function getCitiesFromCityId(Tour $tour)
    {
        $cities = $tour->getFromCityIdTo(request('city_from_id'), '', 'cities');
        if (is_array($cities)) {
            $dataCities = City::whereIn('id', $cities)->get();
            foreach ($cities as $key => $city) {
                $name = $dataCities->where('id', $city)->first()->name;
                $cities[$key] = ["id" => $key, 'name' => $name];
            }
            return $cities;
        }
    }

    public function copyStore(Tour $tour)
    {
        try {
            $tour = Tour::find(\request()->get('id'));
            $rent = \DB::table('rents')->where('id', $tour->rent_id)->first();
            $newRent = new Rent();
            $newRent->save();
            foreach ($newRent->getFillable() as $item) {
                $newRent->$item = isset($rent->$item) ? $rent->$item : null;
            }
            $newRent->save();
            $newTour = new Tour();

            $newTour->rent()->associate($newRent);
            $newTour->save();
            $tourData = [];
            foreach ($newTour->getFillable() as $item) {
                if (in_array($item, ['date_start', 'time_start', 'date_finish', 'time_finish'])) {
                    if ($item == 'date_start' || $item == 'date_finish') {
                        $tourData[$item] = Carbon::createFromFormat('d.m.Y', \request()->get($item))->format('Y-m-d');
                    } else {
                        $tourData[$item] = $tourData[$item] = \request()->get($item);
                    }
                } else {
                    $tourData[$item] = $tour->$item;
                }
            }
            \DB::table('tours')->where('id', $newTour->id)->update($tourData);
            return $this->responseSuccess();
        } catch (\Exception $e) {
            return $this->responseError(['message' => $e->getMessage()]);
        }

    }

    public function statistic()
    {
        if (!$dateFrom = request('date_from')) $dateFrom = Carbon::now()->subDay(3)->format('Y-m-d');
        if (!$dateTo = request('date_to')) $dateTo = Carbon::now()->format('Y-m-d');

        $tours = request('status') ? Tour::whereBetween('date_start', [$dateFrom, $dateTo])->whereStatus(request('status'))->latest()->paginate()
            : Tour::whereBetween('date_start', [$dateFrom, $dateTo])->latest()->paginate();

        $resultcashpayment = 0;
        $resultcashpaymentoffice = 0;
        $resultcashpaymentchild = 0;
        $resultcashlessmentchild = 0;
        $resultcashlesspayment = 0;
        $resultcheckingaccount = 0;
        $resultsuccess = 0;
        $resultpass = 0;

        foreach ($tours as $tour) {
            $tour->date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $tour->date_start->format('Y-m-d') . $tour->time_start);

            $tour->cashpayment = $tour->orders->whereIn('type_pay', ['cash-payment', ''])->pluck('orderPlaces')->collapse()->where('appearance', true)->sum('price');
            $tour->cashpaymentoffice = $tour->orders->whereIn('type_pay', 'cash_payment_office')->pluck('orderPlaces')->collapse()->where('appearance', true)->sum('price');
            $tour->cashlesspayment = $tour->orders->where('type_pay', 'cashless_payment')->pluck('orderPlaces')->collapse()->where('appearance', true)->sum('price');
            $tour->checkingaccount = $tour->orders->where('type_pay', 'checking_account')->pluck('orderPlaces')->collapse()->where('appearance', true)->sum('price');
            $tour->success = $tour->orders->where('type_pay', 'success')->pluck('orderPlaces')->collapse()->where('appearance', true)->sum('price');
            $resultcashpayment += $tour->cashpayment;
            $resultcashlesspayment += $tour->cashlesspayment;
            $resultcheckingaccount += $tour->checkingaccount;
            $resultsuccess += $tour->success;
            $resultpass += $tour->pass;
            $resultcashpaymentoffice += $tour->cashpaymentoffice;
            $resultcashpaymentchild += $tour->cashpaymentchild;
            $resultcashlessmentchild += $tour->cashlessmentchild;
        }

        if (request()->ajax() && !request('_pjax')) {
            return response(['view' => view('admin.' . $this->entity . '.statistics.table',
                compact('tours', 'date', 'resultcashpayment', 'resultcashlesspayment', 'resultcheckingaccount', 'resultsuccess', 'resultpass',
                    'resultcashpaymentoffice', 'resultcashpaymentchild', 'resultcashlessmentchild') + ['entity' => $this->entity])->render(),
            ])->header('Cache-Control', 'no-cache, no-store');
        }

        return view('admin.tours.statistics',
            compact('tours', 'date', 'resultcashpayment', 'resultcashlesspayment', 'resultcheckingaccount', 'resultsuccess', 'resultpass',
                'resultcashpaymentoffice', 'resultcashpaymentchild', 'resultcashlessmentchild') + ['entity' => $this->entity]);
    }

    public function statisticExcel()
    {
        if (!$dateFrom = request('date_from')) $dateFrom = Carbon::now()->subDay(7)->format('Y-m-d');
        if (!$dateTo = request('date_to')) $dateTo = Carbon::now()->format('Y-m-d');

        $tours = request('status') ? Tour::whereBetween('date_start', [$dateFrom, $dateTo])->whereStatus(request('status'))->latest()->get()
            : Tour::whereBetween('date_start', [$dateFrom, $dateTo])->latest()->get();

        $resultcashpayment = 0;
        $resultcashlesspayment = 0;
        $resultcheckingaccount = 0;
        $resultsuccess = 0;
        $resultpass = 0;
        $resultcashpaymentoffice = 0;
        $resultcashpaymentchild = 0;
        $resultcashlessmentchild = 0;

        foreach ($tours as $tour) {
            $tour->date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $tour->date_start->format('Y-m-d') . $tour->time_start);

            $tour->cashpayment = $tour->orders->whereIn('type_pay', ['cash-payment', ''])->pluck('orderPlaces')->collapse()->where('appearance', true)->sum('price');
            $tour->cashlesspayment = $tour->orders->where('type_pay', 'cashless_payment')->pluck('orderPlaces')->collapse()->where('appearance', true)->sum('price');
            $tour->checkingaccount = $tour->orders->where('type_pay', 'checking_account')->pluck('orderPlaces')->collapse()->where('appearance', true)->sum('price');
            $tour->cashlessmentchild = $tour->orders->where('type_pay', 'cashless_payment_child')->pluck('orderPlaces')->collapse()->where('appearance', true)->sum('price');
            $tour->success = $tour->orders->where('type_pay', 'success')->pluck('orderPlaces')->collapse()->where('appearance', true)->sum('price');
            $resultcashpayment += $tour->cashpayment;
            $resultcashlesspayment += $tour->cashlesspayment;
            $resultcheckingaccount += $tour->checkingaccount;
            $resultsuccess += $tour->success;
            $resultpass += $tour->pass;
            $resultcashpaymentoffice += $tour->cashpaymentoffice;
            $resultcashpaymentchild += $tour->cashpaymentchild;
            $resultcashlessmentchild += $tour->cashlessmentchild;
        }

        $data = [];
        foreach ($tours as $tour) {
            $data[] = [
                trans('admin_labels.date') => $tour->date_start->format('d.m.Y'),
                trans('admin_labels.day_of_week') => strftime('%A', strtotime($tour->date_start->format('d.m.Y'))),
                trans('admin_labels.holiday') . " (" . trans('admin_labels.work_day') . ")" => $tour->date->isWeekday() ? trans('admin_labels.work_day') : trans('admin_labels.holiday'),
                trans('admin_labels.planned_time') => $tour->date->format('H:i'),
                trans('admin_labels.actual_time') => '',
                trans('admin.routes.single') => $tour->route->name ?? '',
                trans('admin_labels.bus_id') => $tour->bus->number ?? '',
                trans('admin_labels.driver_id') => $tour->driver->initials ?? '',
                trans('admin_labels.count_places') => $tour->bus->places ?? '',
                trans('admin_labels.company_id') => $tour->bus->company->name ?? '',
                trans('admin_labels.cnt_passengers') => $tour->busyPlacesCount ?? '',
                trans('admin_labels.sum_cash') => $tour->cashpayment ?? '',
                trans('admin_labels.cash_payment_office') => $tour->cashpaymentoffice ?? '',
                trans('admin_labels.cash_payment_child') => $tour->cashpaymentchild ?? '',
                trans('admin_labels.cashless_payment_child') => $tour->cashlessmentchild ?? '',
                trans('admin_labels.sum_cashless_payments') => $tour->cashlesspayment ?? '',
                trans('admin_labels.sum_payment_to_ca') => $tour->checkingaccount ?? '',
                trans('admin_labels.sum_online_pay') => $tour->success ?? '',
                trans('admin_labels.sum_pass') => $tour->pass ?? '',
                trans('admin_labels.mileage') => $tour->route->mileage ?? '',
                trans('admin_labels.actual_mileage') => '',
                trans('admin_labels.comment') => $tour->comment ?? '',
            ];
        }

        \Excel::create('tours', function ($excel) use ($data) {
            if (count($data)) {
                $excel->sheet(trans('admin.tours.statistics'), function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            }
        })->export('xls');
        return redirect()->back();

    }

    public function ordersMap(Tour $tour)
    {
        $tour = Tour::findOrFail($tour->id);
        $orders = $tour->ordersReady;
        $placesTable = [];
        $colorsTable = [];
        define("ORDER_COLORS", ['white', 'green', 'blue', 'yellow', 'violet', 'aqua', 'aquamarine']);
        $colors = ORDER_COLORS;
        shuffle($colors);
        foreach ($tour->route->stationsIds() as $item) {       // Заполняем матрицу мест по всем остановкам 
            $placesTable[$item] = array_fill(1, $tour->bus->places, 0);
            $colorsTable[$item] = array_fill(1, $tour->bus->places, 0);
        }
        foreach ($orders as $order) {
            $stationsOrder = $tour->route->stationsFromTo($order->station_from_id, $order->station_to_id)->pluck('id')->toArray();  // Список остановок текущей брони
            array_unshift($stationsOrder, $order->station_from_id);
            $orderColor = array_pop($colors);
            if (empty($colors)) {
                $colors = ORDER_COLORS;
                shuffle($colors);
            }
            foreach ($stationsOrder as $station_id) {
                foreach ($order->orderPlaces->pluck('number') as $number) {
                    if (isset($placesTable[$station_id][$number])) {
                        $placesTable[$station_id][$number] += 1;
                        $colorsTable[$station_id][$number] = $orderColor;
                    }
                }
            }
        }
        return ['html' => view('admin.tours.popups.information', compact('tour', 'placesTable', 'colorsTable', 'colors'))->render()];
    }

    public function buildRoute(Tour $tour)
    {
        $buildRoute = YandexRoutingService::build($tour);

        if ($buildRoute['result'] == 'success') {
            return view('index.partials.redirect', ['url' => $buildRoute['url'], 'seconds' => $buildRoute['delay']]);
        } else {
            echo $buildRoute['message'];
        }

    }

    public function buildTaxiRoute($order_id)
    {
        $order = Order::findOrFail($order_id);
        $url = GeoService::getRouteLink($order);
        if ($url) {
            return view('index.partials.redirect', ['url' => $url]);
        };

    }
}
