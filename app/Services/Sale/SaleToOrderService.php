<?php

namespace App\Services\Sale;

use App\Models\Order;
use App\Models\OrderPlace;
use App\Models\Sale;
use App\Models\Tour;
use App\Services\Order\DiscountReturnTicketService;
use App\Services\Prettifier;
use DB;

class SaleToOrderService
{
    public static function index(Order $order, Tour $tour, $places, $tourPrice)
    {
        $price = 0;
        $orderSuccess = ($order->client ? $order->client->orders->where('appearance', 1)->where('status', 'active')->count() : 0) + 1;

        $sales = $tour->route->sales->pluck('id');

        $oldPlaces = ['places' => []];

        $saleEach = Sale::query()->whereIn('id', $sales)->active($tour, Sale::TYPE_EACH)->first();
        $saleAtATime = Sale::query()->whereIn('id', $sales)->active($tour, Sale::TYPE_AT_A_TIME)->where('count', '<=', count($places))->orderBy('count', 'desc')->first();
        $saleBeginningWith = Sale::query()->whereIn('id', $sales)->active($tour, Sale::TYPE_BEGINNING_WITH)->where('count', '<=', $orderSuccess)->orderBy('count', 'desc')->first();

        $error = false;

        DB::beginTransaction();
        try {
            $oneTimeSale = false;
            foreach ($places as $place) {
                $sales = [];
                $pricePlace = $tourPrice;

                $isReturnTicket = false;
                if ($order->tour->route->discount_return_ticket) {
                    $newPrice = DiscountReturnTicketService::simple($order, $pricePlace);
                    if ($newPrice !== $pricePlace) {
                        $pricePlace = $newPrice;
                        $isReturnTicket = true;
                    }
                }

                if ($saleAtATime && count($places) >= $saleAtATime->count) {
                    list($sales, $pricePlace) = self::saveSale($sales, $saleAtATime, $pricePlace);
                }

                if (!$oneTimeSale && $saleEach && ($orderSuccess % $saleEach->count === 0) && isset($order->client) && $order->client->orders->where('status', '!=', Order::STATUS_DISABLE)->where('type', Order::TYPE_WAITING)->where('is_sale', true)->count() === 0) {
                    list($sales, $pricePlace) = self::saveSale($sales, $saleEach, $pricePlace);
                    $oneTimeSale = true;
                }

                if (!$oneTimeSale && $saleBeginningWith && $orderSuccess >= $saleBeginningWith->count) {
                    list($sales, $pricePlace) = self::saveSale($sales, $saleBeginningWith, $pricePlace);
                    $oneTimeSale = true;
                }

                $pricePlace = round($pricePlace, env('ROUND_ORDER', 1));

                if (!config('app.FRAGMENTATION_RESERVED') && $tour->reserved->contains('number', $place) && $tour->reservation_by_place) {
                    throw new SaleException('Место №' . $place . ' уже занято.');
                }

                $orderPlace = OrderPlace::create([
                    'order_id' => $order->id,
                    'number' => $place,
                    'price' => $pricePlace,
                    'is_return_ticket' => $isReturnTicket
                ]);
                $orderPlace->sales()->sync($sales);

                $oldPlaces['places'][] = ['number' => $place, 'price' => $pricePlace];
                $price += $pricePlace;
            }

            DB::commit();

            $order->price = $price;
            $order->count_places = count($places);

            count($sales) && $order->is_sale = true;
        } catch (SaleException $e) {
            $error = $e->getMessage();
            DB::rollBack();
        }

        return [$order, $oldPlaces, $error];
    }

    public static function tourPrice(Tour $tour, $placesCount)
    {
        $sales = $tour->route->sales->pluck('id');
        $saleAtATime = Sale::query()->whereIn('id', $sales)->active($tour, Sale::TYPE_AT_A_TIME)->where('count', '<=', $placesCount)->orderBy('count', 'desc')->first();
        $sales = [];
        $pricePlace = $tour->price;
        if ($saleAtATime && $placesCount >= $saleAtATime->count) {
            list($sales, $pricePlace) = self::saveSale($sales, $saleAtATime, $pricePlace);
        }
        $pricePlace = round($pricePlace, env('ROUND_ORDER', 1));
        
        return $placesCount*$pricePlace;
    }
    
    protected static function saveSale($sales, $sale, $price)
    {
        $priceOld = $price;
        //$pricePlace = Prettifier::percent($price, $sale->percent);
        if ($sale->is_percent) {
            $pricePlace = Prettifier::percent($price, $sale->value);
        } else {
            $pricePlace = $price - $sale->value;
            $pricePlace = $pricePlace >= 0.0 ? $pricePlace : 0.0;
        }

        $sales += [$sale->id => ['old_price' => $priceOld, 'new_price' => $pricePlace]];
        return [$sales, $pricePlace];
    }
}
