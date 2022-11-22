<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserVerificationCode extends Model
{
    protected $fillable = ['field','field_type','status', 'code', 'type', 'expired_at'];

    protected $dates = ['expired_at'];

    const STATUS_ACTIVE = 'active';
    const STATUS_USED = 'used';

    const TYPE_CONFIRMATION = "confirmation";
    const TYPE_PASSWORD_RESET = "password_reset";

    const TYPES = [
        self::TYPE_CONFIRMATION,
        self::TYPE_PASSWORD_RESET
    ];
}
