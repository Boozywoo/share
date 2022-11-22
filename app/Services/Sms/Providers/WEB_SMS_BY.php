<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 014 14.06.19
 * Time: 15:07
 */

namespace App\Services\Sms\Providers;

use App\Services\Sms\ISmsProvider;
use App\Models\SmsLog;
use mttzzz\laravelTelegramLog\Telegram;

class WEB_SMS_BY extends AbstractSmsProvider implements ISmsProvider
{
    public function __construct()
    {
        $this->data = [
            'user' => env('SMS_API_LOGIN'),
            'apikey' => env('SMS_API_PASSWORD'),
        ];
        if (env('SMS_SENDER')) $this->data['sender'] = env('SMS_SENDER');
    }

    CONST MAIN_URL = 'http://cp.websms.by?';

    public function sendSms($phone, $message, $client = null, $order = null)
    {
        $error = null;
        $messageId = null;
        $status = SmsLog::SMS_STATUS_NEW;
        $this->data += [
            'recipients' => $phone,
            'message' => $message,
            'r' => 'api/msg_send',
            'urgent' => 1,
        ];

        $result = $this->sendRequest(self::MAIN_URL);
        $result = json_decode($result);
        if ($result->status != 'success') {
            $status = SmsLog::SMS_STATUS_NOT_DELIVERED;
            $error = $result->message;
        } else {
            $messageId = $result->messages_id[0];
        }
        return [$messageId, $status, $error];
    }

    public function getBalance()
    {
        $this->data += [
            'r' => 'api/user_balance'
        ];

        $result = $this->sendRequest(self::MAIN_URL);
        $result = json_decode($result);
        return empty($result->balance) ? 0 : $result->balance;
    }

    public function getStatusSms($orderId)
    {
        $this->data += [
            'r' => 'api/msg_status',
            'messages_id' => $orderId
        ];

        $result = $this->sendRequest(self::MAIN_URL);
        $result = json_decode($result);
        return $result;
    }

    public function getStatusBunkSms($messagesId)
    {
        $this->data += [
            'r' => 'api/msg_list',
            'messages_id' => $messagesId
        ];

        $result = $this->sendRequest(self::MAIN_URL);
        $result = json_decode($result);
        return $result;
    }

    public function checkStatus()
    {
        $this->getCheckData();
        if ($this->checkSms->count()) {
            $messagesId = $this->checkSms->pluck('message_id')->toArray();
            $messagesId = implode(',', $messagesId);
            $smsStatuses = $this->getStatusBunkSms($messagesId);
            foreach ($smsStatuses->messages as $message) {
                SmsLog::where('message_id', $message->id)->update([
                        'status' => $message->status,
                        'send_datetime' => $message->send_datetime,
                        'confirm_datetime' => $message->confirm_datetime,
                    ]
                );
            }
        }

    }
}