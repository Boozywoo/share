<?php

namespace App\Observers;

use App\Models\City;

class CityObserver
{
	public function creating(City $city)
	{
		$city->status = City::STATUS_ACTIVE;
	}
}