<?php

namespace App\Console\Commands;

use App\Models\Bus;
use App\Models\Repair;
use App\Models\Tour;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RepairBus extends Command
{
    protected $signature = 'repair:bus';

    public function handle()
    {
        //#Start
        $busesIds = Repair::ofStatus(Repair::STATUS_REPAIR)
            ->where('date_from', Carbon::now()->format('Y-m-d'))
            ->pluck('bus_id');
        $buses = Bus::filter(['buses' => $busesIds]);
        $buses->update(['status' => Bus::STATUS_REPAIR]);

        //#End
        $repairs = Repair::ofStatus(Repair::STATUS_REPAIR)->where('date_to', Carbon::now()->format('Y-m-d'));
        $busesIds = $repairs->pluck('bus_id');
        $repairs->update(['status' => Repair::STATUS_OF_REPAIR]);

        $buses = Bus::filter(['buses' => $busesIds]);
        $buses->update(['status' => Bus::STATUS_OF_REPAIR]);

        $tours = Tour::filter([
            'date_to' => Carbon::now()->format('Y-m-d'),
            'buses' => $busesIds,
            'status' => Tour::STATUS_REPAIR
        ]);
        $tours->update(['status' => Tour::STATUS_ACTIVE]);
    }
}
