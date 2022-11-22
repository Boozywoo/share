<?php

namespace App\Observers;

use App\Models\Client;
use App\Models\Order;
use App\Models\Driver;
use App\Models\DriverAppSetting;
use App\Models\OrderHistory;
use App\Models\Setting;
use App\Channels\SmsChannel;
use Vinkla\Pusher\Facades\Pusher;
use App\Notifications\Order\DisableOrderNotification;


class OrderObserver
{
    public $was_pushed = 0;

    public function created(Order $order)
    {
        $d_a_setting = DriverAppSetting::first();

        if($d_a_setting->notification == 'push'){
            if ($order->source != "driver") {
                if ($this->was_pushed == 1) return;

                if ($order->tour->driver_id != null && $order->type == Order::TYPE_WAITING) {
                    Pusher::trigger('driver-channel' . $order->tour->driver_id . $order->tour_id, 'my-event', [
                        'app_url' => env('APP_URL'),
                        'message' => "Новая бронь!",
                        'places' => $order,
                    ]);

                    $this->was_pushed = 1;
                }
            }
        } elseif($d_a_setting->notification == 'sms') {
            if ($order->source != "driver") {
                if ($this->was_pushed == 1) return;

                if ($order->tour->driver_id != null) {
                    $driver = Driver::find($order->tour->driver_id);
                    $sms = new SmsChannel();
                    $message = "Новая бронь!";
                    $sms->send(null, null, $message, $driver->phone);
                    $this->was_pushed = 1;
                }
            }
        }
    }


    public function updated(Order $order)
    {
        $changed = $order->getDirty();
        $changedStatus = array_get($changed, 'status');
        if (array_get($changed, 'status')) {
            \Log::info('Статус заказа #'.$order->id.' ('.$order->slug.') изменен на '.$changedStatus);
        }
        if (array_get($changed, 'type') == Order::TYPE_WAITING && $order->type_pay == Order::TYPE_PAY_CASH_PAYMENT) {
            Pusher::trigger('driver-channel' . $order->tour->driver_id . $order->tour_id, 'my-event', [
                'app_url' => env('APP_URL'),
                'message' => "Новая бронь!",
            ]);
        }
        if ($newPayStatus = array_get($changed, 'type_pay')) {
             OrderHistory::create(['order_id' => $order->id,
                'action' => OrderHistory::ACTIVE_UPDATE,
                'source' => $order->source,
                'comment' => 'Статус оплаты изменен на: '. trans('admin.orders.pay_types.'.$newPayStatus),
                'client_id' => $order->client_id ?? 0,
                'operator_id' => $order->source === Order::SOURCE_OPERATOR ? \Auth::id() ?? 0 : 0,
                ]);

            if ($newPayStatus == Order::TYPE_PAY_SUCCESS && $order->client_id) {
                Pusher::trigger('driver-channel' . $order->tour->driver_id . $order->tour_id, 'my-event', [
                    'app_url' => env('APP_URL'),
                    'message' => "Новая бронь!",
                ]);
                if (!empty($order->client->email))  {
                    try{
                        \Mail::send('admin.mail.order', ['order' => $order], function ($message) use ($order) {
                            $message->to($order->client->email)
                                ->subject('Вы купили билет');
                        });
                    }
                    catch(\Exception $e){
                        \Log::info('Ошибка при отправке почты: ');
                        \Log::error($e->getMessage());
                    }
                }
                 
             }
//            \Log::info('Пользователь: '.(null !== \Auth::user() ? \Auth::user()->name.' #'.\Auth::id() : 'Guest').' - cтатус оплаты заказа #'.$order->id.' ('.$order->slug.') изменен на '.$newPayStatus);
        }

        if ($order->source != "driver") {
            if ($this->was_pushed == 1) return;

            if ($order->tour->driver && $order->tour->driver->id != null) {
                if ($changedStatus) {
                    if ($changedStatus == Order::STATUS_DISABLE) {
                        Pusher::trigger('driver-channel' . $order->tour->driver_id . $order->tour_id, 'my-event', [
                            'app_url' => env('APP_URL'),
                            'message' => 'Бронь отменена!',
                        ]);
                        if(Setting::all()->first()->is_send_to_email && $order->confirm == 1 && $order->type != 'no_completed') {
                            try{
                                \Mail::send('admin.mail.order', ['order' => $order], function ($message) use ($order) {
                                    $message->to(Setting::all()->first()->main_email)
                                        ->subject('Бронь отменена!');
                                });
                            }
                            catch(\Exception $e){
                                \Log::info('Ошибка при отправке почты: ');
                                \Log::error($e->getMessage());
                            }
                        }
                    }
                }
                $this->was_pushed = 1;
            }
        }
    }

    public function deleting(Order $order)
    {
        $d_a_setting = DriverAppSetting::first();

        if($d_a_setting->notification == 'push'){
            if ($order->source != "driver") {
                if ($this->was_pushed == 1) return;

                if ($order->tour->driver_id != null) {
                    Pusher::trigger('driver-channel' . $order->tour->driver_id . $order->tour_id, 'my-event', [
                        'app_url' => env('APP_URL'),
                        'message' => 'Бронь отменена!',
                    ]);

                    $this->was_pushed = 1;
                }
            }
        } elseif($d_a_setting->notification == 'sms') {
            if ($order->source != "driver") {
                if ($this->was_pushed == 1) return;

                if ($order->tour->driver_id != null) {
                    $driver = Driver::find($order->tour->driver_id);
                    $sms = new SmsChannel();
                    $message = "Бронь отменена!";
                    $sms->send(null, null, $message, $driver->phone);
                    $this->was_pushed = 1;
                }
            }
        }
    }

    public function saved(Order $order)
    {
        $changed = $order->getDirty();
        $changedStatus = array_get($changed, 'status');

        // $changedType = array_get($changed, 'type');


        if ($changedStatus) {
            if ($changedStatus == Order::STATUS_DISABLE && $order->client_id && $order->confirm) {
                $client = Client::where('id', $order->client_id)->first();
                $client->notify(new DisableOrderNotification($order));
            }
        }
        /*if ($changedType && env('MINSK_TRANS')) {
            if ($changedType == Order::TYPE_WAITING && $order->client_id) {
                $client = new AvtovokzalRuService();
                $race = $client->get_race_summary($order->tour->integration_uid);
                $integrationOrder = $client->book_order($race, $order->client);
                $integrationOrder = $client->confirm_order($integrationOrder);
                $order->integration_data = serialize($integrationOrder);
            }
        }*/
    }
}
