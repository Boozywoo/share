<?php

namespace App\Notifications\Order;

use App\Channels\SmsChannel;
use App\Channels\SmsChannelFix;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DisableOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        if (Setting::getField('is_notification_sms') && Setting::getField('is_notification_cancel_sms')) {
            $sms = new SmsChannel();
            $sms->send($notifiable, $this);
        }

        return $notifiable->email ? [SmsChannelFix::class] : [SmsChannelFix::class];
//        return $notifiable->email ? [SmsChannel::class, 'mail'] : [SmsChannel::class];
    }

    public function toSms($notifiable)
    {
        return 'Бронь: ' . $this->order->slug . "\n" .
            'Дата: ' . $this->order->tour->date_start->format('Y-m-d') . "\n" .
            'Отменена';
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->view('mail.order.disable', ['client' => $notifiable, 'order' => $this->order])
            ->subject('Уведомление об отмене электронного билета');
    }
}
