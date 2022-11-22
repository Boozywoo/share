<?php

namespace App\Observers;

use App\Models\Driver;
use App\Models\Token;

class DriverObserver
{
    public function saved(Driver $driver)
    {
        $changed = $driver->getDirty();
        $changedBusId = array_get($changed, 'bus_id');

//		if ($changedBusId) {
//			Driver::where('id', '!=', $driver->id)
//				->filter(['bus_id' => $changedBusId])
//				->update(['bus_id' => null]);
//		}
    }

    public function creating(Driver $driver)
    {
        $driver->status = $driver->status == Driver::STATUS_SYSTEM ? Driver::STATUS_SYSTEM : Driver::STATUS_ACTIVE;
        $driver->reputation = Driver::REPUTATION_NEW;
    }

    public function created(Driver $driver)
    {
        do {
            $token = str_random(32);
        } while (Token::whereApiToken($token)->first());
        $driver->token()->create(['api_token' => $token]);
    }
}