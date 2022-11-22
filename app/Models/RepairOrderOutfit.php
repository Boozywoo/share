<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class RepairOrderOutfit extends Model
{
    protected $fillable = ['repair_id', 'creator_id', 'odometer', 'date_from', 'date_to', 'comment', 'fuel'];

    protected $dates = ['date_from', 'date_to'];


    public function breakages()
    {
        return $this->belongsToMany(CarBreakages::class, 'repair_order_outfit_breakages', 'car_breakage_id', 'repair_order_outfit_id');
    }

    public function bus_variable()
    {
        return $this->morphOne(BusVariable::class, 'imageable');
    }

    public function setDateToAttribute($value)
    {
        $this->attributes['date_to'] = $value ? Carbon::parse($value)->format('Y-m-d'): null;
    }

    public function setDateFromAttribute($value)
    {
        $this->attributes['date_from'] = Carbon::parse($value)->format('Y-m-d');
    }
}
