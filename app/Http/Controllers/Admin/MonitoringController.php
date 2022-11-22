<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\SelectRepository;
use App\Models\Schedule;
use App\Services\Monitoring\MonitoringService;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\MonitoringRequest;
use App\Models\Bus;
use App\Models\RouteStation;
use App\Models\Station;
use App\Models\MonitoringBus;
use App\Http\Controllers\Admin\Response;
use App\Models\MonitoringSetting;

class MonitoringController extends Controller
{
    protected $entity = 'monitoring';
    protected $select;
    protected $service;

    public function __construct(SelectRepository $selectRepository, MonitoringService $service)
    {
        $this->select = $selectRepository;
        $this->service = $service;
    }

    public function index()
    {
        $schedules = Schedule::filter(request()->except('routes') + ['routes' => auth()->user()->routeIds])
            ->with('bus', 'route')
            ->orderByRaw("DATE_FORMAT(date_start, '%H') ASC")
            ->paginate();
        $buses = $this->select->getBuses();
        $routes = $this->select->getRoutes(request('bus_id'));
        $times = $this->select->getTimes(request('bus_id'), request('route_id'));
        if(MonitoringSetting::first() != null) {
            $highSpeed = MonitoringSetting::all()->pluck('high_speed')->first();
        } else {
            $highSpeed = 100;
        }

        if (request()->ajax() && !request('_pjax')) return $this->ajaxView($buses, $routes, $schedules, $times, $highSpeed);
        return view('admin.' . $this->entity . '.index', compact('buses','routes','schedules', 'times', 'highSpeed') + ['entity' => $this->entity]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    protected function ajaxView($buses, $routes, $schedules, $times, $highSpeed)
    {
        return response([
            'view' => view('admin.' . $this->entity . '.index.filter', compact('buses', 'routes', 'schedules', 'times', 'highSpeed') + ['entity' => $this->entity])->render()
        ])->header('Cache-Control', 'no-cache, no-store');

        return response([
            'view' => view('admin.' . $this->entity . '.index.table', compact('schedules') + ['entity' => $this->entity])->render(),
            'pagination' => view('admin.partials.pagination', ['paginator' => $schedules])->render(),
        ])->header('Cache-Control', 'no-cache, no-store');
    }

    public function driverWaypoints()
    {
        $bus_id = request('bus_id');
        if(!empty($bus_id)) {
            return response()->json($this->service->busLocation($bus_id));
        }
    }

    public function routeWaypoints(Request $request)
    {
        $this->validate($request, [
            'route_id' => 'required|numeric',
        ]);
        $route_id = request('route_id');

        return response()->json($this->service->routePath($route_id));
    }

    public function driversWaypoints(Request $request)
    {
        $this->validate($request, [
            'route_id' => 'required|numeric',
        ]);
        $route_id = request('route_id');

        return response()->json($this->service->busesWaypoints($route_id));
    }

    // method not used
//    public function postAuthBus(Request $request) {
//        $busNumber = $request->get('bus_number');
//        $pass = $request->get('password');
//
//        if ($busNumber == null || $pass == null) {
//            echo "Введите все данные!";
//        }
//
//        $bus = Bus::query()->where('number', $busNumber)->first();
//
//        $busPass = $bus->password;
//        if (!password_verify($pass, $busPass)) {
//            echo "Неправильный пароль!";
//            return;
//        }
//    }

    public function postGeoAndSpeed(Request $request) {

        $busNumber = $request->get('bus_number');
        $latitude = $request->get('latitude');
        $longitude = $request->get('longitude');
        $speed = $request->get('speed');

        if ($busNumber == null  || $latitude == null || $longitude == null || $speed == null) {
            echo "Введите все данные!";
        }

        $bus = Bus::query()->where('number', $busNumber)->first();

        $busPass = $bus->password;

        $lastMonBus = MonitoringBus::query()->where('bus_id', $bus->id)->orderBy('updated_at', 'desc')->first();
        if($lastMonBus != null) {
            $lastTime = strtotime($lastMonBus->updated_at->toDateTimeString());
        }
        if($lastMonBus != null && time() - $lastTime < 60){
            echo "Слишком рано!";
            return;
        }

        $monBus = new MonitoringBus;

        $monBus->bus_id = $bus->id;
        $monBus->password = $busPass;
        echo $latitude . " "  . $longitude;
        $monBus->latitude = $latitude;
        $monBus->longitude = $longitude;
        $monBus->speed = $speed;
        $monBus->save();
    }

    function highSpeed(Request $request) {
        $highSpeed = $request->get('highSpeed');

        $settings = MonitoringSetting::first();
        $settings = $settings == null ? new MonitoringSetting() : $settings;
        $settings->high_speed = $highSpeed;

        $settings->save();
    }

    function busSpeed(Request $request) {
        $busId = $request->get('busId');

        $bus = MonitoringBus::where('bus_id', $busId)->first();
        return response()->json([
            'speed' => $bus ? $bus->speed * 3.6 : 0,
        ]);
    }
}
