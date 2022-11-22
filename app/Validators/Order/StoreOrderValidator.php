<?php

namespace App\Validators\Order;

use App\Models\Order;
use App\Models\Setting;
use App\Models\Station;
use App\Models\Tour;
use App\Repositories\SelectRepository;
use App\Services\Order\FragmentationOrder;
use Carbon\Carbon;

class StoreOrderValidator
{
    const TYPE_CLIENT = 'client';
    const TYPE_OPERATOR = 'operator';

    public static function tour($tour, $type = self::TYPE_CLIENT)
    {
        $error = false;

        $setting = Setting::first();

        if(!$setting->is_change_in_completed_tours) {
            if ($tour->status != Tour::STATUS_ACTIVE) $error = 'Рейс должен иметь активный статус';
        }

        if ($type == self::TYPE_CLIENT) {
            if (!in_array($tour->type_driver, [Tour::TYPE_DRIVER_NEW, Tour::TYPE_DRIVER_COLLECTION, Tour::TYPE_DRIVER_WAY])) $error = true;
            if ($tour->date_start < Carbon::now()->format('Y-m-d')) $error = true;
            if ($tour->date_start == Carbon::now()->format('Y-m-d') && $tour->time_start < Carbon::now()->format('H:i:s')) $error = true;
        }

        return $error;
    }

    public static function order($order, $tourId = null)
    {
        $error = false;

        if ($tourId && $order->tour_id != $tourId) $error = true;
        if ($order->type != Order::TYPE_NO_COMPLETED) $error = true;
        if ($order->status != Order::STATUS_ACTIVE) $error = true;

        return $error;
    }

    public static function stations($routeId, $stationFromId, $stationToId, SelectRepository $selectRepository)
    {
        $error = $stationFromId && $stationToId ? false : true;

        $stations = $selectRepository->stations($routeId);
        if (!isset($stations[$stationFromId])) $error = true;

        $stations = $selectRepository->stations($routeId, $stationFromId);
        if (!isset($stations[$stationToId])) $error = true;

        return $error;
    }

    public static function places($tour, $order, $places)
    {
        $freePlacesCount = $tour->freePlacesCount;
        if ($order && $order->tour_id == $tour->id) $freePlacesCount += $order->count_places;
        $countPlaces = count($places);

        /* if ($order->tour->reservation_by_place && !isset($order->orderPlaces)) return 'выберите хотя бы одно место';
         elseif  ($order->tour->reservation_by_place && isset($order->orderPlaces) && $order->orderPlaces->count()) return 'выберите хотя бы одно место';*/

        if ($order->status != Order::STATUS_RESERVE && $freePlacesCount < $countPlaces) return 'Свободно только ' . $tour->freePlacesCount . ' мест';
        
        foreach ($places as $place) {
            if ($place && $tour->reserved->contains('number', $place)) return 'Место №' . $place . ' уже занято.';
            if ($place && $tour->reservation_by_place && !$tour->bus->template->templatePlaces->contains('number', $place)) return 'У этого автобуса нет места №' . $place;
        }

        return false;
    }

    public static function places_new(Tour $tour, $order, $places, $stationFrom = null, $stationTo = null)
    {
        $reserved = $tour->reserved;

        if ($stationFrom && $stationTo && !$tour->route->is_taxi) {
            $freePlacesCount = $tour->freePlacesBetween($stationFrom, $stationTo);
            if (config('app.FRAGMENTATION_RESERVED')) {
                $freePlaces = $tour->freePlacesBetween($stationFrom, $stationTo, 'places');
                $freePlaces = empty($freePlaces) ? [] : $freePlaces;
                $reserved = $reserved->whereNotIn('number', $freePlaces);
            }
        } else {
            $freePlacesCount = $tour->freePlacesCount;
        }

        if ($order && $order->tour_id === $tour->id) {
            $freePlacesCount += $order->count_places;
        }

        $countPlaces = count($places);

        if ($order->status != Order::STATUS_RESERVE && $freePlacesCount < $countPlaces) {
            return 'Свободно только ' . $tour->freePlacesCount . ' мест';
        }

        foreach ($places as $place) {
            if ($place && $reserved->contains('number', $place)) {
                return 'Место №' . $place . ' уже занято.';
            }

            if ($place && !$tour->bus->template->templatePlaces->contains('number', $place)) {
                return 'У этого автобуса нет места №' . $place;
            }
        }

        return false;
    }

    public static function limitDayRoute($order, $client = null)
    {
        if ($client) $clientId = $client->id;
        elseif ($order->client) $clientId = $order->client->id;
        else return false;

        $countOrder = Order::where('id', '<>', $order->id)
            ->whereHas('tour', function ($q) use ($order) {
                $q->where('route_id', $order->tour->route_id);
                $q->where('date_start', $order->tour->date_start);
            })
            ->where('client_id', $clientId)
            ->where('status', Order::STATUS_ACTIVE)
            ->count();

        if (Setting::first()->limit_one_order_route && $countOrder) {
            return trans('validation.limit_order_route');
        }
        return false;
    }

    public static function limitTour($order, $client = null)
    {
        if ($client) $clientId = $client->id;
        elseif ($order->client) $clientId = $order->client->id;
        else return false;

        $countOrder = Order::where('id', '<>', $order->id)
            ->where('tour_id', $order->tour_id)
            ->where('client_id', $clientId)
            ->where('status', Order::STATUS_ACTIVE)
            ->count();

        if ($countOrder) {
            return trans('validation.limit_tour');
        }
        return false;
    }
}
