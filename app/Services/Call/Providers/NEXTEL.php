<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 027 27.06.19
 * Time: 19:02
 */

namespace App\Services\Call\Providers;


use App\Services\Call\ICallProvider;

class NEXTEL extends AbstractCallProvider implements ICallProvider
{
    public function webHookAnswer($data)
    {
        \Log::info(print_r($data, 1));
        if (isset($data['event']) && $data['event'] == 'CALL_ANSWER') {
            $this->pusher($data['call']['from'], $data['call']['to'][0], 15);
        }
    }

    public function webHookHangUp($data)
    {

    }

    public function webHookIncoming($data)
    {

    }

    public function outCall($extNumber, $phoneClient)
    {
        $client = new \GuzzleHttp\Client();
        $result = $client->post('https://cstat.nextel.com.ua:8443/tracking/api/phones/directCall', [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => env('CALL_KEY'),
            ],
            'form_params' => [
                'sip' => $extNumber,
                'number' => $phoneClient,
            ]
        ]);

        $result = json_decode($result->getBody());
        if ($result->status == 'Success') {
            return $this->responseOutCall(self::TYPE_SUCCESS);
        } else {
            $this->responseOutCall(self::TYPE_ERROR, $result->message);
        }
    }
}