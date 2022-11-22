<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CustomerPersonality extends Model
{
    protected $fillable = ['name', 'slug'];

    public function setSlugAttribute($value)
    {
        if(!$value){
            $this->attributes['slug'] = Str::slug($this->attributes['name']);
        }
    }
}
