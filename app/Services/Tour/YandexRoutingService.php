<?php

namespace App\Services\Tour;

use App\Models\Tour;
use App\Models\Station;
use Carbon\Carbon;

class YandexRoutingService
{
    public static function build(Tour $tour)        // Строит оптимальный маршрут по всем адресам трансферного рейса с помощью Яндекс-навигации
    {
        $tour = Tour::findOrFail($tour->id);
        $orders = $tour->ordersReady;
        $airport = Station::findOrFail(3);
        $serviceDuration = 3;   // Минут на ожидание пассажиров на каждой точке
        $timeStart = Carbon::createFromFormat('Y-m-d H:i:s', $tour->date_time_start);
        $timeFinish = Carbon::createFromFormat('Y-m-d H:i:s', $tour->date_time_finish);
        //$timeWindow = $timeStart->format('c') . '/' . $timeStart->copy()->addHours(1)->format('c');
        $timeWindow = $timeFinish->copy()->subHours(2)->format('c') . '/' . $timeFinish->format('c');
        $timeWindowAirport = $timeFinish->copy()->subMinutes(15)->format('c') . '/' . $timeFinish->format('c');


        $locations = [];
        if ($tour->route->flight_type == 'arrival') {
            $data['vehicles'][] = ['id' => 1, "return_to_depot" => false, "start_at" => "airport", "visit_depot_at_start" => false];
            //$timeWindow = $timeStart->format('c') . '/' . $timeStart->copy()->addHours(5)->format('c');
        }
        if ($tour->route->flight_type == 'departure') {
            $data['vehicles'][] = ['id' => 1, "return_to_depot" => true, 'start_at'=> $orders->first()->transferAddress(), "finish_at" => "airport", "visit_depot_at_start" => false];
            //$timeWindow = $timeStart->copy()->subHours(5)->format('c') . '/' . $timeStart->format('c') ;
        }
        $data['depots'][] = ['id' => "1", 'point' => ['lat' => floatval($airport->latitude), 'lon' => floatval($airport->longitude)], 'time_window' => $timeWindowAirport, 'flexible_start_time' => true];
        $locations[] = ['id' => 'airport', 'point' => ['lat' => floatval($airport->latitude), 'lon' => floatval($airport->longitude)], 'time_window' => $timeWindow, 'type' => 'garage'];
        
        $data['options'] =  ["minimize" => "cost", "time_zone" => 3, "quality" => "normal", "date" => Carbon::now()->format('Y-m-d'), "routing_mode" => "driving"];

        foreach ($orders as $order) {
            $locations[] = ['id' => $order->transferAddress(), 'point' => ['lat' => floatval($order->latitude), 'lon' => floatval($order->longitude)],
                'ref' => strval($order->id), 'time_window' => $timeWindow, 'type' => 'pickup', 'service_duration_s' => $serviceDuration*60];
        }
        $data['locations'] = $locations;
        $data['locations'][1]['type'] = 'garage';
        unset($data['locations'][1]['delivery_deadline']);

        $payload = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://courier.yandex.ru/vrs/api/v1/add/mvrp?apikey='.env('YANDEX_MAPS_API_KEY'));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = json_decode(curl_exec($ch));
        curl_close($ch);

        if (empty($result->error)){
            $delay = intval($result->status->estimate ?? time() - time());
            $tour->update(['mvrp_id' => $result->id]);
            return ['result' => 'success', 'id' => $result->id, 'url' => 'https://yandex.ru/courier/mvrp-map#'.$result->id.'?route=0', 'delay' => $delay];
        } else {
            return ['result' => 'error', 'message' => $result->error->message];
        }
    }


}