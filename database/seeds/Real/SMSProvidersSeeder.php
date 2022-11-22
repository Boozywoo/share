<?php

use App\Models\SmsProvider;
use Illuminate\Database\Seeder;

class SMSProvidersSeeder extends Seeder
{
    public function run()
    {
        SmsProvider::create([
          'name'=>'A1 (Bel)',
          'number_prefix'=>'375',
          'sms_send' => 'true',
          'sms_sender' => 'marsh',
          'sms_api_login' => 'george.pleschenko@gmail.com',
          'sms_api_password' => 'r6C3rXophV',
          'default' => true,
          'active' => true
        ]);

        SmsProvider::create([
          'name'=>'Kyivstar (Ukr)',
          'number_prefix'=>'380',
          'sms_send' => 'true',
          'sms_sender' => 'marsh2',
          'sms_api_login' => '2george.pleschenko@gmail.com',
          'sms_api_password' => 'xxxxxxxx',
          'default' => false,
          'active' => true
        ]);
    }
}