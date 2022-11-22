<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/16/2017
 * Time: 7:26 PM
 */

namespace App\Services\Order;

class ChildPlaceService
{
    public static function index($order, $count)
    {
        $error = false;
        //$discount       = Setting::getField('discount_children')/100;
        $discount = $order->tour->route->discount_child ? $order->tour->route->discount_child / 100 : 0;
        $countChildren = $order->orderPlaces->where('is_child', true)->count();
        $countAddChild = $count - $countChildren;

        if ($countAddChild > 0) {
            $orderPlaces = $order->orderPlaces->where('is_child', false)->sortByDesc("id")->take($countAddChild);
            foreach ($orderPlaces as $place) {
                $price = ($place->sales && $place->sales->first() !== NULL) ?
                    $place->sales->first()->getOriginal()['pivot_old_price'] : $place->price;
                $place->price = $order->tour->route->discount_child_type ?
                                round($price * (1 - $discount), env('ROUND_ORDER', 1)) :
                                $price - $order->tour->route->discount_child;
                $place->is_child = true;
                $place->save();
            }
        } elseif ($countAddChild < 0) {
            $orderPlaces = $order->orderPlaces->where('is_child', true)->sortByDesc("id")->take(abs($countAddChild));
            foreach ($orderPlaces as $place) {
                $place->price = ($place->sales && $place->sales->first() !== NULL) ?
                    $place->sales->first()->getOriginal()['pivot_old_price'] :
                    $order->orderPlaces->where('is_child', false)->first()->price;
                $place->is_child = false;
                $place->save();
            }
        }
        $order->price = $order->orderPlaces->sum('price');
        return [$order, $error];
    }
}