<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 014 14.06.19
 * Time: 16:10
 */

namespace App\Services\Sms\Providers;


use App\Models\SmsLog;

class AbstractSmsProvider
{
    protected $data;
    protected $client;
    protected $checkSms;

    public function sendRequest($url, $method = 'get')
    {
        $url = $url . http_build_query($this->data);
        return file_get_contents($url);
    }

    public function getCheckData($limit = 1000)
    {
        $this->checkSms = SmsLog::whereNotIn('status',SmsLog::SMS_NO_CHECK_STATUSES)
            ->limit($limit)
            ->get();
    }
}