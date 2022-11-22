<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderActiveMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $client = \App\Models\Client::find(971);
        $order = \App\Models\Order::find(31690);
        return $this
            ->view('mail.order.active', ['client' => $client, 'order' => $order])
            ->subject('Билет успешно оформлен');
    }
}
