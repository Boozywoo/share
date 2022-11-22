<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    const SMS_STATUS_NEW = 'new';
    const SMS_STATUS_SEND = 'send';
    const SMS_STATUS_DELIVERED = 'delivered';
    const SMS_STATUS_NOT_DELIVERED = 'notdelivered';
    const SMS_STATUS_BLOCKED = 'blocked';
    const SMS_STATUS_IN_PROGRESS = 'inprogress';
    const SMS_STATUS_ABSENT = 'absent';

    const SMS_STATUSES = [
        self::SMS_STATUS_NEW,
        self::SMS_STATUS_SEND,
        self::SMS_STATUS_DELIVERED,
        self::SMS_STATUS_NOT_DELIVERED,
        self::SMS_STATUS_BLOCKED,
        self::SMS_STATUS_IN_PROGRESS,
        self::SMS_STATUS_ABSENT,
    ];


    protected $dates = ['confirm_datetime', 'send_datetime'];

    const SMS_NO_CHECK_STATUSES = [
        self::SMS_STATUS_DELIVERED,
        self::SMS_STATUS_NOT_DELIVERED,
        self::SMS_STATUS_BLOCKED
    ];

    protected $fillable = [
        'client_id',
        'user_send_id',
        'message',
        'message_id',
        'phone',
        'status',
        'error',
        'order_id',
        'confirm_datetime',
        'send_datetime',

    ];

    //Relationships
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function userSend()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}