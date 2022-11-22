<?php

namespace App\Services\Code;

use App\Models\Client;
use App\Models\Code;
use App\Notifications\Client\SendCodeNotification;
use App\Services\Prettifier;

class SendCodeService
{
    public static function index($phone)
    {
        $phone = Prettifier::prettifyPhoneClear($phone);
        $code = env('APP_ENV') == 'production' ? str_pad(rand(0, 9999), 4, '0') : '0000';


        Code::wherePhone($phone)->delete();
        Code::create(['phone' => $phone,'code' => $code]);

        $client = new Client(['phone' => $phone]);
        $client->notify(new SendCodeNotification($code));
    }
}