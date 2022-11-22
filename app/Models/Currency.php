<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    //Accessors & Mutators

    public function getNameAttribute($name)
    {
        return trans('admin_labels.'.$name).' : '.$name;
    }

}
