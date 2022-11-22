<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 004 04.04.19
 * Time: 8:45
 */

namespace App\Services\Order;

use App\Models\Order as ModelOrder;
use App\Models\Order;
use App\Models\Station;
use Carbon\Carbon;

class DiscountReturnTicketService
{
    public static function index(ModelOrder $order, $pricePlace, $countPlace)
    {

        $between = [$order->created_at->startOfDay()->format('Y-m-d H:i:s'), $order->created_at->endOfDay()->format('Y-m-d H:i:s')];
        $stationCityFromIds = Station::where('city_id', $order->stationFrom->city->id)
            ->get()->pluck('id')->toArray();
        $stationCityToIds = Station::where('city_id', $order->stationTo->city->id)
            ->get()->pluck('id')->toArray();

        $returnOrder = Order::where('return_order_id', $order->id)->count();       // Является ли этот заказ обратным рейсом для другого заказа, если да, то применяем скидку
        if ($order->client_id) {
            $fromOrders = Order::where('client_id', $order->client_id)      // Места в заказах в обюратном направлении к текущему заказу
                ->where('status', Order::STATUS_ACTIVE)
                ->where(function ($query) {                                       // При заказе с сайта клиентом билет должен быть оплачен или заказан оператором в админке
                    $query->where(function ($query1) {
                        $query1->where('type_pay', Order::TYPE_PAY_SUCCESS)
                            ->where('source', 'site');
                    })
                        ->orWhere(function ($query1) {
                            $query1->where('source', 'operator');
                        });
                    })
                ->whereIn('station_from_id', $stationCityToIds)
                ->whereIn('station_to_id', $stationCityFromIds)
                ->whereBetween('created_at', $between)
                ->sum('count_places');

            $toOrders = Order::where('client_id', $order->client_id)        // Места в заказах в направлении текущего заказа, кроме самого эаказа
                ->where('status', Order::STATUS_ACTIVE)
                ->where(function ($query) {
                    $query->where(function ($query1) {
                        $query1->where('type_pay', Order::TYPE_PAY_SUCCESS)
                            ->where('source', 'site');
                    })
                        ->orWhere(function ($query1) {
                            $query1->where('source', 'operator');
                        });
                })
                ->whereIn('station_from_id', $stationCityFromIds)
                ->whereIn('station_to_id', $stationCityToIds)
                ->whereBetween('created_at', $between)
                ->where('id', '<>', $order->id)
                ->sum('count_places');
        } else {
            $fromOrders = 0;
            $toOrders = 0;
        }

        if (($fromOrders >= ($toOrders + $countPlace)) || $returnOrder) {
            $pricePlace = $order->tour->route->discount_return_ticket_type ?
                          $pricePlace - ($pricePlace * $order->tour->route->discount_return_ticket / 100) :
                          $pricePlace - $order->tour->route->discount_return_ticket;
            return round($pricePlace, env('ROUND_ORDER', 1));
        }
        return $pricePlace;
    }

    public static function simple(ModelOrder $order, $pricePlace)   {
        $returnOrder = Order::where('return_order_id', $order->id)->count();       // Является ли этот заказ обратным рейсом для другого заказа, если да, то применяем скидку
        if ($order->is_return_ticket || $returnOrder) {
            $pricePlace = $order->tour->route->discount_return_ticket_type ?
                $pricePlace - ($pricePlace * $order->tour->route->discount_return_ticket / 100) :
                $pricePlace - $order->tour->route->discount_return_ticket;
            return round($pricePlace, env('ROUND_ORDER', 1));
        }
        return $pricePlace;
    }
}