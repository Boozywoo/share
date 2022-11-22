<?php

namespace App\Services\Social;

use App\Models\Status;
use App\Models\Order;
use App\Services\Prettifier;
use Carbon\Carbon;

class SocialToOrderService
{
    public static function index($order, $tour, $price)
    {
        $isSocialDate = $order->client && $order->client->date_social ? false : true; // если дата "Соц. статус действует до" не проставлена, то действует бессрочно

        if (!$isSocialDate) {
            // Проверка не истекла ли дата соц. статуса
            $isSocialDate = Carbon::now()->timestamp <= $order->client->date_social->timestamp;
        }

        if ($order->client && $isSocialDate) {
            $socialStatus = $order->client->socialStatus;
            if ($socialStatus && $socialStatus->status === Status::STATUS_ACTIVE) {
                $socialId = $order->client->status_id;
                if($socialStatus->apply_to_all_orders && $order->source == Order::SOURCE_OPERATOR){
                    $orderPlaces = $order->orderPlaces;
                    
                    $isRouteStatus = \DB::table('route_status')
                        ->where('status_id', $order->client->socialStatus->id)
                        ->where('route_id', $order->tour->route->id)
                        ->first();

                    if ($orderPlaces && $isRouteStatus) {
                        foreach($orderPlaces as $orderPlace) {
                            $priceOrderPlaceOld = $orderPlace->price;

                            if ($order->client->socialStatus->is_percent) {
                                $priceOrderPlaceNew = Prettifier::percent($priceOrderPlaceOld, $order->client->socialStatus->percent);
                            } else {
                                $priceOrderPlaceNew = $priceOrderPlaceOld - $order->client->socialStatus->value;
                            }

                            $orderPlace->status_id = $socialId;
                            $orderPlace->price = $priceOrderPlaceNew;
                            $orderPlace->status_old_price = $priceOrderPlaceOld;
                            $orderPlace->save();

                            $order->price = $price - $priceOrderPlaceOld + $priceOrderPlaceNew;
                            //$order->social_status_confirm = $order->client->date_social >= $tour->date_start ? 1 : 0;
                        }
                    }
                } else {
                    $orderPlace = $order->orderPlaces->first();
                    
                    $isRouteStatus = \DB::table('route_status')
                        ->where('status_id', $order->client->socialStatus->id)
                        ->where('route_id', $order->tour->route->id)
                        ->first();

                    if ($orderPlace && $isRouteStatus) {
                        $priceOrderPlaceOld = $orderPlace->price;

                        if ($order->client->socialStatus->is_percent) {
                            $priceOrderPlaceNew = Prettifier::percent($priceOrderPlaceOld, $order->client->socialStatus->percent);
                        } else {
                            $priceOrderPlaceNew = $priceOrderPlaceOld - $order->client->socialStatus->value;
                        }

                        $orderPlace->status_id = $socialId;
                        $orderPlace->price = $priceOrderPlaceNew;
                        $orderPlace->status_old_price = $priceOrderPlaceOld;
                        $orderPlace->save();

                        $order->price = $price - $priceOrderPlaceOld + $priceOrderPlaceNew;
                        //$order->social_status_confirm = $order->client->date_social >= $tour->date_start ? 1 : 0;
                    }
                }
                
            }
        }

        return $order;
    }
}
