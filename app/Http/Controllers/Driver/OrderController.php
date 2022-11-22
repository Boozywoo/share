<?php

namespace App\Http\Controllers\Driver;

use App\Models\Station;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Driver\AddOrderDriverRequest;
use App\Models\Order;
use Vinkla\Pusher\Facades\Pusher;
use App\Models\Tour;
use App\Models\Token;
use App\Services\Order\StoreOrderService;
use App\Services\Support\HandlerError;

class OrderController extends ApiController 
{
    public function add(AddOrderDriverRequest $request)
    {
        try {
            $tour = Tour::find(request('tour_id'));
            if (!empty($tour)) {
                $setting = \App\Models\DriverAppSetting::first();
                if($setting->is_change_price == 1) {
                    $price = request('price');
                } else {
                    $price = '';
                }
                $client = Client::where('phone', preg_replace("/[^0-9]/", "", request('phone')))->first();

                $data = [
                    'tour_id' => request('tour_id'),
                    'station_from_id' => request('station_from_id'),
                    'station_to_id' => request('station_to_id'),
                    'source' => Order::SOURCE_DRIVER,
                    'places' => request('places', []),
                    'status' => Order::STATUS_ACTIVE,
                    'confirm' => true,
                    'price' => $price,
                    'type' => Order::TYPE_WAITING,
                    'type_pay' => request('type_pay'),
                    'client_id' => $client ? $client->id : '',
                    'first_name' => request('first_name'),
                    'phone' => $client ? $client->phone : preg_replace("/[^0-9]/", "", request('phone')), 
                    'last_name' => request('last_name') ?? '',
                    'middle_name' => request('middle_name') ?? '',
                    'birth_day' => request('birth_day') ?? '',
                    'country_id' => request('country_id') ?? '',
                    'passport' => request('passport') ?? '',
                    'doc_type' => request('doc_type') ?? '',
                    'doc_number' => request('doc_number') ?? '',
                    'card' => request('card') ?? '',
                    'gender' => request('gender') ?? '',
                    'flight_number' => request('flight_number') ?? '',
                ];
                list ($order, $error) = StoreOrderService::index($data, $tour);
                
                if ($error) {
                    $data['message'] = $error;
                    return $this->responseError($data);
                }
                
                if ($order) {
                    $order->appearance = true;
                    $order->is_pay = true;
                    $order->save();
                    
                    $opPrice = $order->price / $order->orderPlaces->count();

                    foreach($order->orderPlaces as $op) {
                        $op->appearance = 1;
                        $op->price = $opPrice;
                        $op->save();
                    }

                    Pusher::trigger('driver-channel' . $order->tour->driver_id . $order->tour_id, 'my-event', [
                        'app_url' => env('APP_URL'),
                        'message' => "Новая бронь!",
                    ]);

                    return $this->responseSuccess();
                }   
            }
        } catch (\Exception $e) { 
            return response($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile(), 500);
        } 
    }

    public function addTaxiOrder(Request $request)
    {
        try {
            $tour = Tour::findOrFail(request('tour_id'));
            if (!empty($tour)) {
                $price = 10;

                $client = Client::findOrFail(request('client_id'));
                $data = [
                    'tour_id' => request('tour_id'),
                    'station_from_id' => request('station_from_id'),
                    'station_to_id' => request('station_to_id'),
                    'source' => Order::SOURCE_DRIVER,
                    'places' => array_fill(0, $request->count_places, null),
                    'status' => Order::STATUS_ACTIVE,
                    'confirm' => true,
                    'price' => $price,
                    'type' => Order::TYPE_WAITING,
                    'type_pay' => 'cash-payment',
                    'client_id' => $client->id,
                    'delay' => request('delay'),
                ];

                list ($order, $error) = StoreOrderService::index($data, $tour);

                if ($error) {
                    $data['message'] = $error;
                    return $this->responseError($data);
                }

                if ($order) {
                    Pusher::trigger('driver-taxi-channel2', 'close-taxi-order', [
                        'client_id' => $client->id,
                        'app_url' => env('APP_URL'),
                    ]);
                    return $this->responseSuccess();
                }
            }
        } catch (\Exception $e) {
            return $this->responseError(['message' => $e->getMessage()]);
        }
    }
}
