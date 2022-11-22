<?php

namespace App\Notifications\Rent;

use App\Channels\SmsChannel;
use App\Channels\SmsChannelFix;
use App\Models\Setting;
use App\Services\Order\OrderSmsService;
use App\Services\Rent\OrderRentSmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ActiveRentNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $tour;

    public function __construct($tour)
    {
        $this->tour = $tour;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if (Setting::getField('is_notification_sms'))
        {
            $sms = new SmsChannel();
            $sms->send($notifiable, $this);
        }

        return $notifiable->email ? [SmsChannelFix::class] : [SmsChannelFix::class];
    }

    public function toSms($notifiable)
    {
        return OrderRentSmsService::index($this->tour);
    }
}
