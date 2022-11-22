<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 026 26.06.19
 * Time: 10:19
 */


namespace App\Services\Call\Providers;

use App\Models\Client;
use Vinkla\Pusher\Facades\Pusher;

class AbstractCallProvider
{
    const TYPE_SUCCESS = 'success';
    const TYPE_ERROR = 'success';
    public function pusher($number, $sip = 'all', $timeShow = 45)
    {
        $number = preg_replace('/\D/', '', $number);
        $user = (strlen($number) > 9) ? Client::where("phone", $number)->first() : NULL;
        $message = (isset($user)) ? $user->first_name : 'Новый клиент';
        $type = ($user && $user->status == Client::STATUS_DISABLE) ? 'danger' : 'success';
        if ($type == 'danger') $message .= ' [Клиент в чёрном списке]';

        Pusher::trigger('my-channel', 'my-event', [
            'message' => $message,
            "number" => $number,
            'app_url' => env('APP_URL'),
            "sip" => $sip,
            "time_show" => (int)$timeShow,
            "type" => $type
        ]);
    }

    public function responseOutCall($type, $error = null)
    {
        $data['type'] = $type;
        if ($error) {
            $data['error'] = $error;
        }
        return $data;
    }
}