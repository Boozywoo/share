<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rent extends Model
{
    protected $fillable = [
        'is_meet_airport', 'time_wait', 'chair_child', 'booster', 'wheelchair', 'time_wait',
        'add_km', 'is_pay', 'is_legal_entity', 'from_city_id', 'to_city_id', 'address', 'address_to', 'operator_id',
        'company_carrier_id', 'company_customer_id', 'client_id', 'cnt_passengers', 'type_pay', 'bus_type_id',
        'from_city_id', 'to_city_id', 'agreement_id', 'tariff_id', 'duration', 'methodist_id'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function methodist()
    {
        return $this->belongsTo(User::class);
    }

    public function tariff()
    {
        return $this->belongsTo(Tariff::class);
    }

    public function busType()
    {
        return $this->belongsTo(BusType::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function agreement()
    {
        return $this->belongsTo(Agreement::class)->with('rents');
    }

    public function companyCarrier()
    {
        return $this->belongsTo(Company::class);
    }

    public function companyCustomer()
    {
        return $this->belongsTo(Company::class);
    }

    public function fromCity()
    {
        return $this->belongsTo(Station::class);
    }

    public function toCity()
    {
        return $this->belongsTo(Station::class);
    }

    public function getDurationShowAttribute()
    {
        return round($this->duration / 60) . ' ч ' . ($this->duration % 60) . ' мин';
    }

    public function Tour()
    {
        return $this->belongsTo(Tour::class);
    }
}


