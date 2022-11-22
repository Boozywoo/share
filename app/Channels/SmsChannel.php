<?php

namespace App\Channels;

use App\Models\SmsProvider;
use App\Services\Log\TelegramLog;
use App\Services\Sms\Providers\BAMBOOGROUP;
use App\Services\Sms\SMSClientStatic;
use App\Services\Support\HandlerError;
use GuzzleHttp\Client;
use Illuminate\Notifications\Notification;
use Zadarma_API\Api;
use Zadarma_API\ApiException;
use App\Services\Sms\Providers\BEELINE;

class SmsChannel
{
    public function send($notifiable, $notification, $message = null, $phone = null)
    {
        $message = (isset($message)) ? $message : $notification->toSms($notifiable);
        $this->SpCur = null;
        $phone = isset($phone) ? $phone : $notifiable->phone;
        $phone = preg_replace('/[^0-9.]+/', '', $phone);

        $SmsProviders = SmsProvider::all();
        foreach ($SmsProviders as $sp) {
            if(preg_match("/^$sp->number_prefix/", $phone)) {
                $this->SpCur = $sp;
            }
        }

        if($this->SpCur == null) $this->SpCur = SmsProvider::where('default', '=', 1)->first();
        if ($message) {
            call_user_func_array(array($this, $this->SpCur->name), array($message, $phone));
            if (in_array(config('sms.provider'), ['WEB_SMS_BY', 'TURBOSMS_UA'])) { //Новая обработка отправка смс готово пока что только для ВЕБСМС и для Зел. слонов
                //SMSClientStatic::sendSms($phone, $message, $notifiable, isset($notification->order) ? $notification->order : null);
                //call_user_func_array(array($this, config('sms.provider')), array($message, $phone));

            } else {
//                call_user_func_array(array($this, config('sms.provider')), array($message, $phone));
            }
        }
    }

    public function WEB_SMS_BY($message, $phone)
    {
        try {
            $data = [
              'recipients' => $phone,
              'message' => $message,
              //'user' => env('SMS_API_LOGIN'),
              'sender' => isset($this->SpCur->sms_sender) ? $this->SpCur->sms_sender : env('SMS_SENDER'),
              'user' => isset($this->SpCur->sms_api_login) ? $this->SpCur->sms_api_login : env('SMS_API_LOGIN'),
              'apikey' => isset($this->SpCur->sms_api_password) ? $this->SpCur->sms_api_password : env('SMS_API_PASSWORD'),
              'r' => 'api/msg_send',
            ];

            $query = http_build_query($data + ['urgent' => 1]);
            $result = file_get_contents("http://cp.websms.by/api/?$query");

            if (json_decode($result)->status != 'success') {
                $query = http_build_query($data);
                $result = file_get_contents("http://cp.websms.by/api/?$query");
            }
        }
        catch (\Exception $e) {
            \debug($e->getMessage());
            \Log::error($e->getMessage());
        }
    }

    public function MTS($message, $phone) 
    {
        $body = json_encode([
            'phone_number' => $phone,
            'extra_id' => rand(1, 999999999),
            'callback_url' => env('APP_URL') . '/' . route('admin.sms.get_callback_mts'),
            'tag' => isset($this->SpCur->sms_sender) ? $this->SpCur->sms_sender : env('SMS_SENDER'),
            'channels' => array('sms'),
            'channel_options' => array('sms' => array('text' => $message, 'alpha_name' => isset($this->SpCur->sms_sender) ? $this->SpCur->sms_sender : env('SMS_SENDER'), 'ttl' => 300)),
        ]);

        $client = new Client([
            'base_uri' => 'https://api.communicator.mts.by/' . env('SMS_ID') . '/json2/simple',
            'headers' => ['Content-Type' => 'application/json'],
            'body' => $body,
            'auth' => [isset($this->SpCur->sms_api_login) ? $this->SpCur->sms_api_login : env('SMS_API_LOGIN'), 
            isset($this->SpCur->sms_api_password) ? $this->SpCur->sms_api_password : env('SMS_API_PASSWORD')]
        ]);
        $client->request('POST');
    }

