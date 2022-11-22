<?php


namespace App\Services\Route;


use App\Models\Station;

class GetFromStation
{
    public static function index($route, $cityFrom)
    {
        $stationFrom = null;

        if ($route->stations->where('city_id', $cityFrom)->where('pivot.order', 0)->first()) {
            $stationFrom = $route->stations->where('city_id', $cityFrom)->where('status', Station::STATUS_ACTIVE)->first();
        } elseif ($stationFrom = $route->stations->where('city_id', $cityFrom)->where('pivot.central', true)->first()) {
          //nothing
        } else {
            $stationFrom = $route->stations->where('city_id', $cityFrom)->where('status', Station::STATUS_ACTIVE)->last();
        }
        return $stationFrom;
    }
}
