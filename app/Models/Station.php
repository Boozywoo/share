<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $fillable = [
        'name', 'name_tr', 'city_id', 'street_id', 'place_address', 'latitude', 'longitude', 'status', 'cost_start', 'cost_finish', 'okato_id'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_DISABLE = 'disable';
    const STATUS_COLLECT = 'collect';
    const STATUS_TAXI = 'taxi';

    const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_COLLECT,
        self::STATUS_DISABLE,
        self::STATUS_TAXI,
    ];

    //Relationships
    public function city()
    {
       return $this->belongsTo(City::class);
    }

    public function street()
    {
        return $this->belongsTo(Street::class);
    }

    public function stations()
    {
        return $this->belongsToMany(Station::class)
            ->withPivot('order', 'time', 'interval')
            ->orderBy('pivot_order');
    }

    public function routes()
    {
       return $this->belongsToMany(Route::class)
           ->withPivot('order', 'time', 'interval');
    }

    public function routeStations()
    {
        return $this->hasMany(RouteStation::class);
    }

    public function getAddressAttribute()
    {
        return $this->city->name.', '.$this->street->name;
    }

    public function getNameAndCityAttribute()
    {
        return $this->city->name.', '.$this->name;
    }
    public function getCoordinatesAttribute()
    {
        return $this->latitude.','.$this->longitude;
    }

    public function getClients($orders) {

        $os = [];

        foreach ($orders as $order) {
            if ((empty($order->appearance) && $order->station_from_id == $this->id && $order->status == 'active') ||
                (!empty($order->appearance) && $order->station_to_id == $this->id && $order->status == 'active')) {
                $os[] = $order;
            }
        }
        return $os;
    }

    public function getStationTime($orders) {

        $times = [];

        foreach ($orders as $order) {
            if ((empty($order->appearance) && $order->station_from_id == $this->id && $order->status == 'active'))  {
                array_push($times, $order->station_from_time);
            } elseif ((!empty($order->appearance) && $order->station_to_id == $this->id && $order->status == 'active'))  {
                array_push($times, $order->station_to_time);
            }
        }
        return $times[0];
    }

    public function getClientsCountFrom($orders, $app = null) {

        $count = [];
        if($app === null) {

            foreach ($orders as $order) {
                if (empty($order->appearance) && $order->station_from_id == $this->id && $order->status == 'active')  {
                    foreach($order->orderPlaces as $op) {
                        if($op->appearance !== 1) {
                            array_push($count, $order->slug);
                        }
                    }
                }
            }

            return $count;
        } elseif($app === false) {
            foreach ($orders as $order) {
                if (empty($order->appearance) && $order->station_from_id == $this->id && $order->status == 'active'&& $order->is_finished == 0)  {
                    foreach($order->orderPlaces as $op) {
                        if($op->appearance !== 1) {
                            array_push($count, 1);
                        }
                    }
                }
            }
        } else {
            foreach ($orders as $order) {
                if (empty($order->appearance) && $order->station_from_id == $this->id && $order->status == 'active'&& $order->is_finished == 0)  {
                    foreach($order->orderPlaces as $op) {
                        if($op->appearance !== 0) {
                            array_push($count, 1);
                        }
                    }
                }
            }
        }

        return  array_sum($count) ?? 0;
    }

    public function isFinishedAll($orders) {

        $is_all = true;

        if ($is_all) {

            foreach ($orders as $order) {
                if($order->station_to_id == $this->id && $order->status == 'active') {
                    if($order->is_finished == '0') {
                        $is_all = false;
                        break;
                    }
                }
            }

        }            
        return $is_all;
    }

    public function getClientsTo($orders) {

        $clients = [];

        foreach ($orders as $order) {
            if (!empty($order->appearance) && $order->station_to_id == $this->id && $order->status == 'active')  {
                foreach($order->orderPlaces as $op) {
                    if($op->appearance === 1) {
                        array_push($clients, $order->slug);
                    }
                }
            }
        }
        return $clients;
    }

    public function getClientsCountTo($orders) {
         
        $count = [];
         
            foreach ($orders as $order) {
                if ((!empty($order->appearance) && $order->station_to_id == $this->id && $order->status == 'active'))  {
                    foreach($order->orderPlaces as $op) {
                        if($op->appearance === 1) {
                            array_push($count, 1);
                        }
                    }
                }
            }

        return  array_sum($count); 
    }

    //Scopes
    public function scopeFilter($query, $data)
    {
        $id = array_get($data, 'id');
        $name = array_get($data, 'name');
        $cityId = array_get($data, 'city_id');
        $streetId = array_get($data, 'street_id');
        $routeId = array_get($data, 'route_id');
        $status = array_get($data, 'status');

        $query
            ->when($name, function($q) use($name){
                return $q->where('name', 'like', "%$name%");
            })
            ->when($id, function ($q) use ($id) {
                return $q->where('id', $id);
            })
            ->when($cityId, function($q) use($cityId){
                return $q->where('city_id', $cityId);
            })
            ->when($streetId, function($q) use($streetId){
                return $q->where('street_id', $streetId);
            })
            ->when($routeId, function($q) use($routeId){
                return $q->whereHas('routes', function ($q) use ($routeId) {
                    $q->whereId($routeId);
                });
            })
            ->when($status, function ($q) use ($status) {
                return $q->where('status', $status);
            })
        ;
        return $query;
    }

    public static function getRoutePath($station_ids, $route_id){
        return  Station::query()
            ->whereIn('id', $station_ids)
            ->select('latitude', 'longitude')
            ->leftJoin('route_station', 'route_station.station_id', '=', 'stations.id')
            ->where('route_station.route_id', $route_id)
            ->orderBy('order')
            ->get();
    }
}
