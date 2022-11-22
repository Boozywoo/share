<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{

    protected $fillable = [
        'type', 'key', 'value'
    ];

    public static function store($type, $data)
    {
        foreach ($data as $key => $value)    {
            self::updateOrCreate(['type' => $type, 'key' => $key], ['value' => $value]);
        }
    }

    public static function getValue($type, $key, $default = null)
    {
        return self::where('type', $type)->where('key', $key)->first()->value ?? $default;
    }

}