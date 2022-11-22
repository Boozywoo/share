<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tariff extends Model
{
    const TYPE_DURATION = 'duration';
    const TYPE_DISTANCE = 'distance';
    const TYPE_ROUTE = 'route';

    const TYPES = [
        self::TYPE_DURATION,
        self::TYPE_DISTANCE,
        self::TYPE_ROUTE,
    ];

    const TARIFF_DIRECTIONS = ['carrier' => 1, 'client' => 2];

    protected $guarded = [];


    public function busType()
    {
        return $this->belongsTo(BusType::class);
    }

    public function rates()
    {
        return $this->hasMany(TariffRate::class)->orderBy('max');
    }

    public function setBusTypeIdAttribute($value)
    {
        $this->attributes['bus_type_id'] = empty($value) ? null : $value;
    }


    public function scopeFilter($query, $data)
    {
        $status = array_get($data, 'status');
        $type = array_get($data, 'type');
        $busTypeId = array_get($data, 'bus_type_id');
        $agreementId = array_get($data, 'agreement_id');
        $query
            ->when($status, function ($q) use ($status) {
                return $q->where('status', $status);
            })
            ->when($type, function ($q) use ($type) {
                return $q->where('type', $type);
            })
            ->when($busTypeId, function ($q) use ($busTypeId) {
                return $q->where('bus_type_id', $busTypeId);
            })
            ->when($agreementId, function ($q) use ($agreementId) {
                return $q->where('agreement_id', $agreementId);
            });

        return $query;
    }
}
