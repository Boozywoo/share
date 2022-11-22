<?php

namespace App\Http\Controllers\Admin;

use App\Services\Call\CallClient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Vinkla\Pusher\Facades\Pusher;
//use App\Services\Agi;
//use App\Services\AGI_AsteriskManager;
//use Log;
use Auth;
use App\Models\Client;

class CallController extends Controller
{
    function incomming(Request $request)
    {
        $time_show = $request->time_show ? $request->time_show : 45;
        $sip = $request->sip ? $request->sip : 'all';
        $number = preg_replace('/\D/', '', $request->number);
        $user = (strlen($number) > 9) ? Client::where("phone", $number)->first() : NULL;
        $message = (isset($user)) ? $user->first_name : 'Новый клиент';
        $type = ($user && $user->status == Client::STATUS_DISABLE) ? 'danger' : 'success';
        if ($type == 'danger') $message .= ' [Клиент в чёрном списке]';

        Pusher::trigger('my-channel', 'my-event', [
            'message' => $message,
            "number" => $number,
            'app_url' => env('APP_URL'),
            "sip" => $sip,
            "time_show" => (int)$time_show,
            "type" => $type
        ]);
        return $request->number;
    }

    function hand_up(Request $request)
    {
        echo $request->number;
    }

    function missed(Request $request)
    {
        echo $request->number;
    }

    function out(Request $request)
    {
        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, 'http://178.172.236.218/out/outc.php?phone=' . $request->number . '&code=' . Auth::id());
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $out = curl_exec($curl);
            curl_close($curl);
        }

        return redirect()->back();
    }

    public function outCall(Request $request)
    {
        $client = new CallClient();
        return $client->outCall(Auth::user()->sip, $request->phone);
    }
}
