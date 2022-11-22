<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 026 26.06.19
 * Time: 10:18
 */

namespace App\Services\Call\Providers;


use App\Services\Call\ICallProvider;

class BINOTEL extends AbstractCallProvider implements ICallProvider
{
    public function webHookAnswer($data)
    {
        if (isset($data['method']) && $data['method'] == 'answeredTheCall') {
            $this->pusher(substr($data['externalNumber'], -11), $data['internalNumber'], 15);
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
        try {
            $client = new \denostr\Binotel\Client(env('CALL_KEY'), env('CALL_PASSWORD'));
            $result = $client->calls->extToPhone(['ext_number' => $extNumber, 'phone_number' => $phoneClient]);
            return $this->responseOutCall(self::TYPE_SUCCESS);
        } catch (\Exception $e) {
            return $this->responseOutCall(self::TYPE_ERROR, $e->getMessage());
        }
    }
}