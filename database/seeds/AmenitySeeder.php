<?php

use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $amenity = [
            [
                'name' => 'WIFI',
                'status' => 'active',
                'icon' => 'wifi.png'
            ],
            [
                'name' => 'Телевизор',
                'status' => 'active',
                'icon' => 'tv.png'
            ],
            [
                'name' => '220W',
                'status' => 'active',
                'icon' => 'electric.png'
            ],
            [
                'name' => 'Кофе',
                'status' => 'active',
                'icon' => 'coffee.png'
            ],
            [
                'name' => 'Кондиционер',
                'status' => 'active',
                'icon' => 'fan.png'
            ],
            [
                'name' => 'WC',
                'status' => 'active',
                'icon' => 'wc.png'
            ],
        ];

        $isset = \App\Models\Amenity::where('name', '=', 'WC')->first();
        $image = ["images" => [
            "image" => [
                "is_new" => ["1"],
                "order" => [""],
                "main" => "0"
            ]]];
        foreach ($amenity as $value) {
            $amenity = \App\Models\Amenity::updateOrCreate(array_only($value, ['name', 'status']));
            $image["images"]["image"]["path"] = [public_path('/assets/admin/images/amenities/green/' . $value['icon'])];
            $amenity->syncImages($image);
        }

    }
}
