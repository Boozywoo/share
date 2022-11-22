<?php
/**
 * Created by PhpStorm.
 * User: Dima
 * Date: 16.04.2018
 * Time: 23:24
 */

namespace App\Services\Station;
use App\Models\Route;
use App\Models\Station;
use App\Models\Street;
use App\Services\Geo\GeoService;
use Exception;

class CreateNewStationService
{
    public static function index($name, $city_id, $route_id)
    {
        $route = Route::find($route_id);
        $status = Station::STATUS_COLLECT;
        if ($route->is_taxi)    {
            $nameCut = str_replace(['Россия, ', 'Беларусь, ', 'Украина, '], '', $name);
            $status = Station::STATUS_TAXI;
        };

        if ($station = self::createStation($nameCut, $city_id, $status))
        {
            if ($route->is_taxi && $station->latitude == 0)    {
                list($station->longitude, $station->latitude) = GeoService::getCoordinates($name);
                $station->save();
            }
            $stations = $route->stations->toArray();
            $stationsSync = self::addStationRoute($station->id, $city_id, $stations);
            $route->stations()->sync($stationsSync);
            return $station->id;
        }
        return false;
    }

    public static function createStation($name, $city_id, $status)
    {
        if ($station = Station::where('name', $name)->first())
            return $station;
        elseif ( $street = Street::where('city_id', $city_id)->first())
        {
            return Station::create([
                'street_id' => $street->id,
                'city_id' => $city_id,
                'name' => $name,
                'name_tr' => $name,
                'status' => $status,
            ]);
        }
        else {
            $stationCity = Station::where('city_id', $city_id)->first();
            return Station::create([
                'street_id' => $stationCity->street_id,
                'city_id' => $city_id,
                'name' => $name,
                'name_tr' => $name,
                'status' => $status,
            ]);
        }
    }

    public static function addStationRoute($station_id, $city_id, $stations)
    {
        $syncStations = [];
        $order = 0;

        foreach ($stations as $key => $station)
        {
            $syncStations += [$station['id'] => [
                'order' => $order++,
                'time' => $station['pivot']['time'],
                'interval' => $station['pivot']['interval'],
                'cost_start' => $station['pivot']['cost_start'],
                'cost_finish' => $station['pivot']['cost_finish'],
            ]];
            if ($station['city_id'] == $city_id && $station['status'] == Station::STATUS_ACTIVE)
            {
                    $syncStations += [$station_id => [
                        'order' => $order++,
                        'time' => 0,
                        'interval' => 0,
                        'cost_start' => $station['pivot']['cost_start'],
                        'cost_finish' => $station['pivot']['cost_finish'],
                    ]
                ];
            }
        }
        return $syncStations;
    }

}