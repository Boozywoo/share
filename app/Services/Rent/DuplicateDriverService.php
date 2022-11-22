<?php

namespace App\Services\Rent;

use App\Models\Tour;
use App\Models\Route;
use App\Services\Prettifier;
use Carbon\Carbon;

class DuplicateDriverService
{
    public static function index($id, $driverId, $timeStart, $dateStart, $timeFinish = null, $dateFinish = null)
    {
        $dateStart = date('Y-m-d', strtotime($dateStart));
        $dateFinish = date('Y-m-d', strtotime($dateFinish));
        $dateTimeStart = $dateStart.' '.$timeStart;
        $dateTimeFinish = $dateFinish.' '.$timeFinish;

        $tour = Tour::where('driver_id', $driverId)
            ->where('id', '!=',$id)
            ->where('date_time_start','<',$dateTimeStart)
            ->where('date_time_finish','>',$dateTimeStart)
            ->first();
        if ($tour) return $tour;

        $tour = Tour::where('driver_id', $driverId)
            ->where('id', '!=',$id)
            ->whereBetween('date_time_start',[$dateTimeStart,$dateTimeFinish])
            ->whereBetween('date_time_finish',[$dateTimeStart,$dateTimeFinish])
            ->first();
        if ($tour) return $tour;

        $tour = Tour::where('driver_id', $driverId)
            ->where('id', '!=',$id)
            ->where('date_time_start','<',$dateTimeFinish)
            ->where('date_time_finish','>',$dateTimeFinish)
            ->first();
        return $tour;

        /*$tour = \DB::table('tours')
            ->where('date_time_start','>=', )
            ->first();*/







        /*return Tour::filter([
            'not_id' => $id,
            //'date' => Carbon::createFromFormat('d.m.Y', $dateStart)
        ])
            ->where('status', '!=', Tour::STATUS_DISABLE)
            ->where(function ($q) use ($driverId, $busId) {
                $q->whereDriverId($driverId)->orWhere('bus_id', $busId);
            })
            ->where(function ($q) use ($route, $timeStart, $timeFinish, $dateStart, $dateFinish) {
                $q->where(function ($q) use ($route, $timeStart, $timeFinish, $dateStart, $dateFinish) {
                    $q->whereBetween('date_time_finish', [$dateStart.' '.Prettifier::prettifyTime($timeStart).':00', $dateFinish.' '.$timeFinish]);
                    dump([$dateStart.' '.Prettifier::prettifyTime($timeStart).':00', $dateFinish.' '.$timeFinish]);

                })->orWhere(function ($q) use ($route, $timeStart, $timeFinish, $dateStart, $dateFinish) {
//                    $q->whereBetween('time_start', [Prettifier::prettifyTime($timeStart, $route->interval, false), $timeStart]);
                    $q->whereBetween('date_time_start', [$dateStart.' '.Prettifier::prettifyTime($timeStart).':00', $dateFinish.' '.$timeFinish]);
                });
            })
            ->first();*/
    }
}