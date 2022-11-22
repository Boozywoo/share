<?php

namespace App\Http\Controllers\Driver;

use App\Models\Driver;

class SettingsController extends Controller 
{
    public function displayCities() {
        $driver = Driver::find(request('driver_id')); 

        $driver->is_display_cities = request('is_checked');
        $driver->save();
    }

    public function displayStreets() {
        $driver = Driver::find(request('driver_id')); 

        $driver->is_display_streets = request('is_checked');
        $driver->save();
    }

    public function displayStations() {
        $driver = Driver::find(request('driver_id')); 

        $driver->is_display_stations = request('is_checked');
        $driver->save();
    }

    public function displayUTC() {
        $driver = Driver::find(request('driver_id')); 

        $driver->is_display_utc = request('is_checked');
        $driver->save();
    }

    public function displayButtonFinished() {
        $driver = Driver::find(request('driver_id')); 

        $driver->is_display_finished_button = request('is_checked');
        $driver->save();
    }

    public function changeCode() {
        $driver = Driver::find(\Auth::guard('driver')->user()->id);

        $driver->default_code = request('default_code');
        $driver->save();
    }
    
}
