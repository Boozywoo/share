<?php

namespace App\Services\Geo;

use Exception;
use App\Models\Order;
use App\Models\Tour;

class GeoService
{

    public static function getCoordinates($address) 
    {
        if (!$geocode = @file_get_contents('https://geocode-maps.yandex.ru/1.x/?lang=ru_RU&apikey=' . env('YANDEX_API_KEY') . '&geocode=' . urlencode($address))) {
            \Log::info('Geocoding request failed');
            return [null, null];
        }

        $xml = new \SimpleXMLElement($geocode);

        $xml->registerXPathNamespace('ymaps', 'http://maps.yandex.ru/ymaps/1.x');
        $xml->registerXPathNamespace('gml', 'http://www.opengis.net/gml');

        $result = $xml->xpath('/ymaps:ymaps/ymaps:GeoObjectCollection/gml:featureMember/ymaps:GeoObject/gml:Point/gml:pos');

        if (isset($result[0])) {
            return explode( ' ', $result[0] ); // Широта и долгота
        } else {
            return [null, null];
        }

    }

    public static function getRouteLink(Order $order) {
        $fromPoint = empty($order->custom_address_from) ? [$order->stationFrom->longitude,  $order->stationFrom->latitude] : GeoService::getCoordinates($order->custom_address_from);
        $toPoint = empty($order->custom_address_to) ? [$order->stationTo->longitude,  $order->stationTo->latitude] : GeoService::getCoordinates($order->custom_address_to);
        return 'https://yandex.ru/maps/?rtext=' . $fromPoint[1] . ',' . $fromPoint[0] . '~' . $toPoint[1] . ',' . $toPoint[0] . '&rtt=auto';
    }

    public static function getTourPointsLink(Tour $tour) {
        $airport = $tour->route->airport();
        $link = 'https://yandex.ru/maps/?rtext=' . $airport->latitude . ',' . $airport->longitude;
        $orders = $tour->ordersReady;

        foreach ($orders as $order) {
            $order->distToAirport = round(GeoService::getDistance($airport->latitude, $airport->longitude, $order->latitude, $order->longitude), 2);
        }
        
        foreach ($orders->sortBy('distToAirport') as $key => $order) {
            if ($order->longitude) {
                $link .= '~' . $order->latitude . ',' . $order->longitude;
            }
        }
        return $link . '&rtt=auto';
    }

    public static function getDistance($latitude1, $longitude1, $latitude2, $longitude2){
        $earth_radius = 6371;

        $dLat = deg2rad($latitude2 - $latitude1);
        $dLon = deg2rad($longitude2 - $longitude1);

        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * asin(sqrt($a));
        $d = $earth_radius * $c;

        return $d;
    }

}