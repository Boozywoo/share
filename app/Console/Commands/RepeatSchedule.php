<?php

namespace App\Console\Commands;

use App\Models\Schedule;
use App\Models\Tour;
use Illuminate\Console\Command;
use Carbon\Carbon;

class RepeatSchedule extends Command
{
    protected $signature = 'repeat:schedule';

    public function handle()
    {

        Schedule::where('repeat', 1)
            ->whereStatus(Schedule::STATUS_ACTIVE)
            ->chunkById(100, static function ($schedules) {
                /** @var Schedule[] $schedules */
                foreach ($schedules as $schedule) {
                    $timeStart = $schedule->date_start->format('H:i');
                    $timeFinish = $schedule->date_finish->format('H:i');

                    $nextDay = $schedule->date_finish->addDay();
                    $scheduleDay = $schedule->scheduleDays->where('day', $nextDay->dayOfWeek)->first();
                    $date_time_start = Carbon::createFromFormat('d.m.Y H:i:s', $nextDay->format('d.m.Y').' '.$timeStart . ':00');
                    if ($scheduleDay && isset($schedule->route)) {
                        Tour::create([
                            'schedule_id' => $schedule->id,
                            'bus_id' => $schedule->bus_id,
                            'route_id' => $schedule->route_id,
                            'driver_id' => $scheduleDay->driver_id,
                            'reservation_by_place' => $scheduleDay->reservation_by_place,
                            'price' => $scheduleDay->price,
                            'date_start' => $nextDay->format('d.m.Y'),
                            'time_start' => $timeStart,
                            'time_finish' => $timeFinish,
                            'date_time_start' => $date_time_start,
                            'date_time_finish' => $date_time_start->copy()->addMinutes($schedule->route->interval),
                            'date_finish' => $date_time_start->copy()->addMinutes($schedule->route->interval)->format('d.m.Y'),
                            'status' => Tour::STATUS_ACTIVE,
                            'is_collect' => $schedule->is_collect,
                        ]);
                    }
                    $schedule->date_finish = $nextDay;
                    $schedule->save();
                }
            });

        //Schedule::where('repeat', 1)->update(['date_finish' => $nextDay->format('Y-m-d')])
    }
}
