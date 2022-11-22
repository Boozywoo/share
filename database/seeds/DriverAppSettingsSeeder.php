<?php

use Illuminate\Database\Seeder;
use App\Models\DriverAppSetting;

class DriverAppSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $drivers = DriverAppSetting::firstOrCreate(['id' => 1], ['time_show_driver' => 2], ['is_see_passeger_phone' => 0],
         ['is_accept_cashless_payment' => 0], ['is_change_price' => 0], ['was_calling' => 0], ['notification' => 'push'],
          ['is_cancel' => 1], ['is_see_statistics' => 0], ['is_see_pay' => 0], ['is_see_map' => 0], ['is_display_cities' => 1],
          ['is_display_streets' => 1], ['is_display_stations' => 1], ['is_display_finished_button' => 0], ['is_display_utc' => 0],
          ['default_code' => 'by'], ['is_show_both_directions' => 0]);
        
    }
}
