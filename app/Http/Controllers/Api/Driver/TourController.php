<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Api\ApiController;
use App\Models\Route;
use App\Models\Setting;
use App\Models\DriverAppSetting;
use App\Models\Tour;
use App\Repositories\SelectRepository;
use App\Services\Prettifier;
use App\Validators\Tour\StoreTourValidator;
use Carbon\Carbon;
use App\Services\Client\StoreClientService;

class TourController extends ApiController
{
    protected $select;

    public function __construct(SelectRepository $selectRepository)
    {
        $this->select = $selectRepository;
    }

    public function index()
    {
        $setting = Setting::first();
        $d_a_setting = DriverAppSetting::first();
        $dateFrom = Carbon::createFromTimestamp(request('date_from', Carbon:: now()->timestamp))->format('Y-m-d');
        $dateTo = Carbon::createFromTimestamp(request('date_to', Carbon::now()->timestamp))->format('Y-m-d');

        $filter = [
            'driver_id' => auth()->user()->id,
            'status' => Tour::STATUS_ACTIVE,
            'between' => ['dateFrom' => $dateFrom, 'dateTo' => $dateTo]
        ];

        $data = [];
        $tours = Tour::filter($filter)
            ->with(['route', 'bus', 'ordersReady'])
            ->orderBy('date_start')
            ->orderBy('time_start')
            ->get();

        $addHours = $d_a_setting->time_show_driver ? preg_replace('/[^0-9.]+/', '', $d_a_setting->time_show_driver) : 2;
        foreach ($tours as $tour) {
            $tourTimestamp = strtotime($tour->date_start->format('Y-m-d') . ' ' . $tour->time_start);
            if (Carbon::now()->addHours($addHours)->timestamp > $tourTimestamp) {
                $data[] = [
                    'id' => $tour->id,
                    'type_driver' => $tour->type_driver,
                    'route' => $tour->route->name,
                    'date' => $tour->date_start->day . ' ' . trans('dates.month.long.' . $tour->date_start->month) . ',' . $tour->prettyTimeStart,
                    'bus' => $tour->bus->number . ',' . $tour->bus->name,
                    'bus_places' => $tour->bus->places,
                    'clients_count' => $tour->busyPlacesCount,
                ];
            }
        }

        return $this->responseSuccess(['tours' => $data]);
    }

    public function show($id)
    {
        $tour = $this->getTourActive($id);

        if (!$tour) return $this->responseError();
        if ($tour->type_driver == Tour::TYPE_DRIVER_NEW) return $this->responseError(['message' => 'Рейс недоступен. Начните посадку!']);

        $tour->load(['ordersReady.orderPlaces', 'ordersReady.client']);

        $data = [
            'route' => $tour->route->name,
            'date' => $tour->date_start->day . ' ' . trans('dates.month.long.' . $tour->date_start->month) . ',' . $tour->prettyTimeStart,
            'price' => $tour->ordersReady->sum('totalPrice'),
            'clients_count' => $tour->BusyPlacesCount,
            'orders' => [],
            'stations' => [],
        ];

        $stationsFrom = [];
        $stationsTo = [];
        $stationFromKey = 0;
        $stationToKey = 0;

        foreach ($tour->ordersReady as $order) {
            $orderArr = [
                'id' => $order->id,
                'comment' => $order->comment,
            ];

            if ($order->client) {
                $orderArr += [
                    'station_from_name' => $order->stationFrom->city->name . ' ' . $order->stationFrom->name,
                    'station_to_name' => $order->stationTo->city->name . ' ' . $order->stationTo->name,
                    'client_name' => $order->client->last_name . ' ' . $order->client->first_name,
                    'client_phone' => '+' . $order->client->phone,
                    //'client_phone' => Prettifier::prettifyPhone($order->client->phone),
                    'client_status' => $order->client->socialStatus ? $order->client->socialStatus->name : null,
                    'client_status_image' => $order->client->mainImage ? env('APP_URL') . '/' . $order->client->mainImage->load('original', $order->client) : null,
                    'client_status_state' => $order->client->status_state,
                ];
            }

            foreach ($order->orderPlaces as $place) {
                $data['orders'][] = [
                        'place_id' => $place->id,
                        'price' => $place->price,
                        'number' => $place->number,
                        'appearance' => $place->appearance,
                    ] + $orderArr;
            }

            if (!isset($stationsFrom[$order->stationFrom->id])) {
                $stationsFrom[$order->stationFrom->id] = $stationFromKey;
                $stationFromKey++;
            }
            $stationArrFromKey = $stationsFrom[$order->stationFrom->id];

            $data['stations_from'][$stationArrFromKey]['station_name'] = $order->stationFrom->name;
            $data['stations_from'][$stationArrFromKey]['station_time'] = $order->station_from_time;
            $data['stations_from'][$stationArrFromKey]['orders'][] = [
                'id' => $order->id,
                'client_name' => $order->client ? $order->client->last_name . ' ' . $order->client->first_name : 'Клиента нет',
                'client_phone' => $order->client ? $order->client->prettyPhone : 'Клиента нет',
                'places' => $order->orderPlaces->implode('number', ', '),
            ];

            if (!isset($stationsTo[$order->stationTo->id])) {
                $stationsTo[$order->stationTo->id] = $stationToKey;
                $stationToKey++;
            }
            $stationArrToKey = $stationsTo[$order->stationTo->id];

            $data['stations_to'][$stationArrToKey]['station_name'] = $order->stationTo->name;
            $data['stations_to'][$stationArrToKey]['station_time'] = $order->station_to_time;
            $data['stations_to'][$stationArrToKey]['orders'][] = [
                'id' => $order->id,
                'client_name' => $order->client ? $order->client->last_name . ' ' . $order->client->first_name : 'Клиента нет',
                'client_phone' => $order->client ? $order->client->prettyPhone : 'Клиента нет',
                'places' => $order->orderPlaces->implode('number', ', '),
            ];
        }
        if (!empty($data['stations_from'])) {
            $data['stations_from'] = collect($data['stations_from'])->sortBy('station_time')->toArray();
            $data['stations_from'] = array_values($data['stations_from']);
        } else $data['stations_from'] = [];

        return $this->responseSuccess(['tours' => $data]);
    }

    public function store()
    {
        $tour = $this->getTourActive(request('id'));
        if (!$tour) return $this->responseError();
        $date = Carbon::createFromFormat('Y-m-dH:i:s', $tour->date_start->format('Y-m-d') . $tour->time_start);
        if ($date > Carbon::now()->addHours(24)) return $this->responseError(['Рейс будет доступен за сутки  до отправления.']);

        $data = [];
        if ($typeDriver = request('type_driver')) {
            $data += ['type_driver' => $typeDriver];
            if ($typeDriver == Tour::TYPE_DRIVER_COMPLETED) $data += ['status' => Tour::STATUS_COMPLETED];
        }

        $error = StoreTourValidator::status($tour, $data);
        if ($error) return $this->responseError(['message' => $error]);
        $tour->update($data);

        return $this->responseSuccess();
    }

    public function stations()
    {
        $tour = Tour::find(request('tour_id'));

        $stations = [];

        foreach ($tour->route->stations->load('city') as $station) {
            $stations[] = ['id' => $station->id, 'name' => $station->city->name . ' ' . $station->name];
        }

        return $this->responseSuccess(['stations' => $stations]);
    }

    protected function getTourActive($id)
    {
        return Tour::whereId($id)
            /*->filter([
                'driver_id' => auth()->user()->id,
            ])*/
//			->future()
            ->first();
    }
}