    public function MCOMMUNICATOR($message, $phone)
    {
        try {
            set_time_limit(10);
            $bearer = isset($this->SpCur->sms_api_password) ? $this->SpCur->sms_api_password : env('SMS_API_PASSWORD');
            $naming = isset($this->SpCur->sms_sender) ? $this->SpCur->sms_sender : env('SMS_SENDER');
            $httpHeaders = array(
                'http' => array(
                    'protocol_version' => 1.1,
                    'header' => "Authorization:Bearer " . $bearer,
                ));
            $context = stream_context_create($httpHeaders);
            $params = array('stream_context' => $context,
                'trace' => 1,
                'exceptions' => 0);

            $client = new \SoapClient("https://api.mcommunicator.ru/m2m/m2m_api.asmx?wsdl", $params);
            $result = $client->SendMessage(
                array(
                    "naming" => $naming,
                    "msid" => $phone,
                    "message" => $message,
                )
            );

        } catch (\Exception $e) {
            TelegramLog::telegram('MCOMMUNICATOR: ' . $e->getMessage());
        }
    }


    public function SMS_ASSISTENT_BY($message, $phone)
    {
        try {
            $data = [
                'recipient' => $phone,
                'message' => $message,
                'user' => isset($this->SpCur->sms_api_login) ? $this->SpCur->sms_api_login : env('SMS_API_LOGIN'),
                'password' => isset($this->SpCur->sms_api_password) ? $this->SpCur->sms_api_password : env('SMS_API_PASSWORD'),
                'sender' => isset($this->SpCur->sms_sender) ? $this->SpCur->sms_sender : env('SMS_SENDER'),
            ];

            $query = http_build_query($data);
            $result = file_get_contents("https://userarea.sms-assistent.by/api/v1/send_sms/plain?$query");
            if ($result < 0) {
                \Log::info('SMS_ERROR:' . $result);
            }
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
        }

    }

    public function WEB_SMS_RU($message, $phone)
    {
        $data = [
            'Phone_list' => $phone,
            'Message' => $message,
            'Http_username' => isset($this->SpCur->sms_api_login) ? $this->SpCur->sms_api_login : env('SMS_API_LOGIN'),
            'Http_password' => isset($this->SpCur->sms_api_password) ? $this->SpCur->sms_api_password : env('SMS_API_PASSWORD'),
        ];
        $result = file_get_contents('http://cab.websms.ru/http_in6.asp?' . http_build_query($data));
        \Debugbar::info($result);
        \Log($phone);
    }

