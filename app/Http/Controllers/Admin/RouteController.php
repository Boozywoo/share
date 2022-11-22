<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RouteRequest;
use App\Jobs\Client\ClientImportJob;
use App\Jobs\Route\RouteImportJob;
use App\Models\Bus;
use App\Models\Client;
use App\Models\Currency;
use App\Models\Order;
use App\Models\Route;
use App\Models\Sale;
use App\Models\Station;
use App\Models\RouteStationPrice;
use App\Models\Schedule;
use App\Models\Setting;
use App\Models\Tour;
use App\Models\User;
use App\Repositories\SelectRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    protected $entity = 'routes';
    protected $select;
    protected $user;

    public function __construct(SelectRepository $selectRepository, User $user)
    {
        $this->user = $user;
        $this->select = $selectRepository;
    }

    public function index()
    {

        $routes = Route::filter(request()->except('user_id') + ['user_id' => auth()->id()])
            ->with('stations', 'stations.city', 'stations.street', 'stationsActive')
            ->orderBy('position')
            ->paginate();

        if (request()->ajax() && !request('_pjax')) return $this->ajaxView($routes);
        return view('admin.' . $this->entity . '.index', compact('routes') + ['entity' => $this->entity]);
    }

    public function create()
    {
        $route = new Route();
        $stations = $this->select->cityWithStation();
        $route->required_inputs = explode(',', $route->required_inputs);
        $sales = Sale::query()
            ->where('date_start', '<=', Carbon::now()->format('y-m-d'))
            ->where('date_finish', '>=', Carbon::now()->format('y-m-d'))
            ->where('status', Sale::STATUS_ACTIVE)
            ->get()
            ->reduce(static function ($result, $value) {
                $value->name .= ' (' . trans('admin.settings.sales.types.' . $value->type) . ')';
                $result[$value->id] = [$value->name, $value->type];
                return $result;
            }, []);
        $salesIds = [];

        $currencies = Currency::all('id', 'name')->pluck('name', 'id');
        $phoneCodes = \App\Models\Client::CODE_PHONES;

        return view('admin.' . $this->entity . '.edit', compact('route', 'stations', 'sales', 'salesIds', 'currencies', 'phoneCodes')
            + ['entity' => $this->entity]
            + ['user_fillable' => array_add($this->user->getFieldsByName(), 'flight_number', 'Номер рейса')]);
    }

    public function edit(Route $route)
    {
        $this->authorize('route-id', $route->id);
        $isCollect = $route->is_taxi ? false : true;
        $stations = $this->select->cityWithStation(null, null, null, null, $isCollect, false);
        $route->required_inputs = explode(',', $route->required_inputs);
        $sales = Sale::query()
            ->where('date_start', '<=', Carbon::now()->format('y-m-d'))
            ->where('date_finish', '>=', Carbon::now()->format('y-m-d'))
            ->where('status', Sale::STATUS_ACTIVE)
            ->get()
            ->reduce(static function ($result, $value) {
                $value->name .= ' (' . trans('admin.settings.sales.types.' . $value->type) . ')';
                $result[$value->id] = [$value->name, $value->type];

                return $result;
            }, []);
        $salesIds = $route->sales->count() ? $route->sales->pluck('id')->toArray() : [];
        $currencies = Currency::all('id', 'name')->pluck('name', 'id');
        $phoneCodes = \App\Models\Client::CODE_PHONES;
        $this->user->client = new Client();

        return view('admin.' . $this->entity . '.edit', compact('route', 'stations', 'sales', 'salesIds', 'currencies', 'phoneCodes')
            + ['entity' => $this->entity]
            + ['user_fillable' => array_add($this->user->client->getFieldsByName(), 'flight_number', 'Номер рейса')]);
    }

    public function store(RouteRequest $request)
    {
        $type = $request->input('type');
        $types = ['is_regular', 'is_taxi', 'is_route_taxi','is_transfer'];
        foreach ($types as $item)   {
            $request->request->set($item, $type == $item);
        }

        if(!request('stations')) {
            return ['message' => 'Добавьте остановки!', 'type' => 'error'];
        } 

        if ($id = request('id')) {
            $route = Route::find($id);
            $this->authorize('route-id', $route->id);
            $route->update(Route::prepareData($request->all()));
        } else {
            $route = Route::create(Route::prepareData($request->all()));
        }

        $interval = 0;
        $stationsSync = [];

        foreach (request('stations', []) as $order => $station) {
            if (!isset($station['time'])) continue;
            $interval += empty($station['time']) ? 0 : $station['time'];
            $stationsSync += [$station['station_id'] => [
                'order' => $order,
                'time' => $station['time'],
                'interval' => $interval,
                'cost_start' => isset($station['cost_start']) ? $station['cost_start'] : 0,
                'cost_finish' => isset($station['cost_finish']) ? $station['cost_finish'] : 0,
                'central' => isset($station['central']) ? $station['central'] : false,
                'tickets_from' => isset($station['tickets_from']) ? $station['tickets_from'] : false,
                'tickets_to' => isset($station['tickets_to']) ? $station['tickets_to'] : false,
            ]];
        }

        if ($request->has('sales')) {
            $route->sales()->sync($request->get('sales'));
        } else {
            $route->sales()->sync([]);
        }

        $route->stations()->sync($stationsSync);
        $route->update(compact('interval'));

        return $this->responseSuccess();
    }

    public function prices(Route $route)
    {
        $data = \DB::table('route_station_price')->where('route_id', $route->id)->get();
        $route = Route::whereId($route->id)->with('stations.city')->first();
        return view('admin.' . $this->entity . '.prices', compact('route', 'data') + ['entity' => $this->entity]);
    }

    public function intervals(Route $route)     // Для режима маршрутного такси - интервалы в минутах между остановкам, берем готовый шаблон от цен между остановками
    {
        $data = \DB::table('route_station_price')->where('route_id', $route->id)->get();
        return view('admin.' . $this->entity . '.prices', compact('route', 'data') + ['entity' => $this->entity, 'interval' => true]);
    }

    public function info(Route $route)
    {
        $this->authorize('route-id', $route->id);
        return $this->responseSuccess(['data' => $route]);
    }

    public function statics(Route $route)
    {
        $companies = $this->select->companies(auth()->id());
        if (!$dateFrom = request('date_from')) $dateFrom = Carbon::now()->subMonths(1)->format('Y-m-d');
        if (!$dateTo = request('date_to')) $dateTo = Carbon::now()->addDay()->format('Y-m-d');

        $buses = Bus::filter(request()->except('companies') + ['companies' => auth()->user()->companyIds])
            ->with(['company', 'repairs' => function ($q) use ($dateFrom, $dateTo) {
                $q->filter(['between_date_from' => ['dateFrom' => $dateFrom, 'dateTo' => $dateTo]]);
            }])
            ->latest()
            ->get();

        foreach ($buses as $bus) {
            $bus->routes = Route::with(['tours' => function ($q) use ($bus, $dateFrom, $dateTo) {
                $q->filter([
                    'bus_id' => $bus->id,
                    'between' => ['dateFrom' => $dateFrom, 'dateTo' => $dateTo]
                ]);
            }])->where('id', $route->id)->get();
        }

        /*$orders = Order::whereHas('tour', function ($query) use ($dateFrom, $dateTo) {
            $query->whereBetween('date_start', [$dateFrom, $dateTo]);
        })->get();*/

        if (request()->ajax() && !request('_pjax')) {
            return response([
                'view' => view('admin.' . $this->entity . '.statics.table', compact('buses') + ['entity' => $this->entity])->render(),
            ])->header('Cache-Control', 'no-cache, no-store');
        }
        return view('admin.' . $this->entity . '.statics', compact('buses', 'companies', 'route') + ['entity' => $this->entity]);
    }

    protected function ajaxView($routes)
    {
        return response([
            'view' => view('admin.' . $this->entity . '.index.table', compact('routes') + ['entity' => $this->entity])->render(),
            'pagination' => view('admin.partials.pagination', ['paginator' => $routes])->render(),
        ])->header('Cache-Control', 'no-cache, no-store');
    }

    public function sort()
    {
        $routes = Route::filter(request()->except('user_id') + ['user_id' => auth()->id()])
            ->with('stations')
            ->orderBy('position')
            ->get();
        return view('admin.routes.sort', compact('routes') + ['entity' => $this->entity]);
    }

    public function sortSave(Request $request)
    {
        foreach ($request->routes as $key => $route_id) {
            $route = Route::findOrFail($route_id);
            $route->update(['position' => $key]);
        }
        return $this->responseSuccess(['status' => 'success', 'message' => 'Направления успешно отсортированы']);
    }

    public function import()
    {
        $fileName = 'routes.xlsx';
        request()->file('file')->storeAs('/app', $fileName);
        dispatch(new RouteImportJob($fileName));
        return $this->responseSuccess(['message' => 'Направления загружаются']);
    }

    public function setUserPopup(Route $route)
    {
        $users = User::where('client_id', NULL)->get();
        $checked = $route->users()->get()->pluck('id', 'id');

        return ['html' => view('admin.routes.popups.content', compact('route', 'checked', 'users') + ['entity' => $this->entity])->render()];
    }

    public function setUser(Route $route)
    {
        $users = request('users');
        $route->users()->detach();
        if($users) {
            foreach($users as $u) {
                $user = User::find($u);
                $user->routes()->syncWithoutDetaching($route->id);
            }
        }

        return $this->responseSuccess();
    }

    public function storeStationPrice()
    {
        try {
            $routeId = \request()->get('route_id');
            $stationFromId = \request()->get('station_from_id');
            $stationToId = \request()->get('station_to_id');
            if ($routeId && $stationFromId && $stationToId) {
                RouteStationPrice::updateOrCreate(['station_from_id' => $stationFromId,'station_to_id' => $stationToId, 
                    'route_id' => $routeId], ['price' => request()->get('price'), 'station_from_id' => $stationFromId, 
                    'station_to_id' => $stationToId, 'route_id' => $routeId]);
                return ['message' => 'Данные успешно обновлены', 'type' => 'success'];
            }
        } catch (\Exception $e) {
            return ['message' => $e->getMessage(), 'type' => 'error'];
        }
    }

    public function storeAllStationPrice()
    {
        try {
            $routeId = request()->get('route_id');
            $route = Route::find($routeId);
            if ($routeId) {
                $prices = RouteStationPrice::where('route_id', $routeId)->get();
                foreach($route->stations as $keyFrom => $stationFrom) {
                    foreach($route->stations as $keyTo => $stationTo) {
                        if ($stationFrom->id != $stationTo->id && ($keyFrom < $keyTo || $route->is_route_taxi)) {
                            RouteStationPrice::updateOrCreate(['station_from_id' => $stationFrom->id,'station_to_id' => $stationTo->id, 
                            'route_id' => $route->id], ['price' => request()->get('price'), 'station_from_id' => $stationFrom->id, 
                            'station_to_id' => $stationTo->id, 'route_id' => $route->id]);
                        }
                    }
                }
                foreach ($prices as $route_price) {
                    $route_price->update(['price' => \request()->get('price')]);
                }
                    
                return ['message' => 'Данные успешно обновлены', 'type' => 'success'];
            }
        } catch (\Exception $e) {
            return ['message' => $e->getMessage(), 'type' => 'error'];
        }
    }

    public function storeFromStationPrice()
    {
        try {
            $route = Route::find(request()->get('route_id'));
            $stationFrom = Station::find(request()->get('station_from_id'));

            $is_found = false;
            $sell = RouteStationPrice::where('route_id', $route->id)->where('station_from_id', $stationFrom->id)
            ->where('station_to_id', request()->get('station_to_id'))->first();
            if($route) {
                foreach($route->stations as $stationTo) {
                    if ($stationFrom->id != $stationTo->id) {
                        $item = RouteStationPrice::where('route_id', $route->id)->where('station_from_id', $stationFrom->id)
                            ->where('station_to_id', $stationTo->id)->first();
                        $is_found = $sell == $item ? true : $is_found;
                        
                        if($is_found) {
                            RouteStationPrice::updateOrCreate(['station_from_id' => $stationFrom->id,'station_to_id' => $stationTo->id, 
                            'route_id' => $route->id], ['price' => request()->get('price'), 'station_from_id' => $stationFrom->id, 
                            'station_to_id' => $stationTo->id, 'route_id' => $route->id]);                            
                        }
                    }
                }
            } 
            return ['message' => 'Данные успешно обновлены', 'type' => 'success'];
        } catch (\Exception $e) {
            return ['message' => $e->getMessage(), 'type' => 'error'];
        }
    }

    public function storeToStationPrice()
    {
        try {
            $route = Route::find(request()->get('route_id'));
            $stationTo = Station::find(request()->get('station_to_id'));

            $is_found = false;
            $sell = RouteStationPrice::where('route_id', $route->id)->where('station_to_id', $stationTo->id)
                ->where('station_from_id', request()->get('station_from_id'))->first();
            
            if($route) {
                foreach($route->stations as $stationFrom) {
                    if ($stationFrom->id != $stationTo->id) {
                        $item = RouteStationPrice::where('route_id', $route->id)->where('station_to_id', $stationTo->id)
                            ->where('station_from_id', $stationFrom->id)->first();
                        $is_found = $sell == $item ? true : $is_found;

                        if($is_found) {
                            RouteStationPrice::updateOrCreate(['station_from_id' => $stationFrom->id,'station_to_id' => $stationTo->id, 
                            'route_id' => $route->id], ['price' => request()->get('price'), 'station_from_id' => $stationFrom->id, 
                            'station_to_id' => $stationTo->id, 'route_id' => $route->id]);                            
                        }
                    } else {
                        break;
                    }
                }
            }
            return ['message' => 'Данные успешно обновлены', 'type' => 'success'];
        } catch (\Exception $e) {
            return ['message' => $e->getMessage(), 'type' => 'error'];
        }
    }


    public function print_page_template_excel()
    {
        \Excel::create("[шаблон для импорта направлений] ", function ($excel) {
            $excel->sheet('Направление 1', function ($sheet) {

                $places[] = [
                    'Номер' => '',
                    'Город' => '',
                    'Остановка' => '',
                    'Улица' => '',
                    'Разница/мин' => '',
                    'Статус остановки' => '',

                ];
                $sheet->fromArray($places);
            });
            $excel->sheet('Направление 2', function ($sheet) {

                $places[] = [
                    'Номер' => '',
                    'Город' => '',
                    'Остановка' => '',
                    'Улица' => '',
                    'Разница/мин' => '',
                    'Статус остановки' => '',
                ];
                $sheet->fromArray($places);
            });
        })->export('xlsx');
    }

    public function sendEgis()
    {
        $egisRoutes = Route::filter(['user_id' => auth()->id(), 'status' => Route::STATUS_ACTIVE, 'is_egis' => true])->get()->pluck('id')->toArray();

        $stations = Station::with('city')->whereHas('routeStations', function ($q) use ($egisRoutes) {
            $q->whereIn('route_id', $egisRoutes);
        })->get();

        $created = Carbon::now();
        $egisId = env('EGIS_ID');
        $fname = $egisId.'_'.$created->format('Y_m_d_H_i_s').'_00';
        $path = storage_path('app/egis/stations/');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $doc = <<<EOT
<?xml version="1.0" encoding="utf-8"?>
<imp:Import xsi:type="imp:FullImport" createdAt="{$created->format('c')}"
	dataType="DESTINATION" recordCount="{$stations->count()}" transportSegment="AUTO"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:dt="http://www.egis-otb.ru/datatypes/"
	xmlns:imp="http://www.egis-otb.ru/gtimport/" xmlns:onsi-stat="http://www.egis-otb.ru/data/onsi/stations/"
	xsi:schemaLocation="http://www.egis-otb.ru/gtimport/ ru.egisotb.import.xsd http://www.egis-otb.ru/data/onsi/stations/ ru.egisotb.data.onsi.stations.xsd
	http://www.egis-otb.ru/datatypes/ ru.egisotb.datatypes.xsd
	"> 
EOT;

        foreach ($stations as $item)    {
            $doc .= PHP_EOL.<<<EOT
	<entry sourceId="{$item->id}" xsi:type="imp:ImportedEntry">
		<data name="{$item->city->name}" xsi:type="onsi-stat:AutoStation">
			<actualPeriod from="{$created->format('c')}" to="{$created->copy()->addYear(3)->format('c')}" xsi:type="dt:ImportDateTimePeriod"/>
			<countryCode value="Российская Федерация" xsi:type="dt:SimpleDictionaryValue" />
			<okato value="{$item->okato_id}" xsi:type="dt:SimpleDictionaryValue" />
		</data>
	</entry>
EOT;
        }
        $doc .= PHP_EOL.'</imp:Import>';
        file_put_contents($path.$fname.'0.xml', $doc);
        try {
            Storage::disk('ftp_egis_stations')->put('/Destination/FULL/'.$fname.'0.xml', $doc);     // Файл остановок - загрузка на FTP
        } catch (\Exception $e) {
            return $this->responseError(['message' => $e->getMessage()]);
        }

        $schedules = Schedule::whereIn('route_id', $egisRoutes)
            ->whereDate('date_finish', '>=', date('Y-m-d'))
            ->with('route', 'route.stationsActive', 'route.stationsActive.city')
            ->whereStatus(Schedule::STATUS_ACTIVE)->get();

        $singleTours = Tour::whereIn('route_id', $egisRoutes)       // Одиночные рейсы
            ->whereNull('schedule_id')
            ->whereDate('date_finish', '>=', date('Y-m-d'))
            ->with('route', 'route.stationsActive', 'route.stationsActive.city')
            ->whereStatus(Tour::STATUS_ACTIVE)->get();

        $changedTours = Tour::whereIn('route_id', $egisRoutes)      //
            ->whereHas('schedule', function ($q) {
                $q->whereRaw('tours.time_start != cast(schedules.date_start as time)');
            })
            ->whereDate('date_finish', '>=', date('Y-m-d'))
            ->with('route', 'route.stationsActive', 'route.stationsActive.city')
            ->whereStatus(Tour::STATUS_ACTIVE)->get();
        $singleTours = $singleTours->toBase()->merge($changedTours);

        foreach ($singleTours as $item) {               // Готовим файл расписаний
            $item->setRawAttributes(['date_start' => Carbon::parse($item->date_time_start)]);        // В поле date_start рейса храниться только дата, а в одноименном поле расписания дата со временем
            $item->daysMask = substr_replace('0000000', '1', $item->date_start->dayOfWeekIso-1, 1);
        }

        $schedules = $schedules->toBase()->merge($singleTours);
        $doc = <<<EOT
<?xml version="1.0" encoding="utf-8"?>
<imp:Import createdAt="{$created->format('c')}" dataType="TIMETABLE_PLAN" recordCount="{$schedules->count()}" transportSegment="AUTO" xmlns:dt="http://www.egis-otb.ru/datatypes/" xmlns:imp="http://www.egis-otb.ru/gtimport/" xmlns:tt="http://www.egis-otb.ru/data/timetable/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.egis-otb.ru/gtimport/ ru.egisotb.import.xsd http://www.egis-otb.ru/data/timetable/ ru.egisotb.data.timetable.xsd" xsi:type="imp:FullImport">
EOT;
        foreach ($schedules as $schedule) {
            $firstCity = $schedule->route->stationsActive->first()->city->name;
            $lastCity = $schedule->route->stationsActive->last()->city->name;
            $firstCityTZ = $schedule->route->stationsActive->first()->city->UTCOffset;
            $doc .= PHP_EOL.<<<EOT
  <entry sourceId="{$schedule->getOriginal('id')}" xsi:type="imp:ImportedEntry">
    <data  xsi:type="tt:CalendarTimetable">
      <actualPeriod from="{$schedule->date_start->format('Y-m-d\TH:i:s')}{$firstCityTZ}" xsi:type="dt:ImportDateTimePeriod"/>
      <operator value="{$egisId}" />
      <route routeName="{$firstCity} - {$lastCity}" xsi:type="tt:RouteHead">
EOT;
            foreach ($schedule->route->stationsActive as $key => $item) {
                $doc .= "\n\n        <routePoint departTime=\"".$schedule->date_start->copy()->addMinutes($item->getOriginal('pivot_interval'))->format('Y-m-d\TH:i:s').$firstCityTZ.'" pathIndex="'.$key.'" timeFromStart="'.$item->getOriginal('pivot_interval').'" xsi:type="tt:ImportRoutePoint">';
                $doc .= "\n".'          <station value="'.$item->city->name.'" xsi:type="dt:SimpleDictionaryValue"/>'."\n        </routePoint>";
            }
            $doc .= PHP_EOL.PHP_EOL.<<<EOT
        <routeEnd value="{$lastCity}" xsi:type="dt:SimpleDictionaryValue"/>
        <routeStart value="{$firstCity}" xsi:type="dt:SimpleDictionaryValue"/>
      </route>
      <calendar xsi:type="tt:WeekCalendar" daymask="{$schedule->daysMask}"/>
    </data>
  </entry>

EOT;
        }

        $doc .= PHP_EOL.'</imp:Import>';
        $path = storage_path('app/egis/schedule/');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        file_put_contents($path.$fname.'1.xml', $doc);
        try {
            Storage::disk('ftp_egis_schedules')->put('/FULL/' . $fname.'1.xml', $doc);    // Файл расписаний - пробуем загрузить на FTP
        } catch (\Exception $e) {
            return $this->responseError(['message' => $e->getMessage()]);
        }
        $settings = Setting::first();
        $settings->update(['egis_status' => 'sent', 'egis_file' => $fname, 'egis_answer' => '']);

        return $this->responseSuccess(['message' => 'Данные отправлены в ЕГИС (ФГУП «ЗащитаИнфоТранс»)']);
    }

    public function egisStatus()
    {   $settings = Setting::first();
        if (!$settings->egis_status) {
            return $this->responseError(['message' => 'Данные в ЕГИС еще не отправлялись.']);
        }
        if ($settings->egis_status == 'sent') {
            if (Storage::disk('ftp_egis_feedback')->exists('/RD_AUTO_' . $settings->egis_file . '0.xml.ack')) {
                $statusFile = Storage::disk('ftp_egis_feedback')->get('/RD_AUTO_' . $settings->egis_file . '0.xml.ack');     // Забираем результат проверки справочника остановочных пунктов
                file_put_contents(storage_path('app/egis/stations/RD_AUTO_' . $settings->egis_file . '0.xml.ack'), $statusFile);
                if (strpos($statusFile, 'errCode="0"')) {
                    $settings->update(['egis_status' => 'success', 'egis_answer' => 'Данные успешно приняты системой ЕГИС (ФГУП «ЗащитаИнфоТранс»)']);
                } else {
                    $errorStart = strpos($statusFile, '<fault description="') + 20;
                    $errorLast = strpos($statusFile, '"', $errorStart);
                    $settings->update(['egis_status' => 'error',
                        'egis_answer' => substr($statusFile, $errorStart, $errorLast - $errorStart)]);
                    return $this->responseError(['message' => 'Данные не приняты ЕГИС. Ошибка: ' . $settings->egis_answer]);
                }
            };
            if (Storage::disk('ftp_egis_feedback')->exists('/TT_AUTO_' . $settings->egis_file . '1.xml.ack')) {
                $statusFile = Storage::disk('ftp_egis_feedback')->get('/TT_AUTO_' . $settings->egis_file . '1.xml.ack');     // Забираем результат проверки
                file_put_contents(storage_path('app/egis/schedule/TT_AUTO_' . $settings->egis_file . '1.xml.ack'), $statusFile);
                if (strpos($statusFile, 'errCode="0"')) {
                    $settings->update(['egis_status' => 'success', 'egis_answer' => 'Данные успешно приняты системой ЕГИС (ФГУП «ЗащитаИнфоТранс»)']);
                } else {
                    $errorStart = strpos($statusFile, '<fault description="') + 20;
                    $errorLast = strpos($statusFile, '"', $errorStart);
                    $settings->update(['egis_status' => 'error',
                        'egis_answer' => substr($statusFile, $errorStart, $errorLast - $errorStart)]);
                    return $this->responseError(['message' => 'Данные не приняты ЕГИС. Ошибка: ' . $settings->egis_answer]);
                }
            }
            if (!$settings->egis_answer)    {
                return $this->responseError(['message' => 'Данные отправлены. Ответ от ЕГИС пока не получен.']);
            }
        }

        if ($settings->egis_status == 'success') {
            return $this->responseSuccess(['message' => $settings->egis_answer]);
        } else {
            return $this->responseError(['message' => $settings->egis_answer]);
        }
    }
}
