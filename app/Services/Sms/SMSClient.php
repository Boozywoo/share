<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 014 14.06.19
 * Time: 14:42
 */

namespace App\Services\Sms;


use App\Services\Support\HandlerError;
use Illuminate\Http\Request;
use App\Models\SmsLog;

class SMSClient implements ISmsProvider
{
    protected $provider;

    public function __construct()
    {
        $classname = 'App\\Services\\Sms\\Providers\\' . config('sms.provider');
        try {
            $this->provider = new $classname();
        } catch (\Exception $e) {
        }
    }

    public function sendSms($phone, $message, $client = null, $order = null)
    {
        try {
            $function = __FUNCTION__;
            $authUser = \Auth::id();

            list($messageId, $status, $error) = $this->provider->$function($phone, $message);
            if ($client && $order) {
                SmsLog::create([
                    'client_id' => $client ? $client->id : null,
                    'user_send_id' => $authUser && empty($authUser->client_id) ? \Auth::id() : null,
                    'order_id' => $order ? $order->id : null,
                    'message' => $message,
                    'phone' => $client ? $client->phone : null,
                    'message_id' => $messageId ? $messageId : null,
                    'status' => $status,
                    'error' => $error,
                ]);
            }

        } catch (\Exception $e) {
            HandlerError::index($e);
        }
    }

    public function getStatusSms($orderId)
    {
        try {
            $function = __FUNCTION__;
            $this->provider->$function($orderId);
        } catch (\Exception $e) {
            HandlerError::index($e);
        }
    }

    public function getBalance()
    {
        try {
            $function = __FUNCTION__;
            return $this->provider->$function();
        } catch (\Exception $e) {
        }
    }

    public function getStatusBunkSms($bunk)
    {
        try {
            $function = __FUNCTION__;
            $this->provider->$function($bunk);
        } catch (\Exception $e) {
        }
    }

    public function checkStatus()
    {
        try {
            $function = __FUNCTION__;
            $this->provider->$function();
        } catch (\Exception $e) {

        }
    }

    public function webHook(SmsLog $smsLog, Request $request)
    {
        try {
            $function = __FUNCTION__;
            return $this->provider->$function();
        } catch (\Exception $e) {

        }
    }
}