<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 014 14.06.19
 * Time: 16:25
 */

namespace App\Services\Sms;


interface ISmsProvider
{
    public function sendSms($phone, $message, $client = null, $order = null);

    public function getStatusSms($orderId);

    public function getBalance();

    public function checkStatus();

    public function getStatusBunkSms($bunk);
}