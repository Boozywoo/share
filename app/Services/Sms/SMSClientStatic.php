<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 016 16.06.19
 * Time: 16:14
 */

namespace App\Services\Sms;


use Illuminate\Http\Request;
use App\Models\SmsLog;

class SMSClientStatic
{
    public static function checkStatus()
    {
        $client = new SMSClient();
        $client->checkStatus();
    }

    public static function sendSms($phone, $message, $client = null, $order = null)
    {
        $smsClient = new SMSClient();
        $smsClient->sendSms($phone, $message, $client, $order);
    }

    public static function webHook(SmsLog $smsLog, Request $request)
    {
        $smsClient = new SMSClient();
        return $smsClient->webHook($smsLog, $request);
    }
}