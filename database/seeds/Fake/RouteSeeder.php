<?php

use App\Models\City;
use App\Models\Street;
use App\Models\Route;
use App\Models\Station;
use App\Models\User;
use Illuminate\Database\Seeder;

class RouteSeeder extends Seeder
{
    public function run()
    {
        Route::where('id', '!=', 'a')->delete();
        Street::where('id', '!=', 'a')->delete();
        City::where('id', '!=', 'a')->delete();


        $cityBorisov = City::create([
            'name' => 'Борисов',
        ]);

        $streetBorisov = Street::create([
            'name' => 'Чапаева',
            'city_id' => $cityBorisov->id,

        ]);

        $stationBorisov = Station::create([
            'name' => 'Борисовский вокзал',
            'city_id' => $cityBorisov->id,
            'street_id' => $streetBorisov->id,
            'latitude' => '28.502822',
            'longitude' => '54.223420'
        ]);

        $cityZod = City::create([
            'name' => 'Жодино',
        ]);

        $streetZod = Street::create([
            'name' => 'Кирова',
            'city_id' => $cityZod->id,
        ]);

        $stationZod = Station::create([
            'name' => 'Жодинский вокзал',
            'city_id' => $cityZod->id,
            'street_id' => $streetZod->id,
            'latitude' => '28.344592',
            'longitude' => '54.121724'
        ]);

        $citySmol = City::create([
            'name' => 'Смолевичи',
        ]);
        $streetSmol = Street::create([
            'name' => 'Ленина',
            'city_id' => $citySmol->id,
        ]);

        $stationSmol = Station::create([
            'name' => 'Смоливический вокзал',
            'city_id' => $citySmol->id,
            'street_id' => $streetSmol->id,
            'latitude' => '28.072680',
            'longitude' => '54.042591'
        ]);

        $cityMinsk = City::create([
            'name' => 'Минск',
        ]);
        $streetMinsk = Street::create([
            'name' => 'Минск',
            'city_id' => $cityMinsk->id,
        ]);

        $stationMinsk = Station::create([
            'name' => 'Минскский вокзал',
            'city_id' => $cityMinsk->id,
            'street_id' => $streetMinsk->id,
            'latitude' => '27.537097',
            'longitude' => '53.901717'
        ]);

        $routeOne = Route::create([
            'name' => 'Борисов-Минск',
            'interval' => 60
        ]);
        $routeOne->stations()->sync([
            $stationBorisov->id => ['order' => 1, 'time' => 0, 'interval' => 0],
            $stationZod->id => ['order' => 2, 'time' => 15, 'interval' => 15],
            $stationSmol->id => ['order' => 3, 'time' => 25, 'interval' => 40],
            $stationMinsk->id => ['order' => 4, 'time' => 20, 'interval' => 60]
        ]);

        $routeTwo = Route::create([
            'name' => 'Минск-Борисов',
            'interval' => 60
        ]);
        $routeTwo->stations()->sync([
            $stationMinsk->id => ['order' => 1, 'time' => 0, 'interval' => 0],
            $stationSmol->id => ['order' => 2, 'time' => 20, 'interval' => 20],
            $stationZod->id => ['order' => 3, 'time' => 25, 'interval' => 45],
            $stationBorisov->id => ['order' => 4, 'time' => 15, 'interval' => 60]
        ]);

        $user = User::first();
        $user->routes()->sync([$routeOne->id, $routeTwo->id]);
    }
}
