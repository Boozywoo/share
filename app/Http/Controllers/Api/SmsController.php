<?php

namespace App\Http\Controllers\Api;

use App\Models\SmsLog;
use App\Services\Sms\SMSClientStatic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SmsController extends Controller
{
    public function webhook(Request $request)
    {
        $messageId = $request->get('message_id');
        if ($messageId and ($sms = SmsLog::where('message_id', $messageId)->first())) {
            SMSClientStatic::webHook($sms, $request);
        }

        \Log::info(print_r($request->all(), 1));
    }
}
