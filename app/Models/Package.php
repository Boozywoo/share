<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Package extends Model
{
    use Notifiable;

    protected $fillable = [
        'package_destination', 'package_from', 'name_sender', 'phone_sender', 'name_receiver', 'phone_receiver', 'tour_id',
        'price', 'currency_id', 'from_station_id', 'destination_station_id',
    ];

    public function currencyName()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tour_id', 'id');
    }

    public function stationFrom()
    {
        return $this->belongsTo(Station::class, 'from_station_id', 'id');
    }
    public function stationTo()
    {
        return $this->belongsTo(Station::class, 'destination_station_id', 'id');
    }
}
