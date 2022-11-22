<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouteStationPrice extends Model
{
    public $table = 'route_station_price';

    public $timestamps = false;

    protected $fillable = [
        'route_id', 'station_from_id', 'station_to_id', 'price','interval',
    ];
}
