<?php

namespace App\Notifications\Order;

use App\Channels\SmsChannel;
use App\Channels\SmsChannelFix;
use App\Services\Order\OrderSmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Log;
use App\Models\Setting;

class ActiveOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;

    public function __construct($order, $is_send = 'off')
    {
        $this->order = $order;
        $this->is_send = $is_send;
    }

    public function via($notifiable)
    {
        if (Setting::getField('is_notification_sms') && (!Setting::getField('auto_turn_notification') ||
            (!$this->order->tour->is_edit && Setting::getField('auto_turn_notification'))))
        {
            $sms = new SmsChannel();
            $sms->send($notifiable, $this);
            $this->order->cnt_sms += 1;
            $this->order->save();
        }

        if((Setting::getField('auto_turn_notification') && $this->is_send == 'on' && $this->order->tour->is_edit)){
            $sms = new SmsChannel();
            $sms->send($notifiable, $this);
            $this->order->cnt_sms += 1;
            $this->order->save();
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
            ->subject('Билет успешно оформлен');
    }
}
