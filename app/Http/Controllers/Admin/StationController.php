<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StationRequest;
use App\Models\Order;
use App\Models\Station;
use App\Models\Street;
use App\Models\Route;
use App\Repositories\SelectRepository;

class StationController extends Controller
{
    protected $entity = 'routes.stations';
    protected $select;

    public function __construct(SelectRepository $selectRepository)
    {
        $this->select = $selectRepository;
    }

    public function index()
    {
        //$this->clear();
        $stations = Station::filter(request()->all())
            ->orderBy('name')
            ->paginate();
        if (request()->ajax() && !request('_pjax')) return $this->ajaxView($stations);
        $cities = $this->select->cities(true);

        return view('admin.' . $this->entity . '.index', compact('stations', 'cities') + ['entity' => $this->entity]);
    }

    public function create()
    {
        $station = new Station();
        $cities = $this->select->cities(true);
        $streets = $this->select->streets(['city_id' => key($cities->toArray())]);
        $stations = Station::where('id', $station->id)->get(['id', 'name','latitude', 'longitude']);

        $data = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];
    
        foreach ($stations as $key => $station) {
            $data['features'][$key] = [
                'type' => 'Feature',
                'id' => $key,
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [floatval($station->latitude), floatval($station->longitude)],
                ],
                'properties' => [
                    'balloonContentHeader' => $station->name,
                ],
            ];
        }

        $data = json_encode($data);

        return view('admin.' . $this->entity . '.edit', compact('station', 'cities', 'streets', 'data') + ['entity' => $this->entity]);
    }

    public function edit(Station $station)
    {
        $cities = $this->select->cities(true);
        $streets = $this->select->streets(['city_id' => $station->city_id]);
        $stations = Station::get(['id', 'name','latitude', 'longitude']);

        $data = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];
    
        foreach ($stations as $key => $stat) {
            $data['features'][$key] = [
                'type' => 'Feature',
                'id' => $key,
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [floatval($stat->latitude), floatval($stat->longitude)],
                ],
                'properties' => [
                    'balloonContentHeader' => $stat->name,
                ],
            ];
        }

        $data = json_encode($data);

        return view('admin.' . $this->entity . '.edit', compact('station', 'cities', 'streets', 'data') + ['entity' => $this->entity]);
    }

    public function copy(Station $station)
    {
        $station->id = 0;
        $cities = $this->select->cities(true);

        $streets = $this->select->streets(['city_id' => $station->city_id]);

        $stations = Station::get(['id', 'name','latitude', 'longitude']);

        $data = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];
    
        foreach ($stations as $key => $stat) {
            $data['features'][$key] = [
                'type' => 'Feature',
                'id' => $key,
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [floatval($stat->latitude), floatval($stat->longitude)],
                ],
                'properties' => [
                    'balloonContentHeader' => $stat->name,
                ],
            ];
        }

        $data = json_encode($data);
        return view('admin.' . $this->entity . '.edit', compact('station', 'cities', 'streets', 'data') + ['entity' => $this->entity]);
    }

    public function store(StationRequest $request)
    {
        if ($id = request('id')) {
            $station = Station::find($id);
            if ($station->routes->count() && request('status') == Station::STATUS_DISABLE) {
                return $this->responseError(['message' => trans('messages.admin.stations.statuses.disabled')]);
            }
            $station->update(request()->all());
        } else {
            $station = Station::create(request()->all());
        }

        return $this->responseSuccess();
    }

    protected function ajaxView($stations)
    {
        return response([
            'view' => view('admin.' . $this->entity . '.index.table', compact('stations') + ['entity' => $this->entity])->render(),
            'pagination' => view('admin.partials.pagination', ['paginator' => $stations])->render(),
        ])->header('Cache-Control', 'no-cache, no-store');
    }

    protected function jsonData()
    {
        if (request('route_id')) {
            $route = Route::find(request('route_id'));
            return $route->stations()->where('street_id', request('street_id'))->get();
        }

        $street = Street::find(request('street_id'));
        return $street ? $street->stations()->get()->toArray() : [];
    }

    public function clear()
    {
        $routes = Route::where('is_taxi', true)->get();
        $stationsBusy = [];
        foreach ($routes as $route) {
            $stations = $route->stations->where('status', Station::STATUS_COLLECT);
            foreach ($stations as $station) {
                $orderFrom = Order::with('tour')
                    ->where('station_from_id', $station->id)
                    ->whereHas('tour', function ($q) {
                        $q->where('date_start', '>=', date('Y-m-d'));
                    })
                    ->get();

                $orderTo = Order::with('tour')
                    ->where('station_to_id', $station->id)
                    ->whereHas('tour', function ($q) {
                        $q->where('date_start', '>=', date('Y-m-d'));
                    })
                    ->get();

                if (!$orderFrom->count() && !$orderTo->count())
                    \DB::table('route_station')
                        ->where('route_id', $route->id)
                        ->where('station_id', $station->id)
                        ->delete();// удалить остановку из направления
                   // Station::where('id', $station->id)->delete(); удалить неавктивные остановки
                else $stationsBusy[] = $station->id;
            }
        }

        //удалить остановки в статусе "СБОР" которые не в направлениях
        /*Station::whereNotIn('id', $stationsBusy)
            ->where('status', Station::STATUS_COLLECT)
            ->delete();*/
    }
}