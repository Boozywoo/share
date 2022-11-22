<?php

namespace App\Models;

use App\Traits\ImageableTrait;
use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    use ImageableTrait;

    protected $fillable = ['name', 'status', 'company_id'];

    const STATUS_ACTIVE = 'active';
    const STATUS_NOT_ACTIVE = 'not active';

    const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_NOT_ACTIVE
    ];

    const IMAGE_TYPE_IMAGE = 'image';

    const IMAGES_PARAMS = [
        self::IMAGE_TYPE_IMAGE => [
            'multiple' => false,
            'params' => [
                'admin' => [
                    'w' => 50,
                    'h' => 50,
                    'fit' => 'max',
                ],
            ],
        ],
    ];


    //Relationship
    public function company(){

        return $this->belongsTo(Company::class);
    }
    public function buses(){

        return $this->belongsToMany( Bus::class, 'bus_amenities');
    }

    //Scopes
    public function scopeIsActive($query){

        return $query->where('status', '=',self::STATUS_ACTIVE);
    }
}
