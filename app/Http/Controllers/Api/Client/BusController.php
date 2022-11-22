<?php

namespace App\Http\Controllers\Api\Client;

use App\Models\Bus;
use App\Models\MonitoringBus;
use App\Models\Route;
use App\Models\Token;
use App\Models\Tour;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use function foo\func;

class BusController extends Controller
{
    public function show(Request $request)
    {
        $bus = Bus::where('id', $request->bus_id)->first();
        $tour = Tour::where('id',$request->tour_id)->first();
        $places = $bus->template->templatePlaces->transform(function ($item) use ($tour) {
            $busy = $tour->reserved->contains('number', $item->number);
            return ['number' => $item->number, 'type' => $busy ? 'busy' : $item->type];
        });

        return [
            'id' => $bus->id,
            'name' => $bus->name,
            'name_tr' => $bus->name_tr,
            'number' => $bus->number,
            'template' => [
                'ranks' => $bus->template->ranks,
                'columns' => $bus->template->columns,
                'places' => $places,
            ]
        ];
    }

    public function location(Request $request)
    {
        $clientId = Token::where('api_token', $request->api_token)->first()->client_id;

        if (!$clientId) {
            //exception
        }

        $bus = Bus::where('id', $request->bus_id)->first();

        if (!$bus) {
            //exception
        }

        //test
        $routes = Route::with('stationsActive')->where('is_taxi', false)->where('status', Route::STATUS_ACTIVE)->get();

        $stations = collect([]);

        foreach ($routes as $route) {
            $stations = $stations->merge($route->stationsActive);
        }

//        $stations = $stations->unique('id')->map(function ($value) {
//            $latitude = $value->longitude;
//
//            $value->longitude = $value->latitude;
//            $value->latitude = $latitude;
//
//            return $value;
//        })->sortBy('id')->values();

//        $coordinate = $stations->random();
        $coordinate = MonitoringBus::whereBusId($bus->id)->orderBy('id', 'DESC')->select('latitude', 'longitude')->first();

        return \response()->json($coordinate);
    }
}
