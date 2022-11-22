<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Cron extends Model
{
    protected $table = 'cron';

    protected $fillable = [
        'type', 'object_name', 'is_active', 'params','create_at'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_DISABLE = 'disable';

    //Relationships

    //Scopes

}

