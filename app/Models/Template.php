<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $fillable = [
        'name', 'ranks', 'columns', 'count_places'
    ];

    //Relationships
    public function templatePlaces()
    {
        return $this->hasMany(TemplatePlace::class);
    }

    public function buses()
    {
        return $this->hasMany(Bus::class);
    }

    //Scopes
    public function scopeFilter($query, $data)
    {
        $name = array_get($data, 'name');
        $count = array_get($data, 'count');
        $query
            ->when($name, function ($q) use ($name){
                return $q->where('name', 'like', "%$name%");
            })
            ->when($count, function ($q) use ($count){
                return $q->where('count', '>', $count);
            })
        ;
        return $query;
    }
}
