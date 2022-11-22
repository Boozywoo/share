<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleDay extends Model
{
    protected $fillable = [
        'schedule_id', 'driver_id', 'day', 'price'
    ];

    //Relationships
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
    
    public function driver()
    {
        return $this->hasMany(Driver::class);
    }

    //Mutators

    //Scopes
    public function scopeFilter($query, $data)
    {
        $driverId = array_get($data, 'driver_id');
        $busId = array_get($data, 'bus_id');
        $query
            ->when($driverId, function ($q) use ($driverId) {
                return $q->where('driver_id', $driverId);
            })
        ;
        return $query;
    }
}
