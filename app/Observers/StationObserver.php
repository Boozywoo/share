<?php

namespace App\Observers;

use App\Models\Station;

class StationObserver
{
	public function creating(Station $station)
	{
		//$station->status = Station::STATUS_ACTIVE;
	}
}