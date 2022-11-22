<?php

namespace App\Notifications\Client;

use App\Channels\SmsChannel;
use App\Channels\SmsChannelFix;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SendCodeNotification extends Notification
{
    use Queueable;

    public $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function via($notifiable)
    {
        $sms = new SmsChannel();
        $sms->send($notifiable, $this);
        return [SmsChannelFix::class];
    }

    public function toSms($notifiable)
    {
        return $this->code;
    }
}
