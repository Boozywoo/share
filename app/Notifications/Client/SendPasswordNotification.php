<?php

namespace App\Notifications\Client;

use App\Channels\SmsChannel;
use App\Channels\SmsChannelFix;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendPasswordNotification extends Notification
{
    use Queueable;

    public $password;

    public function __construct($password)
    {
        $this->password = $password;
    }

    public function via($notifiable)
    {
        $sms = new SmsChannel();
        $sms->send($notifiable, $this);
        return [SmsChannelFix::class];
    }

    public function toSms($notifiable)
    {
        return trans('admin_labels.password') ." ". $this->password;
    }
}
