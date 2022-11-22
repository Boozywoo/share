<?php

namespace App\Services\Sms\Providers;


use App\Services\Sms\ISmsProvider;

class MTS extends AbstractSmsProvider implements ISmsProvider
{
    public function sendSms($phone, $message, $client = null, $order = null)
    {

    }

    public function getStatusSms($orderId)
    {

    }

    public function getBalance()
    {

    }

    public function checkStatus()
    {

    }

    public function getStatusBunkSms($bunk)
    {

    }
}