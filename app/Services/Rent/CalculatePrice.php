<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 012 12.08.18
 * Time: 16:36
 */

namespace App\Services\Rent;


use App\Models\BusRent;
use App\Models\Tour;
use Carbon\Carbon;

class CalculatePrice
{
    public static function index(Tour $tour)
    {
        if ($tour->bus_id) {
            $hours = Carbon::createFromTimestamp(strtotime($tour->date_time_start))->diffInHours(Carbon::createFromTimestamp(strtotime($tour->date_time_finish)));
            $tariff = BusRent::where('bus_id', $tour->bus_id)
                ->where('from_hour', '<', $hours)
                ->where('to_hour', '>=', $hours)
                ->first();
            if (!$tariff && $tour->bus_id) $tariff = BusRent::where('bus_id', $tour->bus_id)->orderByDesc('to_hour')->first();
            $tour->price = isset($tariff->cost) ? $hours * $tariff->cost : 0;
        } else $tour->price = 0;

        $tour->save();
    }
}