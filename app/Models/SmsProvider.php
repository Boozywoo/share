<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsProvider extends Model
{

    protected $table = 'sms_providers';

    protected $fillable = [
        'name', 'number_prefix', 'sms_send', 'sms_sender', 'sms_api_login', 'sms_api_password', 'default', 'active', 'is_latin'
    ];

    protected $casts = [
      'id' => 'integer',
      'number'=>'string',
      'number_prefix'=>'integer',
      'sms_send'=>'string',
      'sms_sender'=>'string',
      'sms_api_login'=>'string',
      'sms_api_password'=>'string',
      'default'=>'boolean',
      'active'=>'boolean',
      'is_latin'=>'string',
    ];
}