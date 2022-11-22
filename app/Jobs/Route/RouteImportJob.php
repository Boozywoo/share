<?php

namespace App\Jobs\Route;

use App\Models\City;
use App\Models\Route as RouteModel;
use App\Models\Station;
use App\Models\Street;
use App\Services\Log\TelegramLog;
use App\Services\Prettifier;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RouteImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $file;

    public function __construct($fileName)
    {
        $this->file = storage_path('app/app/' . $fileName);
    }

    public function handle()
    {

        try {
            \Excel::load($this->file, function ($reader) {
                $sheets = $reader->get(); // get first sheet
                $sheets_name = $reader->getSheetNames();

                foreach ($sheets as $key=>$sheet) {
                    $routeName = $sheets_name[$key];//$sheet->getTitle();
                    $stations = [];
                    foreach ($sheet->toArray() as $item) {
                        if (isset($item['gorod']) && $item['gorod']) {
                            $city = $this->getCity($item['gorod']);
                            if (isset($item['ulitsa']) && $item['ulitsa']) {
                                $street = $this->getStreet($city->id, $item['ulitsa']);
                                if (isset($item['ostanovka']) && $item['ostanovka']) {
                                    $status = in_array($item['status_ostanovki'], ['Сбор', 'сбор']) ? Station::STATUS_COLLECT : Station::STATUS_ACTIVE;
                                    $station = $this->getStation($city->id, $street->id, $item['ostanovka'], $status);
                                    $stations[] = ['station' => $station, 'item' => $item];
                                }
                            }
                        } else break;
                    }
                    $route = $this->getRoute($stations, $routeName);
                    $routeStations = $this->prepareRouteData($stations);
                    $route->stations()->sync($routeStations);
                }
            });
        } catch (\Exception $e) {

            TelegramLog::telegram('RouteImportJob: ' . $e->getMessage());
            \Log::error($e->getMessage());
        }
    }

    public function prepareRouteData($stations)
    {
        $data = [];
        $order = 1;
        $interval = 0;

        foreach ($stations as $station) {
            $time = $this->getStationInterval($station) ? $this->getStationInterval($station) : 0;
            $interval += $time;
            $data[$station['station']->id] = ['order' => $order++, 'time' => $time, 'interval' => $interval];
        }
        return $data;
    }

    public function getCity($name)
    {
        $name = trim($name);
        if ($city = City::where('name', $name)->first())
            return $city;
        else return City::create([
            'name' => $name,
            'name_tr' => Prettifier::Transliterate($name),
            'status' => City::STATUS_ACTIVE
        ]);
    }

    public function getStreet($city_id, $name)
    {
        if ($street = Street::where('name', $name)->first())
            return $street;
        else return Street::create([
            'city_id' => $city_id,
            'name' => $name,
        ]);
    }

    public function getStation($city_id, $street_id, $name, $status)
    {
        if ($station = Station::where('name', $name)
            ->where('city_id', $city_id)
            ->where('street_id', $street_id)
            ->first())
            return $station;
        else return Station::create([
            'city_id' => $city_id,
            'street_id' => $street_id,
            'name' => $name,
            'name_tr' => Prettifier::Transliterate($name),
            'latitude' => '27.537097',
            'longitude' => '53.901717',
            'status' => $status

        ]);
    }

    public function getRoute($stations, $routeName)
    {
        if ($route = RouteModel::where('name', $routeName)->first())
            return $route;
        else {
            return RouteModel::create([
                'name' => $routeName,
                'name_tr' => Prettifier::Transliterate($routeName),
                'status' => RouteModel::STATUS_ACTIVE,
                'interval' => $this->getRouteInterval($stations),
                'is_international' => true,
            ]);
        }
    }

    public function getRouteInterval($stations)
    {
        $interval = 0;
        foreach ($stations as $station)
            $interval += $this->getStationInterval($station);
        return $interval;
    }

    public function getStationInterval($station)
    {
        if (isset($station['item'])) {
            $item = $station['item'];
            if (isset($item['raznitsamin'])) {
                return $item['raznitsamin'];
            } elseif (isset($item['raznitsa'])) {
                return $item['raznitsa'];
            }
        }
        return 0;
    }
}
