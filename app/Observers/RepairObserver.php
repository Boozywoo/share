<?php

namespace App\Observers;

use App\Models\Bus;
use App\Models\Repair;
use App\Models\Tour;
use Carbon\Carbon;

class RepairObserver
{
	public function saved(Repair $repair)
	{
		$changed = $repair->getDirty();
		$changedStatus = array_get($changed, 'status');

		if ($changedStatus) {
            if ($changedStatus !== Repair::STATUS_REPAIR) {
                // Розкомментировать после окончания блока Ремонт
//                $repair->bus()->update(['status' => Bus::STATUS_OF_REPAIR]);

/*                $repair->bus->tours()->filter([
                    'date_start' => Carbon::now(),
                    'date_finish' => $repair->date_finish,
                    'status' => Tour::STATUS_REPAIR
                ])->update(['status' => Tour::STATUS_ACTIVE]);*/
            }
        }

    }

/*    public function created(Repair $repair)
    {
        if ($repair->status == Repair::STATUS_REPAIR) {
            $status_tour = Tour::STATUS_REPAIR;
            $status_bus = Bus::STATUS_REPAIR;

            if ($repair->date_from->diffInDays(Carbon::now()) == 0) {
                // Розкомментировать после окончания блока Ремонт
                $repair->bus()->update(['status' => $status_bus]);
            }
        }

    }*/

    /*	public function creating(Repair $repair)
        {
            $repair->status = Repair::STATUS_REPAIR;
        }*/
}
