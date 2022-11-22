<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 025 25.01.19
 * Time: 18:57
 */

namespace App\Repositories;

use App\Models\Currency;
use App\Models\Route;
use App\Models\Tour;
use App\Services\Order\FragmentationOrder;
use Carbon\Carbon;

class SelectRepositoryIndex
{
    public function routes()
    {
       return Route::with('stationsActive')->where('status', Route::STATUS_ACTIVE)->get();
    }

    public function currencies()
    {
        $currencies = new Currency;
        $currencies = Currency::query();
//        return $currencies->get();
        return $currencies->pluck('alfa', 'id');
    }

    public function routeSchedules($cityFromId, $cityToId, Carbon $date = null)
    {
      $routes = Route::with('tours', 'stations')
        ->where('status', Route::STATUS_ACTIVE)
        ->whereHas('tours', function ($q) use ($date){
          //$q->where('date_start', $date->format('Y-m-d'));
          /*if (!$date->diffInDays(Carbon::now())) {
              $q->where('time_start','>', Carbon::now()->format('H:i:s'));
          }*/
        })
          ->whereHas('stations', function ($q) use ($cityFromId) {
              $q->where('city_id', $cityFromId)->where('tickets_from', true);
          })
          ->whereHas('stations', function ($q) use ($cityToId) {
              $q->where('city_id', $cityToId);
          })
          ->get();

        foreach ($routes as $key => $route) {
            if ($route->stations->where('city_id', $cityFromId)->first()->pivot->order > $route->stations->where('city_id', $cityToId)->last()->pivot->order) {
                unset($routes[$key]);
            }
            if ($route->stations->last()->city_id == $cityFromId) {
                unset($routes[$key]);
            }
        }

      return $routes;
    }

    public function freePlaces(Tour $tour, $stationFrom, $stationTo)
    {
        $freePlaces = FragmentationOrder::searchFreePlacesInterval($tour, [$stationFrom->pivot->interval, $stationTo->pivot->interval], 'count');
        return  $tour->bus->places - $tour->Reserved->groupBy('number')->count() + $freePlaces;
    }
}