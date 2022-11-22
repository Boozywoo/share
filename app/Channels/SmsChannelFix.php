<?php

namespace App\Channels;

use App\Models\SmsLog;
use Illuminate\Notifications\Notification;

class SmsChannelFix
{
    public function send($notifiable, Notification $notification)
    {
        /*$sender = env('SMS_SENDER') ? $sender = env('SMS_SENDER') : NULL;
        $data = [
            'recipients' => '375292567968',
            'message' => $notification->toSms($notifiable),
            'user' => env('SMS_API_LOGIN'),
            'apikey' => env('SMS_API_PASSWORD'),
            'r' => 'api/msg_send',
        ];
        if (isset($sender)) $data['sender'] = env('SMS_SENDER');

        if ($data['message']) {
            if (env('SMS_SEND')) {
                $query = http_build_query($data + ['urgent' => 1]);
                $result = file_get_contents("http://cp.websms.by?$query");
                if (json_decode($result)->status != 'success') {
                    $query = http_build_query($data);
                    $result = file_get_contents("http://cp.websms.by?$query");
                }
            }


            //client id no universal
            SmsLog::create([
                'message' => $data['message'],
                'client_id' => $notifiable->id,
                'phone' => $notifiable->phone,
            ]);
        }*/
    }
}