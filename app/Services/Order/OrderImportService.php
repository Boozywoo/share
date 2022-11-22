<?php

namespace App\Services\Order;

use App\Models\Client;
use App\Models\Tour;
use App\Models\User;
use App\Models\Driver;
use App\Models\Order;
use App\Traits\ClearPhone;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Order\StoreOrderService;

use Carbon\Carbon;
use Exception;
use InvalidArgumentException;

class OrderImportService
{

    protected $results;
    protected $user;
    public $error;

    public function __construct($results, User $user)
    {
        $this->results = $results;
        $this->user = $user;

    }

    public function import()
    {
        try {
            foreach ($this->results as $result) {
                $driver_name = $result['fio_voditelya'];
                $date_start_str = $result['data_registratsii_na_terminale'];
                $time_str = $result['vremya_registratsii_na_terminale'];
                $price = $result['summa_terminala_rub'];

                if (!isset($driver_name, $date_start_str, $time_str, $price)) {
                    continue;
                }

                $firstName = trans('admin_labels.system');
                $phone = 70000000000;
                $client = Client::wherePhone($phone)->first();
                
                $driver = Driver::where('last_name', (explode(" ", $driver_name))[0])->first();

                $date_start = Carbon::parse($date_start_str)->format('Y-m-d');
                $time = Carbon::parse($time_str)->format('H:i:s');

                if($driver) {
                    $tour = Tour::where('driver_id', $driver->id)
                    ->where('date_start', $date_start)
                    ->where('time_start', '<=', $time)
                    ->where('time_finish', '>=', $time)
                    ->first();
                }

                if($tour) {

                    if($this->isEqualData($tour, $driver, $date_start, $time)) {
                        continue;
                    }
                    
                    $data = [
                        'tour_id' => $tour->id,
                        'station_from_id' => $tour->route->stations->first()->id,
                        'station_to_id' => $tour->route->stations->last()->id,
                        'status' => Order::STATUS_ACTIVE,
                        'source' => 'system',
                        'created_user_id' => \Auth::id(),
                        'operator_id' => \Auth::id(),
                        'confirm' => true,
                        'price' => $price,
                        'type' => Order::TYPE_COMPLETED,
                        'places_with_number' => 0,
                        'places' => array_combine(Array(0), Array('')),
                        'type_pay' => 'cashless-payment',
                    ];
                    
                    if($client) {
                        $data['phone'] = $phone;
                        $data['first_name'] = $firstName;
                        $data['client_id'] = $client->id;
                        
                    } else {
                        $model = Client::create([
                            'phone' => $phone,
                            'first_name' => $firstName,
                            'order_success' => 0,
                            'order_error' => 0,
                            'status' => Client::STATUS_ACTIVE,
                        ]);
                        $data['client_id'] = $model->id;
                        $data['phone'] = $phone;
                        $data['first_name'] = $firstName;
                    }

                    list ($order, $error) = StoreOrderService::index($data, $tour);
    
                    if ($error) {
                        $data['message'] = $error;
                        \Log::error($data);
                        continue;
                    }

                    \Log::info('Successfully imported order ' . $order->slug);
    
                    if ($order) {
                        \Log::info($order->slug);
    
                        $order->appearance = true;
                        $order->is_pay = true;
                        $order->payment_time = $time;
                        $order->save();
    
                        $opPrice = $order->price / $order->orderPlaces->count();
    
                        foreach($order->orderPlaces as $op) {    
                            $op->appearance = 1;
                            $op->price = $opPrice;
                            $op->save();
                        }
                    } 
                }
            }

        } catch (\Exception $e) {
            $this->logError($e);
            return array('action'=>'1','msg'=>$e->getMessage());
        }

        return array('action'=>'2','msg'=> '');
    }

    private function isEqualData($tour, $driver, $date_start, $payment_time) {
        foreach($tour->orders as $order) {
            if(($driver->id == $tour->driver->id) && ($date_start == $tour->date_start)) {
                if($order->payment_time) {
                    return $order->payment_time == $payment_time;
                }
            } else {
                return false;
            }
        }
    }

    private function logError(\Exception $e) {
        $message = $e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile() . ' ' . $e->getTraceAsString();
        \Log::error($message);
    }
}