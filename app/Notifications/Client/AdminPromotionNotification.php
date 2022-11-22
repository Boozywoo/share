<?php

namespace App\Notifications\Client;

use App\Channels\SmsChannel;
use App\Channels\SmsChannelFix;
use App\Services\Order\OrderSmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Setting;

class AdminPromotionNotification extends Notification
{
    use Queueable;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        if (Setting::getField('is_promotion_backend'))
        {
            $sms = new SmsChannel();
            $sms->send($notifiable, $this, Setting::getField('promotion_backend_text'));
        }

        return $notifiable->email ? [SmsChannelFix::class] : [SmsChannelFix::class];
//        return $notifiable->email ? [SmsChannel::class, 'mail'] : [SmsChannel::class];
    }

    public function toSms($notifiable)
    {
        return OrderSmsService::template($this->order);
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->view('mail.order.active', ['client' => $notifiable, 'order' => $this->order])
            ->subject('Акции');
    }
}
