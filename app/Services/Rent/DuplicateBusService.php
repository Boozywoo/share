<?php

namespace App\Services\Rent;

use App\Models\Tour;
use App\Models\Route;
use App\Services\Prettifier;
use Carbon\Carbon;

class DuplicateBusService
{
    public static function index($id, $busId, $timeStart, $dateStart, $timeFinish = null, $dateFinish = null)
    {
        $dateStart = date('Y-m-d', strtotime($dateStart));
        $dateFinish = date('Y-m-d', strtotime($dateFinish));
        $dateTimeStart = $dateStart.' '.$timeStart;
        $dateTimeFinish = $dateFinish.' '.$timeFinish;

        $tour = Tour::where('bus_id', $busId)
            ->where('id', '!=',$id)
            ->where('date_time_start','<',$dateTimeStart)
            ->where('date_time_finish','>',$dateTimeStart)
            ->first();
        if ($tour) return $tour;

        $tour = Tour::where('bus_id', $busId)
            ->where('id', '!=',$id)
            ->whereBetween('date_time_start',[$dateTimeStart,$dateTimeFinish])
            ->whereBetween('date_time_finish',[$dateTimeStart,$dateTimeFinish])
            ->first();
        if ($tour) return $tour;

        $tour = Tour::where('bus_id', $busId)
            ->where('id', '!=',$id)
            ->where('date_time_start','<',$dateTimeFinish)
            ->where('date_time_finish','>',$dateTimeFinish)
            ->first();
        return $tour;
    }
}