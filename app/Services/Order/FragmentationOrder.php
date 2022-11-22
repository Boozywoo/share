<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 015 15.12.18
 * Time: 15:53
 */

namespace App\Services\Order;


use App\Models\Order;
use App\Models\OrderPlace;
use App\Models\Tour;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\DocBlock\Tags\Source;

class FragmentationOrder
{
    public static function setNumber(OrderPlace $place)
    {
        $isSetNumber = false;

        if (!$place->order->tour->reservation_by_place) {
            $isSetNumber = true;
        }

        if ($place->order->source == Order::SOURCE_DRIVER) {
            $isSetNumber = true;
        }

        if (empty($place->number)) {
            $isSetNumber = true;
        }

        if ($isSetNumber && $place->order->tour->route->is_taxi == false) {
            if ($number = array_values($place->order->tour->freePlacesBetween($place->order->station_from_id, $place->order->station_to_id, 'places'))[0] ?? false) {
                $place->number = $number;
            } else {
                $busPlaces = $place->order->tour->bus->template->templatePlaces->where('type', 'number')->pluck('number')->toArray();
                $busy = $place->order->tour->Reserved->pluck('number')->toArray();
                for ($placeNumber = 1; $placeNumber <= $place->order->tour->bus->places; $placeNumber++) {
                    if (!in_array($busPlaces[$placeNumber - 1], $busy)) {
                        $place->number = $place->order->status == Order::STATUS_ACTIVE ? $busPlaces[$placeNumber - 1] : '';
                        break;
                    }
                }
            }
        }
    }

    public static function setStationToFrom(OrderPlace $place)
    {
        if (!$place->order->tour->rent) {
            $place->station_from_id = $place->order->station_from_id;
            $place->station_to_id = $place->order->station_to_id;
            $timeStations = StationIntervalsService::index($place->order->tour->route->id, $place->order->station_from_id, $place->order->station_to_id);
            $place->start_min = $timeStations[0];
            $place->finish_min = $timeStations[1];
        }
    }

    public static function freePlaces(Tour $tour, $timeStations): Collection
    {
        return $tour->bus->template->templatePlaces
            ->where('type', \App\Models\TemplatePlace::TYPE_NUMBER)
            ->pluck('number')
            ->diff(self::busyPlaces($tour, $timeStations)->pluck('number'))
            ->values();
    }

    public static function searchFreePlaces(Tour $tour, $stationFromId, $stationToId, $getResponse = 'place')
    {
        if (!$tour->rent) {
            $timeStations = StationIntervalsService::index($tour->route->id, $stationFromId, $stationToId);
            return self::searchFreePlacesInterval($tour, $timeStations, $getResponse);
        }
        return false;
    }

    public static function searchCityFreePlaces(Tour $tour, $cityFrom, $cityTo, $getResponse = 'place')
    {
        if ($cityFrom && $cityTo) {
            $stationFromId = $tour->route->stations->where('city_id', $cityFrom)->last()->id;
            $stationToId = $tour->route->stations->where('city_id', $cityTo)->first()->id;
            $freePlaces = FragmentationOrder::searchFreePlaces($tour, $stationFromId, $stationToId, $getResponse);
            return empty($freePlaces) ? [] : $freePlaces->toArray();
        } elseif ($cityFrom) {
            $stationFromId = $tour->route->stations->where('city_id', $cityFrom)->last()->id;
            $stationToId = $tour->route->stations->last()->id;
            $freePlaces = FragmentationOrder::searchFreePlaces($tour, $stationFromId, $stationToId, $getResponse);
            return empty($freePlaces) ? [] : $freePlaces->toArray();
        }
        return [];
    }

    public static function searchFreePlacesInterval($tour, $timeStations, $getResponse = 'place', $excludeId = 0)
    {
        $orders = Order::filter([
            'tour_id' => $tour->id,
            'status' => Order::STATUS_ACTIVE,
        ])
            ->wherePull(0)
            ->where('id', '!=' , $excludeId)
            ->pluck('id')
            ->toArray();

        if ($tour->route->is_taxi)  {
            return false;
        }

        $busyPlaces = OrderPlace::whereIn('order_id', $orders)
            ->where(function ($query) use ($timeStations) {
                $query->whereBetween('start_min', self::sortTimeStations([$timeStations[0], $timeStations[1] - 1]));
                $query->orWhereBetween('finish_min', self::sortTimeStations([$timeStations[0] + 1, $timeStations[1]]));
        })->get()->pluck('number')->toArray();

        $places = OrderPlace::whereIn('order_id', $orders)
            ->where(function ($query) use ($timeStations) {
            $query->where('finish_min', '<=', $timeStations[0]);
            $query->Orwhere('start_min', '>=', $timeStations[1]);
        })
            ->whereNotIn('number', $busyPlaces)
            ->whereNotBetween('start_min', [$timeStations[0], $timeStations[1] - 1])
            ->whereNotBetween('finish_min', [$timeStations[0] + 1, $timeStations[1]])
            ->get();

        if ($places->count()) {
            if ($getResponse == 'places') {
                return $places->groupBy('number')->keys();
            } elseif ($getResponse == 'place') {
                return $places->first()->number;
            } elseif ($getResponse == 'count') return $places->groupBy('number')->count();
        }

        return false;
    }

    public static function busyPlaces($tour, $timeStations)
    {
        return OrderPlace::whereHas('order', function ($q) use ($tour) {
            $q->where('tour_id', $tour->id);
            $q->where('status', Order::STATUS_ACTIVE);
            $q->where('pull', false);
        })
            ->where(function ($query) use ($timeStations) {
                $query->whereBetween('start_min', self::sortTimeStations([$timeStations[0], $timeStations[1] - 1]));
                $query->orWhereBetween('finish_min', self::sortTimeStations([$timeStations[0] + 1, $timeStations[1]]));
            })
            ->get();
    }

    public static function sortTimeStations(array $timeStations): array
    {
        $sortedStations = $timeStations;
        sort($sortedStations);

        return $sortedStations;
    }
}
