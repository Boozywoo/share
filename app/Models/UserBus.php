<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBus extends Model
{
    protected $table = 'user_bus';
    protected $fillable = ['bus_id', 'imageable_id', 'imageable_type'];
    public $timestamps = true;


    const TYPE_TAKE = 'take';
    const TYPE_PUT = 'put';
    const TYPE_REVIEW = 'review';

    const TYPES = [
        self::TYPE_TAKE,
        self::TYPE_PUT,
        self::TYPE_REVIEW
    ];

    public function imageable()
    {
        return $this->morphTo();
    }



}
