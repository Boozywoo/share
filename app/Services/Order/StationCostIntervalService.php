<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/30/2017
 * Time: 12:59 AM
 */

namespace App\Services\Order;
use App\Models\Station;
use Log;

class StationCostIntervalService
{
    public static function index($tour, $stationFromId, $stationToId)
    {
        if ($tour->route->is_line_price && !$tour->route->is_route_taxi) {
            $costStart      = isset($tour->route->stations->find($stationFromId)->pivot->cost_start) ?
                $tour->route->stations->find($stationFromId)->pivot->cost_start : 0;
            $costFinish     = isset($tour->route->stations->find($stationToId)->pivot->cost_finish) ?
                $tour->route->stations->find($stationToId)->pivot->cost_finish : 0;
            return $tour->price + $costStart + $costFinish;
        } else {
            $item = \DB::table('route_station_price')
                ->where('route_id',$tour->route->id)
                ->where('station_from_id', $stationFromId)
                ->where('station_to_id', $stationToId)
                ->first();
            return $item ? $item->price ?? 0: 0;
        }
    }
}