    public function WEB_SMS_UKR($message, $phone)
    {
        $text = iconv('windows-1251', 'utf-8', htmlspecialchars($message));
        $description = iconv('windows-1251', 'utf-8', htmlspecialchars('Моя первая рассылка'));
        $start_time = 'AUTO'; // отправить немедленно или ставим дату и время  в формате YYYY-MM-DD HH:MM:SS
        $end_time = 'AUTO'; // автоматически рассчитать системой или ставим дату и время  в формате YYYY-MM-DD HH:MM:SS
        $rate = 1; // скорость отправки сообщений (1 = 1 смс минута). Одиночные СМС сообщения отправляются всегда с максимальной скоростью.
        $lifetime = 4; // срок жизни сообщения 4 часа
        $source = 'SMS'; // Alfaname
        $recipient = $phone;
        $user = isset($this->SpCur->sms_api_login) ? $this->SpCur->sms_api_login : env('SMS_API_LOGIN'); // тут ваш логин в международном формате без знака +. Пример: 380501234567
        $password = isset($this->SpCur->sms_api_password) ? $this->SpCur->sms_api_password : env('SMS_API_PASSWORD'); // Ваш пароль

        $myXML = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
        $myXML .= "<request>";
        $myXML .= "<operation>SENDSMS</operation>";
        $myXML .= '		<message start_time="' . $start_time . '" end_time="' . $end_time . '" lifetime="' . $lifetime . '" rate="' . $rate . '" desc="' . $description . '" source="' . $source . '">' . "\n";
        $myXML .= "		<body>" . $text . "</body>";
        $myXML .= "		<recipient>" . $recipient . "</recipient>";
        $myXML .= "</message>";
        $myXML .= "</request>";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERPWD, $user . ':' . $password);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, 'http://sms-fly.com/api/api.php');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml", "Accept: text/xml"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $myXML);
        $response = curl_exec($ch);

        curl_close($ch);

    }

    public function DEVINOTELE($message, $phone)
    {
        try {
            $client = new Client();
            $data = [
                'login' => isset($this->SpCur->sms_api_login) ? $this->SpCur->sms_api_login : env('SMS_API_LOGIN'),
                'password' => isset($this->SpCur->sms_api_password) ? $this->SpCur->sms_api_password : env('SMS_API_PASSWORD')
            ];

            $sessionId = file_get_contents('https://integrationapi.net/rest/user/sessionid?' . http_build_query($data));
            $sessionId = str_replace('"', '', $sessionId);

            $data = [
                'SessionID' => $sessionId,
                'DestinationAddress' => $phone,
                'Data' => $message,
                'SourceAddress' => isset($this->SpCur->sms_sender) ? $this->SpCur->sms_sender : env('SMS_SENDER')
            ];
            $client->post('https://integrationapi.net/rest/Sms/Send?' . http_build_query($data));
        } catch (\Exception $e) {
            HandlerError::index($e);
        }
    }

    public function DEVINOTELE_RU($message, $phone)
    {
        try {
            $body = json_encode([
                'messages' => [
                        [
                        'from' => isset($this->SpCur->sms_sender) ? $this->SpCur->sms_sender : env('SMS_SENDER'),
                        'to' => $phone,
                        'text' => $message,
                        ]
                    ]
            ]);

            $client = new Client([
                'base_uri' => 'https://api.devino.online/sms/messages',
                'headers' => ['Content-Type' => 'application/json', 'Authorization' => 'Key '.(isset($this->SpCur->sms_api_password) ? $this->SpCur->sms_api_password : env('SMS_API_PASSWORD'))],
                'body' => $body
            ]);
            $result = $client->request('POST');

        } catch (\Exception $e) {
            \Log::info('Devino SMS error: ' . $e->getCode() . ' ' . $e->getMessage());
        }
    }

    public function SMS_RU($message, $phone)
    {
        try {
            $data = [
                'to' => $phone,
                'msg' => $message,
                'json' => 1,
                'api_id' => isset($this->SpCur->sms_api_password) ? $this->SpCur->sms_api_password : env('SMS_API_PASSWORD')
            ];
            if (env('SMS_SENDER', null)) {
                $data['from'] = isset($this->SpCur->sms_sender) ? $this->SpCur->sms_sender : env('SMS_SENDER');
            }
            $result = file_get_contents('https://sms.ru/sms/send?' . http_build_query($data));
        } catch (\Exception $e) {
            HandlerError::index($e);
        }
    }

    public function SMSC_RU($message, $phone)
    {
        try {
            $data = [
                'mes' => $message,
                'phones' => $phone,
                'login' => isset($this->SpCur->sms_api_login) ? $this->SpCur->sms_api_login : env('SMS_API_LOGIN'),
                'psw' => isset($this->SpCur->sms_api_password) ? $this->SpCur->sms_api_password : env('SMS_API_PASSWORD'),
                'charset' => 'utf-8'
            ];
            if (env('SMS_SENDER', null)) {
                $data['from'] = isset($this->SpCur->sms_sender) ? $this->SpCur->sms_sender : env('SMS_SENDER');
            }
            $result = file_get_contents('https://smsc.ru/sys/send.php?' . http_build_query($data));
        } catch (\Exception $e) {
            HandlerError::index($e);
        }
    }

    public function WEB_SMS_KZ($message, $phone)
    {
        try {
            $body = json_encode([
                'client_message_id' => rand(1, 999999999),
                'sender' => isset($this->SpCur->sms_sender) ? $this->SpCur->sms_sender : env('SMS_SENDER'),
                'recipient' => $phone,
                'message_text' => $message,
                'priority' => 2
            ]);

            $client = new Client([
                'base_uri' => 'https://api.kcell.kz/app/smsgw/rest/v2/',
                'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
                'body' => $body,
                'auth' => [isset($this->SpCur->sms_api_login) ? $this->SpCur->sms_api_login : env('SMS_API_LOGIN'),
                    isset($this->SpCur->sms_api_password) ? $this->SpCur->sms_api_password : env('SMS_API_PASSWORD')]
            ]);
            $client->request('POST', 'messages');
        } catch (\Exception $e) {
            \Log::info('SMS_SEND: ' . $e->getCode() . ' ' . $e->getMessage());
        }
    }

    public function TURBOSMS_UA($message, $phone)
    {
        // Подключаемся к серверу
        $client = new \SoapClient('http://turbosms.in.ua/api/wsdl.html');

        // Данные авторизации
        $auth = [
            'login' => isset($this->SpCur->sms_api_login) ? $this->SpCur->sms_api_login : env('SMS_API_LOGIN'),
            'password' => isset($this->SpCur->sms_api_password) ? $this->SpCur->sms_api_password : env('SMS_API_PASSWORD')
        ];

        // Результат авторизации
        //echo $result->AuthResult . PHP_EOL;
        // Получаем количество доступных кредитов
        //$result = $client->GetCreditBalance();
        //echo $result->GetCreditBalanceResult . PHP_EOL;

        // Авторизируемся на сервере
        $result = $client->Auth($auth);
        $sms = [
            'sender' => isset($this->SpCur->sms_sender) ? $this->SpCur->sms_sender : env('SMS_SENDER'),
            'destination' => '+' . preg_replace('/[^0-9.]+/', '', $phone),
            'text' => iconv('windows-1251', 'utf-8', $message)
        ];
        $result = $client->SendSMS($sms);
    }

    public function ZADARMA($message, $phone)
    {
        try {
            $api = new Api(isset($this->SpCur->sms_api_login) ? $this->SpCur->sms_api_login : env('SMS_API_LOGIN'),
                isset($this->SpCur->sms_api_password) ? $this->SpCur->sms_api_password : env('SMS_API_PASSWORD'));
            $api->sendSms('+' . preg_replace('/[^0-9.]+/', '', $phone), $message, 
                isset($this->SpCur->sms_sender) ? $this->SpCur->sms_sender : env('SMS_SENDER'));
        } catch (ApiException $e) {
            HandlerError::index($e);
        }
    }

    public function ROCKETSMS_BY($message, $phone)
    {
        $message = array(
            "username" => isset($this->SpCur->sms_api_login) ? $this->SpCur->sms_api_login : env('SMS_API_LOGIN'),
            "password" => isset($this->SpCur->sms_api_password) ? $this->SpCur->sms_api_password : env('SMS_API_PASSWORD'),
            "phone" => $phone,
            "text" => $message
        );

        $messageQuery = http_build_query($message);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://api.rocketsms.by/simple/send');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $messageQuery);

        $result = @json_decode(curl_exec($ch), true);

        $response = curl_exec($ch);
        curl_close($ch);
    }    

    public function BEELINE($message, $phone)
    {
        try {
            $target = $phone;
            $sender = isset($this->SpCur->sms_sender) ? $this->SpCur->sms_sender : env('SMS_SENDER');
            $login = isset($this->SpCur->sms_api_login) ? $this->SpCur->sms_api_login : env('SMS_API_LOGIN');
            $pass = isset($this->SpCur->sms_api_password) ? $this->SpCur->sms_api_password : env('SMS_API_PASSWORD');

            $sms= new BEELINE($login, $pass, 'a2p-sms-https.beeline.ru');
            $result = $sms->post_message($message, $target, $sender);
        } catch (\Exception $e) {
            \Log::info('SMS_SEND: ' . $e->getCode() . ' ' . $e->getMessage());
        }
    }

    public function BAMBOOGROUP($message, $phone)
    {
        try {
            $login = isset($this->SpCur->sms_api_login) ? $this->SpCur->sms_api_login : env('SMS_API_LOGIN');
            $pass = isset($this->SpCur->sms_api_password) ? $this->SpCur->sms_api_password : env('SMS_API_PASSWORD');
            $smsline = new BAMBOOGROUP($login, $pass, "https://api.smsline.by");

            $result = $smsline -> MessageSingleChannel(
                "sms", array(
                    "target" => isset($this->SpCur->sms_sender) ? $this->SpCur->sms_sender : env('SMS_SENDER'),
                    "msisdn" => $phone,
                    "text" => $message,
                )
            );

        } catch (\Exception $e) {
            \Log::info('SMS_SEND: ' . $e->getCode() . ' ' . $e->getMessage());
        }
    }
}