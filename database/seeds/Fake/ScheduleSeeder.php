<?php

use App\Models\Bus;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\ScheduleDay;
use App\Models\Tour;
use App\Services\Tour\DuplicateService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run()
    {
        Schedule::where('id', '!=', 'a')->delete();
        Tour::where('id', '!=', 'a')->delete();
        
        $backMonth = Carbon::now()->subMonth();

        $routes = Route::all();
        $buses = Bus::with('driver')->get();
        foreach ($routes as $route) {
            $timeStart = 6;
            $timeFinish = 7;
            for ($i = 1; $i < 10; $i++) {
                $bus = $buses->random();
                $schedule = Schedule::create([
                    'date_start' => $backMonth->format('Y-m-d'). $timeStart . ':00:00',
                    'date_finish' => $backMonth->copy()->addMonths(2)->format('Y-m-d'). $timeFinish . ':00:00',
                    'route_id' => $route->id,
                    'repeat' => 1,
                    'bus_id' => $bus->id,
                ]);
                $scheduleDays = [];
                for ($j = 1; $j < 6; $j++) {
                    $scheduleDays[] = new ScheduleDay([
                        'day' => $j,
                        'price' => 10,
                        'driver_id' => $bus->driver->id,
                    ]);
                }
                $schedule->scheduleDays()->saveMany($scheduleDays);

                $tours = [];
                $diffInDays = $schedule->date_start->diffInDays($schedule->date_finish);
                $status = Tour::STATUS_ACTIVE;
                $tourDuplicate = DuplicateService::index(null, $bus->driver->id, $bus->id, $route->id, $timeStart . ':00:00', $schedule->date_start->format('d.m.Y'));
                if ($tourDuplicate) $status = Tour::STATUS_DUPLICATE;
                for ($j = 0; $j <= $diffInDays; $j++) {
                    $currentDay = $schedule->date_start->addDays($j);
                    if ($currentDay->dayOfWeek != 6 && $currentDay->dayOfWeek != 0) {
                        $tours[] = new Tour([
                            'bus_id' => $schedule->bus_id,
                            'route_id' => $schedule->route_id,
                            'driver_id' => $bus->driver->id,
                            'price' => 10,
                            'date_start' => $currentDay->format('d.m.Y'),
                            'time_start' => $timeStart . ':00',
                            'status' => $status,
                        ]);
                    }
                }

                $schedule->tours()->saveMany($tours);
                $timeStart++;
                $timeFinish++;
            }
        }
    }
}
