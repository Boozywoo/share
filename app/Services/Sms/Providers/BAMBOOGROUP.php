<?php

namespace App\Services\Sms\Providers;

use Requests;

\Requests::register_autoloader();

class BAMBOOGROUP {

    public function checkStatus()
    {

    }

    private $login;
    private $password;
    private $url;


    public function __construct($login, $password, $url){
        $this->login = $login;
        $this->password = $password;
        $this->url = $url;
    }


    private function GetHash($text){;
        return hash_hmac("sha256", $text, $this->password);
    }


    private function PostRequest($url, $body, $signature){
        $headers = array(
            'Content-Type' => 'application/json',
            "Authorization-User" => $this->login,
            "Authorization" => "Bearer $signature"
        );

        $request = Requests::post($url, $headers, json_encode($body));

        return $request->body;
    }

    private function GetRequest($url, $signature){
        $headers = array(
            'Content-Type' => 'application/json',
            "Authorization-User" => $this->login,
            "Authorization" => "Bearer $signature"
        );

        $request = \Requests::get($url, $headers);

        return $request->body;
    }


    public function MessageSingleChannel($channel, $body){
        $requestUrl = "{$this->url}/v3/messages/single/{$channel}";
        $requestBody = json_encode($body);
        $signature = $this->GetHash("messagessingle$channel$requestBody");
        return $this->PostRequest($requestUrl, $body, $signature);
    }

    public function MessageMultiChannel($channel, $body){
        $requestUrl = "{$this->url}/v3/messages/multi/single/{$channel}";
        $requestBody = json_encode($body);
        $signature = $this->GetHash("messagesmultisingle$channel$requestBody");
        return $this->PostRequest($requestUrl, $body, $signature);
    }

    public function MessagesSingleChannel($channel, $body){
        $requestUrl = "{$this->url}/v3/messages/bulk/{$channel}";
        $requestBody = json_encode($body);
        $signature = $this->GetHash("messagesbulk$channel$requestBody");
        return $this->PostRequest($requestUrl, $body, $signature);
    }

    public function MessagesMultiChannel($channel, $body){
        $requestUrl = "{$this->url}/v3/messages/multi/bulk/{$channel}";
        $requestBody = json_encode($body);
        $signature = $this->GetHash("messagesmultibulk$channel$requestBody");
        return $this->PostRequest($requestUrl, $body, $signature);
    }

    public function MessageStatus($messageId){
        $requestUrl = "{$this->url}/v3/messages/$messageId";
        $signature = $this->GetHash("messages$messageId");
        return $this->GetRequest($requestUrl, $signature);
    }

    public function MessagesStatuses($body){
        ksort($body);
        $params = http_build_query($body);
        $paramsForSignature = implode(
            "",
            array_map(
                function ($v, $k) {
                    return sprintf("%s%s", $k, $v);
                },
                $body,
                array_keys($body)
            )
        );

        $requestUrl = "{$this->url}/v3/messages?$params";
        $signature = $this->GetHash("messages$paramsForSignature");
        return $this->GetRequest($requestUrl, $signature);
    }
};
