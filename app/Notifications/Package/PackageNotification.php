<?php

namespace App\Notifications\Package;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Channels\SmsChannel;
use App\Channels\SmsChannelFix;

class PackageNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $sms = new SmsChannel();
        $sms->send($notifiable, $this);
        return [SmsChannelFix::class];
    }


    public function toSms($notifiable)
    {
        return $this->message;
    }

}
