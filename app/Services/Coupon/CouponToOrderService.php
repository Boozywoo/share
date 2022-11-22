<?php

namespace App\Services\Coupon;

use App\Models\Coupon;
use App\Services\Prettifier;

class CouponToOrderService
{
    public static function index($order, $code)
    {
        $error = true;

        $coupon = Coupon::filter(['code' => $code])->active($order->tour)->first();
        if ($code && $coupon) {
            $couponId = $coupon->id;
            if ($order->coupon_id != $couponId) {
                $coupon->increment('uses');
                //if ($couponId) Coupon::find($couponId)->decrement('uses');
            }
            $error = false;
        } else {
            $couponId = null;
        }

        $order->coupon_id = $couponId;

        return [$order, $error];
    }

    protected static function saveSale($sales, $sale, $price)
    {
        $priceOld = $price;
        $pricePlace = Prettifier::percent($price, $sale->percent);
        $sales += [$sale->id => ['old_price' => $priceOld, 'new_price' => $pricePlace]];
        return [$sales, $pricePlace];
    }
}