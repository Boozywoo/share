<?php

namespace App\Observers;

use App\Models\Coupon;

class CouponObserver
{
	public function creating(Coupon $coupon)
	{
		$coupon->status = Coupon::STATUS_ACTIVE;
	}
}