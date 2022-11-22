<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 020 20.06.19
 * Time: 16:25
 */

namespace App\Services\Sms\Providers;


use App\Services\Sms\ISmsProvider;

class WEB_SMS_UKR extends AbstractSmsProvider implements ISmsProvider
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