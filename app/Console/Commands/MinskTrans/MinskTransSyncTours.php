<?php

namespace App\Console\Commands\MinskTrans;

use App\Models\Bus;
use App\Models\Client;
use App\Models\Driver;
use App\Models\Integration;
use App\Models\Order;
use App\Models\OrderPlace;
use App\Models\Route;
use App\Models\Tour;
use App\Services\Integrations\AvtovokzalRu\AvtovokzalRuService;
use App\Services\Order\StoreOrderService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MinskTransSyncTours extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'minsk_trans:sync_tours';

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
        $this->Moscow_Minsk();
    }

    public function Moscow_Minsk()
    {
        $date = Carbon::now();
        $client = new AvtovokzalRuService();
        $route = Route::where('name', 'Москва-Минск')->first();
        $integration = Integration::where('slug', Integration::MOS_GOR_TRANS)->first();
        if ($route && $integration) {
            while (true) {
                $races = $client->get_races(1488000, 1488179, $date->format('Y-m-d'));
                if (!empty($races)) {
                    foreach ($races as $race) {
                        $data = [
                            'route_id' => $route->id,
                            'bus_id' => Bus::where('name', 'Системный (' . mb_substr($race->busInfo, '8', '2') . ' места)')->first()->id,
                            'driver_id' => Driver::where('full_name', 'Системный водитель')->first()->id,
                            'date_time_start' => $race->dispatchDate,
                            'date_start' => date('d.m.Y', strtotime($race->dispatchDate)),
                            'time_start' => date('H:i', strtotime($race->dispatchDate)),
                            'time_finish' => date('H:i', strtotime($race->arrivalDate)),
                            'date_finish' => date('d.m.Y', strtotime($race->arrivalDate)),
                            'date_time_finish' => $race->arrivalDate,
                            'price' => $race->price,
                            'status' => Tour::STATUS_ACTIVE,
                            'type_driver' => Tour::TYPE_DRIVER_NEW,
                            'integration_id' => $integration->id,
                            'integration_uid' => $race->uid,
                        ];

                        $tour = Tour::updateOrCreate(collect($data)->only(['integration_id', 'integration_uid'])->toArray(), $data);
                        $newPlaces = $tour->freePlacesCount - $race->freeSeatCount;

                        while ($newPlaces--) {
                            $this->createSystemOrder($tour);
                        }
                    }
                }
                if (empty($races)) break;
                $date->addDay();

            }
        }
        /*
             $points = $client->get_dispatch_points(); //Доступны пункты отправления
             $depots = $client->get_point_depots($points[0]->id); //доступные автовокзалы
             $arPoints = $client->get_arrival_points($points[0]->id, 'Минск'); //пункты высадки с пункта отправления
             $races = $client->get_races($depots[0]->id,$arPoints[0]->id, $dispatch_date);
        */

    }

    public function createSystemOrder($tour)
    {
        $data = [
            'client_id' => Client::where('first_name', 'Системный пользователь')->first()->id,
            'station_from_id' => $tour->route->stations->first()->id,
            'station_to_id' => $tour->route->stations->last()->id,
            'count_places' => 1,
            'tour_id' => $tour->id,
            'price' => $tour->price,
            'source' => 'mosgortrans',
            'status' => Order::STATUS_ACTIVE,
            'type' => Order::TYPE_WAITING,
            'reservation_by_place' => 0,
        ];
        $order = Order::create($data);
        OrderPlace::create([
            'order_id' => $order->id,
            'status' => Order::STATUS_ACTIVE,
            'price' => $tour->price,
        ]);
    }
}
