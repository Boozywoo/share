<?php

namespace App\Services\Tour;

use App\Models\Order;
use App\Models\OrderPlace;
use App\Models\Tour;
use App\Services\Order\FragmentationOrder;
use App\Services\Order\StationIntervalsService;

class TourPullService
{
    public static function from($ordersIds, Tour $tour)
    {
        $filter = [
            'tour_id' => $tour->id
        ];

        if ($ordersIds) {
            $filter['ids'] = $ordersIds;
        }

        $orders = Order::whereIn('status', [Order::STATUS_ACTIVE, Order::STATUS_RESERVE])->filter($filter)->with('orderPlaces')->orderBy('places_with_number', 'desc')->get();

        $ordersError = [];
        $freePlacesCount = $tour->freePlacesCount;

        foreach ($orders as $order) {
            if ($freePlacesCount < $order->count_places || $freePlacesCount === 0) {
                if ($order->orderPlaces->count()) {
                    $ordersError = array_pad($ordersError, count($ordersError) + $order->orderPlaces->count(), $order->id);
                } else {
                    $ordersError[] = $order->id;
                }

                continue;
            }

            foreach ($order->orderPlaces as $place) {
                if ($place->number) {
                    if ($tour->reserved->contains('number', $place->number)) {
                        $ordersError[] = $order->id;
                        continue 2;
                    }

                    if (!$tour->bus->template->templatePlaces->contains('number', $place->number)) {
                        $ordersError[] = $order->id;
                        continue 2;
                    }
                }
            }

            if (!$order->status === Order::STATUS_RESERVE) {
                continue;
            }

            if ($order->orderPlaces->count()) {
                $freePlacesCount -= $order->orderPlaces->count();

                $freePlaces = FragmentationOrder::freePlaces($tour, StationIntervalsService::index($tour->route->id, $order->station_from_id, $order->station_to_id));

                foreach ($order->orderPlaces as $orderPlace) {
                    $orderPlace->number = ($orderPlace->number && !$freePlaces->contains('number', $orderPlace->number)) ? $orderPlace->number : $freePlaces->shift();
                    $orderPlace->save();
                }
            } else {
                $freePlacesCount -= 1;

                OrderPlace::create([
                    'order_id' => $order->id,
                    'status' => Order::STATUS_ACTIVE,
                    'price' => $tour->price,
                    'number' => FragmentationOrder::freePlaces($tour, StationIntervalsService::index($tour->route->id, $order->station_from_id, $order->station_to_id))->first(),
                ]);

                $order->price = $tour->price;
                $order->count_places = 1;
                $order->save();
            }

            $order->update(['pull' => 0, 'status' => Order::STATUS_ACTIVE]);
        }

        return $ordersError;
    }
}
