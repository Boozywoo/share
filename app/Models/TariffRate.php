<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TariffRate extends Model
{
    protected $guarded = [];

    public function scopeFilter($query, $data)
    {
        $tariffId = array_get($data, 'tariff_id');
        $query
            ->when($tariffId, function ($q) use ($tariffId) {
                return $q->where('tariff_id', $tariffId);
            });
        return $query;
    }

    public function tariff()
    {
        return $this->belongsTo(Tariff::class);
    }
}
