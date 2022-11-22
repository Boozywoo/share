<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 031 31.05.19
 * Time: 13:37
 */

namespace App\Services\Order;


use App\Models\Order;
use App\Models\OrderPlace;
use App\Services\Client\StoreClientService;
use App\Services\Tariff\TariffRentPriceService;

class StoreRentOrderService
{
    public static function index($tour, $data)
    {
        $id = array_get($data, 'id');
        $user = \Auth::user();

        if (isset(auth()->user()->client_id))
            $data['client_id'] = auth()->user()->client_id;
        else
            $data['client_id'] = StoreClientService::index($data);

        $data['station_from_time'] = $tour->time_start;
        $data['station_to_time'] = $tour->date_finish;
        $data['price'] = TariffRentPriceService::index($tour, $data);

        $data['count_places'] = count($data['places']);
        if ($id) {
            if ($order = Order::find($id)) {
                $data['modified_user_id'] = \Auth::id();
                $order->update($data);
            }
        } else {
            if ($user && empty($user->client)) {
                $data['created_user_id'] = $user->id;
                $data['operator_id'] = $user->id;
                $data['source'] = Order::SOURCE_OPERATOR;
            }
            $order = Order::create($data);
        }
        $lastPlaces = $order->OrderPlaces;

        $order->orderPlaces()->delete();

        foreach ($data['places'] as $key => $place) {
            $dataOrder = [
                'order_id' => $order->id,
                'number' => $place,
                'name' => isset($lastPlaces[$key]) ? $lastPlaces[$key]['name'] : null,
                'surname' => isset($lastPlaces[$key]) ? $lastPlaces[$key]['surname'] : null,
                'patronymic' => isset($lastPlaces[$key]) ? $lastPlaces[$key]['patronymic'] : null,
                'card' => isset($lastPlaces[$key]) ? $lastPlaces[$key]['card'] : null,
                'passport' => isset($lastPlaces[$key]) ? $lastPlaces[$key]['passport'] : null,
                'birth_day' => isset($lastPlaces[$key]) ? $lastPlaces[$key]['birth_day'] : null,
            ];
            OrderPlace::create($dataOrder);
        }
        return $order;
    }
}