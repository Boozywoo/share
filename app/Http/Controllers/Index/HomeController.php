<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use App\Http\Requests\Index\HomeFormRequest;
use App\Http\Requests\Request;
use App\Models\Bus;
use App\Models\City;
use App\Models\Route;
use App\Models\Station;
use App\Models\Tour;
use App\Repositories\SelectRepository;
use App\Repositories\SelectRepositoryIndex;

class HomeController extends Controller
{
    protected $select;
    protected $selectIndex;

    public function __construct(SelectRepository $selectRepository)
    {
        $this->select = $selectRepository;
        $this->selectIndex = new SelectRepositoryIndex();
    }

    public function index(Tour $tour)
    {
        $routes = $this->selectIndex->routes();
        $cityIds = array();

        foreach ($routes as $route) {
            $cityIds += $route->stationsTickets->pluck('city_id', 'city_id')->toArray();
        }

        if (!count($routes)) abort(404, 'Отсутствуют маршруты!');
        $cities = City::whereIn('id', $cityIds)->orderBy('name')->get()->pluck('name', 'id');

        return view('index.home.index', compact('routes', 'cities'));
    }

    public function stations()
    {
        if ($routeId = request('route_id')) {
            $stations = $this->select->stationsActive($routeId, request('station_from_id'));
            $result = [];
            foreach ($stations as $stationId => $name) {
                $result[] = [
                    'station_id' => $stationId,
                    'name' => $name,
                ];
            }

            return $this->responseSuccess(['stations' => $result]);
        }
        return $this->responseError();
    }

    public function cities(Tour $tour)
    {
        $cities = $tour->getFromCityIdTo(request('city_from_id'), '', 'cities');
        if (is_array($cities)) {
            $dataCities = City::whereIn('id', $cities)->orderBy('name')->get();
            foreach ($cities as $key => $city) {
                $name = $dataCities->where('id', $city)->first()->name;
                $cities[$key] = ["id" => $key, 'name' => $name];
            }
            return $cities;
        }
    }

    public function cityStations()
    {
        $response = array();
        $stations = Station::where('city_id', request('city_id'))->get();

        if (isset($stations) && is_array($stations->toArray())) {
            foreach ($stations as $key => $station)
                $response[] = ["id" => $station->id, 'name' => $station->name];
            return $response;
        }
    }

    public function getRoute(Tour $tour, Route $route)
    {
        $from_city_id = request('from_city_id');
        $to_city_id = request('to_city_id');
        $routes = $tour->getFromCityIdTo($from_city_id, $to_city_id);
        $FromStations = [];
        $ToStations = [];
        if (!empty($routes)) {
            $route_id = reset($routes);
            $stations = Route::find($route_id)->stations;
            foreach ($stations as $station)
                if ($station->city_id == $from_city_id && $station->status == 'active')
                    $FromStations[] = ['id' => $station->id, 'name' => $station->name];
                elseif ($station->city_id == $to_city_id && $station->status == 'active')
                    $ToStations[] = ['id' => $station->id, 'name' => $station->name];
            $response = ['from_stations' => $FromStations, 'to_stations' => $ToStations, 'route_id' => $route_id];
            return $response;
        }
        return false;
    }

    public function test()
    {
        $bus = Bus::find(36);
        return view('index.bus.bus', compact('bus'));
    }

    public function getImages() {
        $hour = ltrim(date('H'), '0');
        $time = ($hour < 20 && $hour >= 8) ? 'am' : 'pm';

        $allImages = [];
        $images = \File::allFiles(public_path('assets/index/images/for_clients/' . $time)) ?? null;
        if ($images) {
            $image = $images[rand(0, count($images) - 1)];
            array_push($allImages, '/' . $time . '/' . $image->getFilename());
        }
        
        $mobileImages = \File::allFiles(public_path('assets/index/images/for_clients/mobile')) ?? null;
        if ($mobileImages)  {
            $mobileImage = $mobileImages[rand(0, count($mobileImages) - 1)];
            array_push($allImages, '/' . 'mobile' . '/' . $mobileImage->getFilename());
        }

        return $allImages;
    }

}