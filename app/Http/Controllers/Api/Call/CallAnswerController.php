<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 026 26.06.19
 * Time: 10:13
 */

namespace App\Http\Controllers\Api\Call;



use App\Services\Call\CallClient;

class CallAnswerController
{
    public function index()
    {
        $client = new CallClient();
        $client->webHookAnswer(request()->all());
    }
}