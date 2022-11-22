<?php
/**
 * Created by PhpStorm.
 */

namespace App\Services\Pays;


use App\Models\Order;
use App\Models\Setting;
use GuzzleHttp\Client as HTTP;

class ServiceOnlineCheckBBV
{

    const BBV_URL = 'http://185.204.116.115:2253/apiv3/';
    
    public static function auth()
    {
        $data = ['username' => env('BBV_USERNAME'), 'password' => env('BBV_PASSWORD')];

        $auth = ServiceOnlineCheckBBV::gateway('auth/', $data);
        if (isset($auth->token)) {
            session(['bbv_token' => $auth->token]);
        }
        return $auth;
    }

    public static function openShift()
    {
        $auth = ServiceOnlineCheckBBV::gateway('shift/', []);
        return $auth;
    }

    public static function receipt(Order $order)
    {

        $data = [
            'request_uid' => $order->id,
            'currency' => 'BYN',
            'deposit_amount' => $order->price,
            'change_percent' => '0',
            'card_amount' => '0',
            'cash_amount' => $order->price,
            'another_amount' => '0',
            'positions' => 
                [
                    [
                    'name' => 'Проезд ' . $order->tour->route->name,
                    'tag' => 0,
                    'ean' =>  '0',
                    'operands' =>  '1*' . $order->price,
                    'amount' => $order->price,
                    'count' => '1',
                    'change_percent' => '0',
                    'change_amount' => '0',
                ],
            ],
            'change_amount' => '0',
        ];

        $receipt = ServiceOnlineCheckBBV::gateway('receipt/', $data);
        return $receipt;
    }

    public static function info()
    {
        return ServiceOnlineCheckBBV::gateway('info/user/', [], false);
    }

    public static function withdraw($amount)
    {
        $data = [
            'amount' => $amount,
            'currency' => 'BYN',
            ];
        $withdraw = ServiceOnlineCheckBBV::gateway('withdraw/', $data);
        return $withdraw;
    }

    public static function close()
    {
        return ServiceOnlineCheckBBV::gateway('zreport/', '{}');
    }

    private static function gateway($method, $data, $post = true)   {
        $client = new HTTP([
            'http_errors' => false,
            'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json', 
                'CashBox-Token' => env('BBV_TOKEN'), 'Token' => session('bbv_token')],
        ]);
        $options = $data == '{}' ? [] : ['json' => $data];
        try {
            $response = $post ? $client->post(self::BBV_URL.$method, $options) : $client->get(self::BBV_URL.$method, $options); 
            $result = json_decode($response->getBody());
            \Log::info(print_r($result, true), ['method' => $method]);
            return $result;

        } catch (Guzzle\Http\Exception\BadResponseException $e) {
            \Log::error('BBV ' . $method, (array)$e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

}
