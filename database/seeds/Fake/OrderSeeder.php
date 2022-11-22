<?php

use App\Models\Client;
use App\Models\Order;
use App\Models\OrderPlace;
use App\Models\Review;
use App\Models\Tour;
use App\Services\Order\StationIntervalsService;
use App\Services\Prettifier;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $tours = Tour::where('date_start', '<', Carbon::now()->addWeek())
            ->whereStatus(Tour::STATUS_ACTIVE)
            ->with('bus')
            ->get();

        $clients = Client::all();
        foreach ($tours as $tour) {
            $stationFromId = $tour->route->stations->first()->id;
            $stationToId = $tour->route->stations->last()->id;
            $countPlaces = $tour->bus->places;
            $companyId = $tour->bus->company_id;
            $places = collect($tour->bus->template->templatePlaces()->where('type', \App\Models\TemplatePlace::TYPE_NUMBER)->pluck('number'));
            for ($i = 1; $i <= $countPlaces - rand(0, 4); $i++) {
                list($stationFromInterval, $stationToInterval) = StationIntervalsService::index($tour->route_id, $stationFromId, $stationToId);
                $stationFromTime = Prettifier::prettifyTime($tour->time_start, $stationFromInterval);
                $stationToTime = Prettifier::prettifyTime($tour->time_start, $stationToInterval);
                $order = Order::create([
                    'tour_id' => $tour->id,
                    'client_id' => $clients->random()->id,
                    'price' => $tour->price,
                    'count_places' => 1,
                    'source' => Order::SOURCE_OPERATOR,
                    'type' => Order::TYPE_WAITING,
                    'status' => Order::STATUS_ACTIVE,
                    'confirm' => 1,
                    'places_with_number' => 1,
                    'old_places' => [
                        'price' => $tour->price,
                        'count_places' => 1,
                        'places' => [['number' => $places->first(), 'price' => $tour->price]]
                    ],
                    'station_from_id' => $stationFromId,
                    'station_to_id' => $stationToId,
                    'station_from_time' => $stationFromTime,
                    'station_to_time' => $stationToTime,
                ]);
                OrderPlace::create([
                    'number' => $places->first(),
                    'price' => $tour->price,
                    'order_id' => $order->id,
                ]);
                $places->forget($i - 1);
                if ($tour->date < Carbon::now()) {
                    Review::create([
                        'client_id' => $order->client_id,
                        'order_id' => $order->id,
                        'company_id' => $companyId,
                        'driver_id' => $tour->driver_id,
                        'rating' => rand(1,5),
                        'comment' => 'тестовый комментарий',
                    ]);
                }
            }
        }
    }
}
