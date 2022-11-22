<?php

use Illuminate\Database\Seeder;
use App\Models\SiteSetting;

class SiteSettingsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'button_color' => '#19a158',
            'background_color' => '#666d78',
            'font_color' => '#fcfcfc',
            'font_size' => 15,
            'border_radius' => 10,
            'opacity'=> 0.9,
        ];

        $settings = SiteSetting::firstOrCreate($data);
    }
}
