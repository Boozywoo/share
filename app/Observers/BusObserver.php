<?php

namespace App\Observers;

use App\Models\Bus;
use App\Models\Tour;
use Carbon\Carbon;

class BusObserver
{
    public function creating(Bus $bus)
    {
        $bus->status = $bus->status == Bus::STATUS_SYSTEM ? Bus::STATUS_SYSTEM : Bus::STATUS_ACTIVE;
    }

    public function updated(Bus $bus)
    {
        $changed = $bus->getDirty();
        $changedStatus = array_get($changed, 'status');

        if ($changedStatus) {
//            dd($changedStatus);
            if ($changedStatus == Bus::STATUS_DISABLE) {
                $bus->tours()->filter([
                    'date_to' => Carbon::now()->subDay()->format('Y-m-d'),
                    'status' => Tour::STATUS_ACTIVE,
                ])->update(['status' => Tour::STATUS_DISABLE]);
            }

            if ($bus->getOriginal('status') == Bus::STATUS_DISABLE && $changedStatus == Bus::STATUS_ACTIVE) {
                $bus->tours()->filter([
                    'date_to' => Carbon::now()->subDay()->format('Y-m-d'),
                    'status' => Tour::STATUS_DISABLE,
                ])->update(['status' => Tour::STATUS_ACTIVE]);
            }
            if ($bus->getOriginal('status') == Bus::STATUS_OF_REPAIR && $changedStatus == Bus::STATUS_ACTIVE) {
                $bus->tours()->filter([
                    'date_to' => Carbon::now()->subDay()->format('Y-m-d'),
                    'status' => Tour::STATUS_REPAIR,
                ])->update(['status' => Tour::STATUS_ACTIVE]);
            }
            if ($changedStatus == Bus::STATUS_REPAIR) {
                $tours = $bus->tours()->update(['status' => Tour::STATUS_REPAIR]);
            }
        }
    }
}
