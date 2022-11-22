<?php

namespace App\Services\Tour;

use App\Models\Bus;
use App\Models\Driver;
use App\Models\Tour;
use App\Models\Route;
use App\Services\Prettifier;
use Carbon\Carbon;

class DuplicateService
{
    public static function index($id, $driverId, $busId, $routeId = null, $timeStart, $dateStart, $timeFinish = null)
    {
        if ($bus = Bus::find($busId)) {
            $busId = $bus->status == Bus::STATUS_SYSTEM ? null : $busId;
        }

        if ($driver = Driver::find($driverId)) {
            $driverId = $driver->status == Driver::STATUS_SYSTEM ? null : $driverId;
        }

        $route = $routeId ? Route::where('id', $routeId)->first() : null;
        if ($route)
            $timeFinish = Prettifier::prettifyTime($timeStart, $route->interval);
        elseif ($id)
            $timeFinish = Tour::where('id', $id)->first()->time_finish;

        $date_time_start = Carbon::createFromFormat('d.m.Y H:i:s', $dateStart.' '.$timeStart);
        $date_time_finish = $date_time_start->copy()->addMinutes($route->interval);
        $tour =  Tour::filter([
            'not_id' => $id,
            //'date' => Carbon::createFromFormat('d.m.Y', $dateStart)
        ])
            ->where('status', '!=', Tour::STATUS_DISABLE)
            ->where(function ($q) use ($driverId, $busId) {
                $q->whereDriverId($driverId)->orWhere('bus_id', $busId);
            })
            ->where(function($query) use ($date_time_start, $date_time_finish){
                $query->whereRaw("(date_time_start BETWEEN '".$date_time_start."' AND '".$date_time_finish."' OR date_time_finish BETWEEN '".$date_time_start."' AND '".$date_time_finish."')")
                    ->orWhereRaw("(date_time_start <= '".$date_time_start."' AND date_time_finish >= '".$date_time_finish."')");
                })
            ->first();
            return $tour;
    }

    public static function forceEdit($driverDuplicateTour, $busDuplicateTour)
    {
        if (($driverDuplicateTour and $busDuplicateTour) && ($driverDuplicateTour->id == $busDuplicateTour->id)) {
            $driverDuplicateTour->status = Tour::STATUS_DUPLICATE;
            $driverDuplicateTour->type_duplicate = Tour::TYPE_DUPLICATE_ALL;
            $driverDuplicateTour->save();
        } elseif ($driverDuplicateTour) {
            $driverDuplicateTour->status = Tour::STATUS_DUPLICATE;
            $driverDuplicateTour->type_duplicate = Tour::TYPE_DUPLICATE_DRIVER;
            $driverDuplicateTour->save();
        } elseif ($busDuplicateTour) {
            $busDuplicateTour->status = Tour::STATUS_DUPLICATE;
            $busDuplicateTour->type_duplicate = Tour::TYPE_DUPLICATE_BUS;
            $busDuplicateTour->save();
        }
    }
}