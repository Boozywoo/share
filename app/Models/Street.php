<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Street extends Model
{
    protected $fillable = ['name', 'city_id'];

    const STATUS_ACTIVE = 'active';
    const STATUS_DISABLE = 'disable';

    const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_DISABLE,
    ];

    public function scopeFilter($query, $data)
    {
        $name = array_get($data, 'name');
        $cityId = array_get($data, 'city_id');

        $query
            ->when($name, function($q) use($name){
                return $q->where('name', 'like', "%$name%");
            })
            ->when($cityId, function($q) use($cityId){
                return $q->where('city_id', $cityId);
            })
        ;
        return $query;
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function stations()
    {
        return $this->hasMany(Station::class);
    }
}
