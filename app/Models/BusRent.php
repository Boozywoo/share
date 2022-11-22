<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusRent extends Model
{
    protected $table = 'bus_rent';
    protected $fillable = ['from_hour', 'to_hour', 'cost', 'bus_id'];


    public function scopeFilter($query, $data)
    {
        $busId = array_get($data, 'bus_id');
        $query
            ->when($busId, function ($q) use ($busId) {
                return $q->where('bus_id', $busId);
            })
        ;
        return $query;
    }
}
