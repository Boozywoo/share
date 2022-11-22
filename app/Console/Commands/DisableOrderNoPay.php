<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Setting;
use App\Models\Tour;
use App\Models\OrderHistory;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DisableOrderNoPay extends Command
{
    protected $signature = 'disable:order-no-pay';
    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $setting = Setting::first();
        if ($setting->is_pay_on && $setting->time_limit_pay > 0) {
            $orders = Order::whereHas('tour', function ($q) {
                    $q->where('status', Tour::STATUS_ACTIVE);
                    $q->where('date_start', '>=', Carbon::now()->startOfDay());
                })
                ->where('status', Order::STATUS_ACTIVE)
                ->where('type', Order::TYPE_WAITING)
                ->where('type_pay', Order::TYPE_PAY_WAIT)
                ->where('source', Order::SOURCE_SITE)
                ->with('tour.route')
                ->get();

            if ($orders->count()) {
                foreach ($orders as $order) {
                    if(env('TYPE_PAY') != 'SBERBANK' || !$this->getStatusSberbank($order)) {
                        if ($order->updated_at->lessThan(Carbon::now()->subMinutes($setting->time_limit_pay))) {
                            $order->update(['status' => Order::STATUS_DISABLE]);
                            \Log::info('Планировщик. Бронь #' . $order->id . ' отменена из-за неоплаты');
                            echo 'Планировщик. Бронь #' . $order->id . ' отменена из-за неоплаты'.PHP_EOL;
                            $orderHistory = new OrderHistory();
                            $orderHistory->order_id = $order->id;
                            $orderHistory->action = OrderHistory::ACTIVE_CANCEL;
                            $orderHistory->source = "cron_no_pay";
                            $orderHistory->save();
                        }
                    }
                }
            }
        }
    }

    public function getStatusSberbank(Order $order)
    {
        $data = array(
            'userName' => env('PAY_LOGIN'),
            'password' => env('PAY_PASSWORD'),
            'orderNumber' => $order->slug
        );
        
        $response = $this->gateway('https://securepayments.sberbank.ru/payment/rest/','getOrderStatusExtended.do', $data);
        if($response['orderStatus'] == 2) {
            $slug = $response['orderNumber'];
            $order = Order::where('slug', $slug)->first();
            $order->type_pay = Order::TYPE_PAY_SUCCESS;
            $order->is_pay = 1;
            $order->save();

            if ($order->returnOrder)    {
                $order->returnOrder->update(['type_pay' => Order::TYPE_PAY_SUCCESS, 'is_pay' => 1]);
            } 

            return true;
        } else {
            $slug = $response['orderNumber'];
            $order = Order::where('slug', $slug)->first();
            $order->type_pay = Order::TYPE_PAY_CANCEL;
            $order->is_pay = 0;
            $order->save();

            if ($order->returnOrder)    {
                $order->returnOrder->update([ 'type_pay' => Order::TYPE_PAY_CANCEL, 'is_pay' => 0]);
            }

            return false;
        }
    }

    function gateway($url, $method, $data) {
        $curl = curl_init(); // Инициализируем запрос
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url.$method, // Полный адрес метода
            CURLOPT_RETURNTRANSFER => true, // Возвращать ответ
            CURLOPT_POST => true, // Метод POST
            CURLOPT_POSTFIELDS => http_build_query($data) // Данные в запросе
        ));
        $response = curl_exec($curl); // Выполняем запрос
         
        $response = json_decode($response, true); // Декодируем из JSON в массив
        curl_close($curl); // Закрываем соединение
        return $response; // Возвращаем ответ
    }
}
