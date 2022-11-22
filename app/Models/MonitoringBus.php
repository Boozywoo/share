<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonitoringBus extends Model
{
    public $table = "monitoring_bus";
    protected $guarded = [];

    public function bus()
    {
        return $this->belongsTo(Bus::class, 'bus_id', 'id');
    }

    public static function getLocationByBusId($bus_id){
       return self::whereBusId($bus_id)
           ->join('buses','buses.id', '=','bus_id')
           ->orderBy('monitoring_bus.id', 'DESC')
           ->select('latitude', 'longitude', 'buses.name', 'buses.number')
           ->take(1)
           ->get();
    }
}
