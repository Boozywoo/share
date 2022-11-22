<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplatePlace extends Model
{
    protected $fillable = [
        'number', 'type'
    ];

    const TYPE_DRIVER = 'driver';
    const TYPE_DELETE = 'delete';
    const TYPE_NUMBER = 'number';

    const TYPES = [
        self::TYPE_DRIVER,
        self::TYPE_DELETE,
        self::TYPE_NUMBER,
    ];

    //Relationships
    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    //Scopes
    public function scopeFilter($query, $data)
    {
        $number = array_get($data, 'number');
        $type = array_get($data, 'type');
        $templateId = array_get($data, 'template_id');
        $query
            ->when($number, function ($q) use ($number){
                return $q->where('number', $number);
            })
            ->when($type, function ($q) use ($type){
                return $q->where('type', $type);
            })
            ->when($templateId, function ($q) use ($templateId){
                return $q->where('template_id', $templateId);
            })
        ;
        return $query;
    }
}
