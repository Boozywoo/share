<?php

use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\Street;
use App\Models\Station;
use App\Models\Route;
use App\Models\User;

class MinskTransRouteSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cityMoscow = $flight = City::firstOrCreate(['name' => 'Москва'], ['name' => 'Москва', 'name_tr' => 'Moskva']);

        $streetMoscow = Street::firstOrCreate([
            'name' => 'пр-д Стратонавтов',
            'city_id' => $cityMoscow->id,
        ]);

        $stationMoscow = Station::firstOrCreate([
            'name' => 'Москва (Тушинская)',
            'city_id' => $cityMoscow->id,
            'street_id' => $streetMoscow->id,
            'latitude' => '37.439613',
            'longitude' => '55.827180',
            'status' => Station::STATUS_ACTIVE
        ]);

        $cityGolicyno = $flight = City::firstOrCreate(['name' => 'Голицыно'], ['name' => 'Голицыно', 'name_tr' => 'Golicyno']);

        $streetGolycino = Street::firstOrCreate([
            'name' => 'Минское шоссе',
            'city_id' => $cityGolicyno->id,
        ]);

        $stationGolicyno = Station::firstOrCreate([
            'name' => 'Минское шоссе',
            'city_id' => $cityGolicyno->id,
            'street_id' => $streetGolycino->id,
            'latitude' => '36.9956003',
            'longitude' => '55.6025653',
            'status' => Station::STATUS_ACTIVE
        ]);

        $cityMinsk = $flight = City::firstOrCreate(['name' => 'Минск'], ['name' => 'Минск', 'name_tr' => 'Minsk']);

        $streetMinsk = Street::firstOrCreate([
            'name' => 'ул. Бобруйская',
            'city_id' => $cityMinsk->id,
        ]);

        $stationMinsk = Station::firstOrCreate([
            'name' => 'Автовокзал',
            'city_id' => $cityMinsk->id,
            'street_id' => $streetMinsk->id,
            'latitude' => '27.5519444',
            'longitude' => '53.8905726',
            'status' => Station::STATUS_ACTIVE
        ]);

        $routeOne = Route::firstOrCreate([
            'name' => 'Москва-Минск',
            'interval' => 600
        ]);

        $routeOne->stations()->sync([
            $stationMoscow->id => ['order' => 1, 'time' => 0, 'interval' => 0],
            $stationGolicyno->id => ['order' => 2, 'time' => 180, 'interval' => 180],
            $stationMinsk->id => ['order' => 3, 'time' => 420, 'interval' => 600]
        ]);

        $routeTwo = Route::firstOrCreate([
            'name' => 'Минск-Москва',
            'interval' => 600
        ]);
        $routeTwo->stations()->sync([
            $stationMinsk->id => ['order' => 1, 'time' => 0, 'interval' => 0],
            $stationGolicyno->id => ['order' => 2, 'time' => 180, 'interval' => 180],
            $stationMoscow->id => ['order' => 3, 'time' => 420, 'interval' => 600],
        ]);

        $user = User::first();
        $user->routes()->sync([$routeOne->id, $routeTwo->id], false);
    }
}
