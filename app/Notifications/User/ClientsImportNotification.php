<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClientsImportNotification extends Notification
{
    use Queueable;

    public $duplicates;
    public $wrongFirstNames;
    public $wrongPhones;

    public function __construct($duplicates, $wrongFirstNames, $wrongPhones)
    {
        $this->duplicates = $duplicates;
        $this->wrongFirstNames = $wrongFirstNames;
        $this->wrongPhones = $wrongPhones;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->view('mail.user.clients-import', [
                'duplicates' => $this->duplicates,
                'wrongFirstNames' => $this->wrongFirstNames,
                'wrongPhones' => $this->wrongPhones,
            ])
            ->subject('Клиенты импортированы');
    }
}
