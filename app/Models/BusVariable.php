<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusVariable extends Model
{

    protected $fillable = ['fuel','odometer','imageable_type','imageable_id','bus_id'];


    public function imageable()
    {
        return $this->morphTo();
    }

    public function bus(){
        //
    }
}
