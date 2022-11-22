<?php


namespace App\Services\Route;


class GetToStation
{
    public static function index($route, $cityTo)
    {
        if ($stationTo = $route->stations->where('city_id', $cityTo)->where('pivot.central', true)->first()) {
            //
        } else {
            $stationTo = $route->stations->where('city_id', $cityTo)->last();
        }
        //todo: временный хак, чтобы подвести время на фронте к времени в админ панели,
        // а вообще стоит лучше разобраться в формировании времени


//        if ($stationTo = $route->stations->where('city_id', $cityTo)->where('pivot.central', true)->first()) {
//            //nothing
//        } elseif ($route->stations->where('city_id', $cityTo)->last()->pivot->order == ($route->stations->count() - 1)) {
//            $stationTo = $route->stations->where('city_id', $cityTo)->last();
//        } else {
//            $stationTo = $route->stations->where('city_id', $cityTo)->first();
//        }
        return $stationTo;
    }
}
