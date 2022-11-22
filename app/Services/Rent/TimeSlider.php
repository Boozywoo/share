<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 030 30.08.18
 * Time: 21:35
 */

namespace App\Services\Rent;


use App\Models\Bus;
use App\Models\Tour;

class TimeSlider
{
    public static function index($buses, $rents, $date)
    {
        $date = date("Y-m-d", strtotime($date));
        $timeSliders = [];
        foreach ($buses as $key => $bus) {
            $timeSlider = [];
            foreach ($rents->where('bus_id', $bus->id) as $rent)
            {
                $timeStart = ($date == $rent->date_start->format('Y-m-d')) ? $rent->time_start : '00:00:00';
                $timeFinish = ($date == $rent->date_finish->format('Y-m-d')) ? $rent->time_finish : '23:59:59';
                $timeSlider['time'][] = $timeStart . '-' . $timeFinish;
                $timeSlider['id'][] = $rent->id;
                $timeSlider['driver_id'][] = $rent->driver_id;
            }

            $nameKey = 'timeslider';
            $nameKey .= $key + 1;
            $timeSliders[$nameKey] = $timeSlider;
        }
        return json_encode($timeSliders);
    }

    public static function findBus($timeSlider, $buses)
    {
        $index = (int)filter_var($timeSlider, FILTER_SANITIZE_NUMBER_INT) - 1;
        return $buses[$index];
    }

    public static function setBusByTimeSlider($timeSlider, $data)
    {
        $buses = Bus::getRentBuses();
        $bus = self::findBus($timeSlider, $buses);
        $data['bus_id'] = $bus->id;
        unset($data['timeSliderBus']);
        return $data;
    }
}