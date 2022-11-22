<?php

namespace App\Console\Commands;

use App\Models\Route;
use App\Models\Station;
use App\Models\Order;
use Illuminate\Console\Command;

class ClearStations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:stations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $routes = Route::where('is_taxi', true)->get();
        $stationsBusy = [];
        foreach ($routes as $route) {
            $stations = $route->stations->where('status', Station::STATUS_COLLECT);
            foreach ($stations as $station) {
                $orderFrom = Order::with('tour')
                    ->where('station_from_id', $station->id)
                    ->whereHas('tour', function ($q) {
                        $q->where('date_start', '>=', date('Y-m-d'));
                    })
                    ->get();

                $orderTo = Order::with('tour')
                    ->where('station_to_id', $station->id)
                    ->whereHas('tour', function ($q) {
                        $q->where('date_start', '>=', date('Y-m-d'));
                    })
                    ->get();

                if (!$orderFrom->count() && !$orderTo->count())
                {
                    \DB::table('route_station')
                        ->where('route_id', $route->id)
                        ->where('station_id', $station->id)
                        ->delete();// удалить остановку из направления
                    Station::where('id', $station->id)->delete();
                }  else $stationsBusy[] = $station->id;
            }
        }

        //удалить остановки в статусе "СБОР" которые не в направлениях
        /*Station::whereNotIn('id', $stationsBusy)
            ->where('status', Station::STATUS_COLLECT)
            ->delete();*/
    }
}
