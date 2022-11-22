<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddService extends Model
{
    protected $fillable = [
        'name', 'percent', 'status','is_percent', 'value'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_DISABLE = 'disable';

    const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_DISABLE,
    ];


    public function routes()
    {
        return $this->belongsToMany(Route::class, 'route_add_service');
    }

    //Scopes
    public function scopeFilter($query, $data)
    {
        $name = array_get($data, 'name');
        $status = array_get($data, 'status');
        $query
            ->when($name, function ($q) use ($name) {
                return $q->where('name', 'like', "%$name%");
            })
            ->when($status, function ($q) use ($status) {
                return $q->where('status', $status);
            });
        return $query;
    }

}
