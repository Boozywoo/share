composer dump-autoload<?php

use Illuminate\Database\Seeder;
use App\Models\Driver;

class DriversTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $drivers = Driver::all();
        foreach ($drivers as $driver){
            $driver->company_id = 1;
            $driver->save();
        }
    }
}
