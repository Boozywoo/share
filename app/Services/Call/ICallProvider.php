<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 026 26.06.19
 * Time: 10:17
 */

namespace App\Services\Call;


interface ICallProvider
{
    public function outCall($extNumber, $phoneClient);

    public function webHookIncoming($data);

    public function webHookAnswer($data);

    public function webHookHangUp($data);
}