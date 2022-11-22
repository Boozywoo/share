<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $fillable = [
        'client_id', 'driver_id', 'api_token'
    ];

    //Relationships
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function client()
    {
        return $this->hasMany(Client::class);
    }
}
