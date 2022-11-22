<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class DriverFines extends Model
{
    protected $table = 'driver_fines';

    protected $fillable = [
        'driver_id', 'sum', 'date', 'type', 'description'
    ];
}
