<?php

use App\Models\SmsConfig;
use Illuminate\Database\Seeder;

class SmsConfigSeeder extends Seeder
{
    public function run()
    {

        $fields = [
            ['key' => 'from', 'show' => true],
            ['key' => 'booking', 'show' => true],
            ['key' => 'to', 'show' => true],
            ['key' => 'date', 'show' => true],
            ['key' => 'auto', 'show' => true],
            ['key' => 'places', 'show' => true],
            ['key' => 'places_count', 'show' => true],
            ['key' => 'price', 'show' => true],
            ['key' => 'driver_phone', 'show' => true],
            ['key' => 'driver_name', 'show' => true],
            ['key' => 'ticket', 'show' => true],
            ['key' => 'info', 'show' => true],
            ['key' => 'pay_link', 'show' => true],
        ];

        foreach ($fields as $key => $value) {
            SmsConfig::firstOrCreate([
                'orderby' => ++$key,
                'key' => $value['key'],
                'show' => $value['show']
            ]);
        }

    }
}