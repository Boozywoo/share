<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidentTemplate extends Model
{
    protected $fillable = ['name','status','is_photo','company_id'];

    const STATUS_TRUE = 1;
    const STATUS_FALSE = 0;

    const STATUSES = [
        self::STATUS_TRUE,
        self::STATUS_FALSE
    ];

    const IS_PHOTO_TRUE = 1;
    const IS_PHOTO_FALSE = 0;
    const IS_PHOTO = [
        self::IS_PHOTO_TRUE,
        self::IS_PHOTO_FALSE,
    ];

    //Relationships

    public function company(){
        return $this->hasOne(Company::class);
    }

    //Scopes

    public function scopeIsStatus($query, $status){

        return $query->where('status', '=', $status);
    }

}
