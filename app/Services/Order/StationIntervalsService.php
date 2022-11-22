<?php

namespace App\Services\Order;

use App\Models\Route;
use App\Models\Station;
use App\Models\City;
use Carbon\Carbon;

class StationIntervalsService
{
    public static function index($routeId, $stationFromId, $stationToId)
    {
        $stationFromInterval = Station::filter([
            'route_id' => $routeId,
            'id' => $stationFromId
        ])
            ->with(['routes' => function ($q) use ($routeId) {
                $q->whereId($routeId);
            }])
            ->first();

        $stationFromInterval = isset($stationFromInterval) ?
            $stationFromInterval->routes->first()->pivot->interval : 0;

        $stationToInterval = Station::filter([
            'route_id' => $routeId,
            'id' => $stationToId
        ])
            ->with(['routes' => function ($q) use ($routeId) {
                $q->whereId($routeId);
            }])
            ->first();

        $stationToInterval = isset($stationToInterval) ?
            $stationToInterval->routes->first()->pivot->interval : 0;

        return [$stationFromInterval, $stationToInterval];
    }

    public static function order($order)
    {
        return self::index($order->tour->route->id, $order->stationFrom->id, $order->stationTo->id);
    }

    public static function getDepartureDateTimeFromStation ($tour, $stationId, $format = 'H:i')
    {
        $route = Route::find($tour->route_id);
        $stationTourStart = Station::find($route->stations->first()->id);
        $cityTourStart = City::find( $stationTourStart->city_id);

        $dateTourStart = new Carbon($tour->prettyDateStart.' '.$tour->time_start, $cityTourStart->timezone);

        $station = Station::find($stationId);
        $cityCurrent = City::find($station->city_id);
        $routeId = $tour->route_id;

        $stationInterval = Station::filter([
            'route_id' => $routeId,
            'id' => $stationId
        ])
        ->with(['routes' => function ($q) use ($routeId) {
            $q->whereId($routeId);
        }])
        ->first();

        $currTime = new Carbon($tour->prettyDateStart.' '.$tour->time_start, $cityCurrent->timezone);
        $difference = $dateTourStart->diffInMinutes($currTime, false);      //  Разница во времени между пунктом отправления и текущей остановкой
        if ($difference) {
            $dateTourStart->subMinutes($difference);
        }

        $stationInterval = isset($stationInterval) ? $stationInterval->routes->first()->pivot->interval : 0;

        $dateTourStart->addMinutes($stationInterval);


        return $dateTourStart->format($format);
    }

}
