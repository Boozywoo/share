<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agreement extends Model
{
    protected $guarded = [];
    protected $dates = ['date', 'date_start', 'date_end'];

    public function serviceCompany()
    {
        return $this->belongsTo(Company::class);
    }

    public function customerCompany()
    {
        return $this->belongsTo(Company::class);
    }

    public function tariffs()
    {
        return $this->belongsToMany(Tariff::class);
    }

    public function scopeFilter($query, $data)
    {
        $number = array_get($data, 'number');
        $enabled = array_get($data, 'enabled');

        $query
            ->when($number, function ($q) use ($number) {
                return $q->where('number', 'like', "%$number%");
            })
            ->when($enabled !== null, function ($q) use ($enabled) {
                return $q->where('enabled', $enabled);
            });
        return $query;
    }

    public function getStatusAttribute()
    {
        return $this->enabled;
    }

    public function rents()
    {
        return $this->hasMany(Rent::class);
    }

    public function getAmountRentsAttribute()
    {
        $expended = $this->expended ? $this->expended : 0;
        return Tour::whereIn('rent_id', $this->rents->pluck('id')->toArray())->sum('price') + $expended;
    }

    public function getBalanceAttribute()
    {
        return $this->limit - $this->getAmountRentsAttribute();
    }

    public function updateStatus()
    {
        if (($this->limit - $this->AmountRents) < 0) {
            $this->enabled = false;
        } else {
            $this->enabled = true;
        }
        $this->save();
    }
}
