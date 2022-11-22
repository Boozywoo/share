<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use Sluggable;

    protected $fillable = [
        'title',
        'content',
        'meta_title',
        'meta_description',
    ];

    public function sluggable()
    {
        return ['slug' => ['source' => 'title']];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    //Mutators
    public function getUrlAttribute()
    {
        return route('index.page', $this->slug);
    }

    public function getGenerateMetaTitleAttribute()
    {
        return $this->meta_title ? $this->meta_title : $this->title;
    }

    public function getGenerateMetaDescriptionAttribute()
    {
        return $this->meta_description ? $this->meta_description : $this->title;
    }

    //Scopes
    public function scopeFilter($query, $data)
    {
        $title = array_get($data, 'title');
        $query
            ->when($title, function($q) use($title){
                return $q->where('title', 'like', "%$title%");
            })
        ;
        return $query;
    }
}
