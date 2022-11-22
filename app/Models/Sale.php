<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'name', 'percent', 'type', 'count', 'status', 'date_start', 'date_finish', 'value', 'is_percent'
    ];

    protected $dates = ['date_start', 'date_finish'];

    const TYPE_BEGINNING_WITH = 'beginnig_with';
    const TYPE_EACH = 'each';
    const TYPE_AT_A_TIME = 'at_a_time';

    const TYPES = [
        self::TYPE_BEGINNING_WITH,
        self::TYPE_EACH,
        self::TYPE_AT_A_TIME,
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_DISABLE = 'disable';

    const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_DISABLE,
    ];


    //Scopes
    public function scopeFilter($query, $data)
    {
        $name = array_get($data, 'name');
        $status = array_get($data, 'status');
        $type = array_get($data, 'type');
        $query
            ->when($name, function ($q) use ($name) {
                return $q->where('name', 'like', "%$name%");
            })
            ->when($status, function ($q) use ($status) {
                return $q->where('status', $status);
            })
            ->when($type, function ($q) use ($type) {
                return $q->where('type', $type);
            });
        return $query;
    }

    public function scopeActive($query, $tour, $type)
    {
        return $query
            ->whereType($type)
            ->where('date_start', '<=', $tour->date_start->format('y-m-d'))
            ->where('date_finish', '>=', $tour->date_start->format('y-m-d'))
            ->whereStatus(self::STATUS_ACTIVE);
    }
}
