<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarBreakages extends Model
{
    protected $fillable = ['name', 'parent_id'];

    public function childs(){

        return $this->hasMany(CarBreakages::class,'parent_id');
    }
}
