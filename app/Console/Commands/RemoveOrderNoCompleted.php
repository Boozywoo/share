<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\Client;
use App\Channels\SmsChannel;
use App\Models\OrderHistory;

class RemoveOrderNoCompleted extends Command
{
    protected $signature = 'remove:order-no-completed';

    public function handle()
    {

        $settings = Setting::first();

            $orders = Order::query()
                ->where('type', Order::TYPE_NO_COMPLETED)
                ->where('status', 'active')
                ->where('operator_id','=',null)
                ->where('updated_at', '<', Carbon::now()->subMinutes(15))
                ->get();

            if (!empty($orders)) {
                foreach ($orders as $order) {
                    if ($settings->send_sms_of_remove_order) {
                        $client = Client::query()->where('id', $order->client_id)->first();
                        if (!empty($client)) {
                            $message = "Ваша бронь " . $order->slug . " отменена, потому что вы не подтвердили номер телефона";
                            $sms = new SmsChannel();
                            $sms->send(null, null, $message, $client->phone);

                        }
                    }
                    $orderHistory = new OrderHistory();
                    $orderHistory->order_id = $order->id;
                    $orderHistory->action = OrderHistory::ACTIVE_CANCEL;
                    $orderHistory->source = "cron";
                    $orderHistory->save();
                    $order->status = Order::STATUS_DISABLE;
                    $order->save();
                }

            }



        /*Order::query()
              ->where('type', Order::TYPE_NO_COMPLETED)
              ->where('updated_at', '<', Carbon::now()->subMinutes(10))
              ->update(['status' => Order::STATUS_DISABLE]);*/
        /*if (env('PAY_TOKEN')) {
            $orders = Order::whereNotIn('type_pay', [Order::TYPE_PAY_SUCCESS, Order::TYPE_PAY_CASH_PAYMENT])
                ->where('created_at', '>' , Carbon::now()->subDays(2))
                ->get();

            foreach ($orders as $order) {
                if ($order->tour->route->time_limit_pay && (Carbon::now()->subMinutes($order->tour->route->time_limit_pay)->timestamp > $order->created_at->timestamp)) {
                    dd($order);
                }
            }
        }*/

    }
}
