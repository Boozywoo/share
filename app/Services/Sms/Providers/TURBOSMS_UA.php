<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 014 14.06.19
 * Time: 15:07
 */

namespace App\Services\Sms\Providers;

use App\Http\Requests\Request;
use App\Services\Sms\ISmsProvider;
use App\Models\SmsLog;

class TURBOSMS_UA extends AbstractSmsProvider implements ISmsProvider
{
    const STATUSES = [
        'Сообщение доставлено получателю' => SmsLog::SMS_STATUS_DELIVERED,
    ];

    public function __construct()
    {
        // Подключаемся к серверу
        $this->client = new \SoapClient('http://turbosms.in.ua/api/wsdl.html');

        $this->data = [
            'login' => env('SMS_API_LOGIN'),
            'password' => env('SMS_API_PASSWORD'),
            'sender' => env('SMS_SENDER')
        ];
    }

    public function auth()
    {
        $result = $this->client->Auth($this->data);
        if ($result->AuthResult == "Вы успешно авторизировались") {
            return true;
        }
        return $result->AuthResult;
    }

    public function sendSms($phone, $message, $client = null, $order = null)
    {
        $error = null;
        $messageId = null;

        if (($auth = $this->auth()) !== true) {
            return [null, SmsLog::SMS_STATUS_NOT_DELIVERED, $auth];
        }

        $this->data += [
            'destination' => '+' . preg_replace('/[^0-9.]+/', '', $phone),
            'text' => $message
        ];

        $result = $this->client->SendSMS($this->data);
        if ($result->SendSMSResult->ResultArray[0] == 'Сообщения успешно отправлены') {
            $messageId = $result->SendSMSResult->ResultArray[1];
            $status = SmsLog::SMS_STATUS_NEW;
        } else {
            $status = SmsLog::SMS_STATUS_NOT_DELIVERED;
            $error = $result->SendSMSResult->ResultArray[1];
        }
        return [$messageId, $status, $error];
    }

    public function getBalance()
    {
        return 0;
    }

    public function getStatusSms($smsId)
    {
        $this->auth();
        $result = $this->client->GetMessageStatus(['MessageId' => $smsId]);
        return $result;
    }

    public function getStatusBunkSms($messagesId)
    {
        //return $result;
    }

    public function checkStatus()
    {
        return true;
        $this->getCheckData(1);
        if ($this->checkSms->count()) {
            $this->auth();
            foreach ($this->checkSms as $sms) {
                $result = $this->client->GetMessageStatus(['MessageId' => $sms->message_id]);
                $status = null;
                if ($result->GetMessageStatusResult == 'Сообщение доставлено получателю') {
                    $status = SmsLog::SMS_STATUS_DELIVERED;
                }
                if ($status) {
                    SmsLog::where('message_id', $sms->message_id)->update([
                            'status' => $status,
                        ]
                    );
                }
            }
        }
    }

    public function webhook(SmsLog $smsLog, Request $request)
    {
        $status = $request->get('status');
        switch ($status) {
            case 'DELIVRD':
                $smsLog->status = SmsLog::SMS_STATUS_DELIVERED;
                break;
        }
        $smsLog->confirm_datetime = $request->sent_date;
        $smsLog->send_datetime = $request->dlr_date;
        $smsLog->error = empty($request->error) ? null : $request->error;
        $smsLog->save();
    }
}