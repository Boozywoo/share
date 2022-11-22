<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 027 27.06.19
 * Time: 19:24
 */

namespace App\Services\Call\Providers;


use App\Services\Call\ICallProvider;

class OUR_ASTERISK extends AbstractCallProvider implements ICallProvider
{
    public function webHookAnswer($data)
    {
        $timeShow = isset($data['time_show']) ? $data['time_show'] : 15;
        $this->pusher($data['number'], $data['sip'], $timeShow);
    }

    public function webHookHangUp($data)
    {

    }

    public function webHookIncoming($data)
    {

    }

    public function outCall($extNumber, $phoneClient)
    {
        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, 'http://178.172.236.218/out/outc.php?phone=' . $phoneClient . '&code=' . $extNumber);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $out = curl_exec($curl);
            curl_close($curl);
        }
    }
}