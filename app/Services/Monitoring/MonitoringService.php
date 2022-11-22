<?php

namespace App\Services\Monitoring;

use App\Models\MonitoringBus;
use App\Models\RouteStation;
use App\Models\Station;
use App\Models\Tour;

class MonitoringService
{
    protected $model;

    public function __construct(MonitoringBus $model){
        $this->model = $model;
    }

    public function busLocation($bus_id){
        $waypoints = MonitoringBus::getLocationByBusId($bus_id);

        return (object) [
            'bus_id' => $bus_id,
            'waypoints' => $waypoints,
            'bus_path' => $this->getBusPath($bus_id)];
    }

    public function routePath($route_id){
        $station_ids = RouteStation::whereRouteId($route_id)->pluck('station_id')->all();
        $buses = Tour::getTourThisMoment($route_id);

        $busLocations = [];
        foreach($buses as $bus){
            $busLocations[] = MonitoringBus::getLocationByBusId($bus)->first();
        }

        $routes =  Station::getRoutePath($station_ids, $route_id);

        return ['waypoints' => $routes, 'buses' => $busLocations];
    }

    public function busesWaypoints($route_id){
        $buses = Tour::getTourThisMoment($route_id);

        $busLocations = [];
        foreach($buses as $bus){
            $busLocations[] = MonitoringBus::getLocationByBusId($bus)->first();
        }

        return ['buses' => $busLocations];
    }

    protected function getBusPath($bus_id){
        $route_id = Tour::getBusTourAtThisMoment($bus_id);
        $station_ids = RouteStation::whereRouteId($route_id)->pluck('station_id')->all();
        return Station::getRoutePath($station_ids, $route_id);
    }
}