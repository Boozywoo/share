<?php

use App\Models\Config;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    public function run()
    {

        $sms = [
            ['type' => 'sms', 'key' => 'sms_send', 'value' => true],
            ['type' => 'sms', 'key' => 'sms_sender', 'value' => ''],
            ['type' => 'sms', 'key' => 'is_latin', 'value' => ''],
            ['type' => 'sms', 'key' => 'sms_api_login', 'value' => ''],
            ['type' => 'sms', 'key' => 'sms_api_password', 'value' => '']
        ];

        foreach ($sms as $value) {
            Config::create([
                'type' => $value['type'],
                'key' => $value['key'],
                'value' => $value['value']
            ]);
        }

    }
}