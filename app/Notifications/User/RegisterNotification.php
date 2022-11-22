<?php

namespace App\Notifications\User;

use App\Channels\SmsChannel;
use App\Channels\SmsChannelFix;
use App\Services\Prettifier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\SmsProvider;

class RegisterNotification extends Notification
{
    use Queueable;

    public $password;
    public $phone;

    public function __construct($password, $phone)
    {
        $this->password = $password;
        $this->phone    = $phone;
    }

    public function via($notifiable)
    {
        $sms = new SmsChannel();
        $sms->send($notifiable, $this);
        return $notifiable->email ? [SmsChannelFix::class] : [SmsChannelFix::class];
//        return $notifiable->email ? [SmsChannel::class, 'mail'] : [SmsChannel::class];
    }

    public function toSms($notifiable)
    {
        $message = 'Вы успешно зарегистрированы. Ваш логин '.$this->phone.'. Ваш пароль '. $this->password;
        $SpCur = SmsProvider::where('default', '=', 1)->first();
        if(isset($SpCur->is_latin) ? $SpCur->is_latin : env('IS_LATIN'))
            $message  = Prettifier::Transliterate($message);
        return $message; 
    }


    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->view('mail.client.register', ['client' => $notifiable, 'password' => $this->password])
            ->subject('Регистрация на сервисе '. env('APP_URL'));
    }
}
