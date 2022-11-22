<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Driver\AddOrderDriverRequest;
use App\Http\Requests\Api\OrderAddRequest;
use App\Models\Order;
use App\Models\OrderPlace;
use App\Models\Tour;
use App\Services\Log\TelegramLog;
use App\Services\Order\StoreOrderService;
use App\Services\Support\HandlerError;

class OrderController extends ApiController
{
    public function show($id)
    {
        $order = $this->getOrderActive($id);

        if (!$order) return $this->responseError();
        $order->comment .= $order->client->card ? ' [№ карты: ' . $order->client->card . ']' : '';
        $data = [
            'client_id' => $order->client->id,
            'client_name' => $order->client->last_name . ' ' . $order->client->first_name,
            'client_phone' => '+' . $order->client->phone,
            //'client_phone' => $order->client->prettyPhone,
            'client_status' => $order->client->socialStatus ? $order->client->socialStatus->name : null,
            'client_status_image' => $order->client->mainImage ? env('APP_URL') . '/' . $order->client->mainImage->load('original', $order->client) : null,
            'client_status_state' => $order->client->status_state,
            'price' => $order->totalPrice,
            'comment' => $order->comment,
        ];

        return $this->responseSuccess(['order' => $data]);

    }

    public function store()
    {
        $order = $this->getOrderActive(request('id'));

        if (!$order) return $this->responseError();

        $data = [];
        if (request('comment') != null) $data['comment'] = request('comment');

        $order->update($data);

        return $this->responseSuccess();
    }

    public function appearance()
    {
        try {
            $placeIds = is_array(request('place_ids')) ? request('place_ids') : [request('place_ids')];
            foreach ($placeIds as $key => $placeId) {
                if ($orderPlace = OrderPlace::find($placeId)) {
                    $orderPlace->appearance = request('appearance');
                    $orderPlace->save();
                    /*if ($orderPlace->order->client && $orderPlace->order->client->status_state == Client::STATUS_STATE_NEW)
                        return $this->responseError(['message' => 'Клиент имеет неподтвержденный статус']);*/
                } else {
                    unset($placeIds[$key]);
                }
            }
            OrderPlace::whereIn('id', $placeIds)->update(['appearance' => request('appearance')]);
            return $this->responseSuccess();
        } catch (\Exception $e) {
            \Log::error(print_r($placeIds, 1));
            HandlerError::index($e);
            return $this->responseError();
        }
    }

    protected function getOrderActive($id)
    {
        return Order::whereId($id)
            ->has('client')
            /*->filter([
                'driver_id' => auth()->user()->id,
            ])*/
            ->active()
            ->first();
    }

    public function add(AddOrderDriverRequest $request)
    {
        $tour = Tour::find(request('tour_id'));
        header('Access-Control-Allow-Origin: *');  

        $clientId = StoreClientService::index(['client_id' => '', 'first_name' => request('first_name'), 'phone' => request('phone')]);

        $data = [
            'tour_id' => request('tour_id'),
            'station_from_id' => request('station_from_id'),
            'station_to_id' => request('station_to_id'),
            'source' => Order::SOURCE_DRIVER,
            'places_with_number' => 0,
            'places' => request('places', []),
            'status' => Order::STATUS_ACTIVE,
            'confirm' => 1,
            'type' => Order::TYPE_WAITING,
            'client_id' => '',
            'first_name' => request('first_name'),
            'phone' => request('phone'),
        ];

        list ($order, $error) = StoreOrderService::index($data, $tour);
        if ($error) return $this->responseError(['message' => $error]);
        return $this->responseSuccess();
    }

    public function add2(AddOrderDriverRequest $request)
    {
        return $this->responseSuccess();
    }
}