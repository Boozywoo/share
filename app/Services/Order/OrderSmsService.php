<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Services\Client\StoreClientService;
use App\Services\Coupon\CouponToOrderService;
use App\Services\Prettifier;
use App\Services\Rent\OrderRentSmsService;
use App\Services\Social\SocialToOrderService;
use App\Validators\Order\StoreOrderValidator;
use App\Services\Pays\ServicePayService;
use \DB;
use App\Models\SmsProvider;
use App\Models\Setting;

use App\Models\SmsConfig;

class OrderSmsService
{
    public static function template($order, $type = null)
    {

        $_smsConfig = SmsConfig::getConfig($order->client->phone);
        if ($order->tour->rent) {
            return OrderRentSmsService::index($order->tour);
        }
        $message = '';

        if ($order->status == Order::STATUS_RESERVE) {
            $message .= "Резервная бронь: \n";
        }

        foreach ($_smsConfig as $_sc) {

            if (env('SMS_SHORT')) {
                // from
                if (($_sc['key'] = 'from') && ($_sc['show']==1)) {
                    $message .= trans('admin.settings.smsconfig.fields.from') . ': ' . $order->stationFrom->name_tr . "\n";
                }

//            $message .= 'От: ' . $order->stationFrom->name_tr . "\n";
            } else {

                // booking
                if (($_sc['key'] == 'booking') && ($_sc['show']==1)) {
//            $message .= "Бронь: $order->id" . "\n";
                    $message .= trans('admin.settings.smsconfig.fields.booking') . ': ' . $order->slug . "\n";
                }

                // from
                if (($_sc['key'] == 'from') && ($_sc['show']==1)) {
//            $message .= 'От: ' . $order->stationFrom->city->name_tr . ' ' . $order->stationFrom->name_tr . "\n";
                    $message .= trans('admin.settings.smsconfig.fields.from') . ': ' . ($order->addressFrom ?? $order->stationFrom->city->name_tr . ' ' . $order->stationFrom->name_tr) . "\n";
                }

                // to
                if (($_sc['key'] == 'to') && ($_sc['show']==1)) {
//            $message .= 'До: ' . $order->stationTo->city->name_tr . ' ' . $order->stationTo->name_tr . "\n";
                    $message .= trans('admin.settings.smsconfig.fields.to') . ': ' . ($order->addressTo ?? $order->stationTo->city->name_tr . ' ' . $order->stationTo->name_tr) . "\n";
                }

            }

            // date
            if (($_sc['key'] == 'date') && ($_sc['show']==1)) {
                $message .= trans('admin.settings.smsconfig.fields.date') . ': ' . Prettifier::prettifyDateTimeFull($order->from_date_time ?? $order->tour->date_start->format('Y-m-d') . ' ' . $order->station_from_time) . "\n";
            }

            // auto
            if (($_sc['key'] == 'auto') && ($_sc['show']==1)) {
//        $message .= 'Авто: ' . $order->tour->bus->name_tr . ' ' . $order->tour->bus->number . "\n";
                $message .= trans('admin.settings.smsconfig.fields.auto') . ': ' . $order->tour->bus->name_tr . ' ' . $order->tour->bus->number . "\n";
            }

            
            // places
            if (($_sc['key'] == 'places') && ($_sc['show']==1)) {
                if ($order->orderPlaces->count() == 1) {
                    $place = trans('admin.settings.smsconfig.fields.place') . ': ';
                } else {
                    $place = trans('admin.settings.smsconfig.fields.places') . ': ';
                }

                $message .= $place . $order->orderPlaces->implode('number', ', ') . "\n";
            }

            // price
            if (($_sc['key'] == 'price') && ($_sc['show']==1)) {
                // $message .= 'Стоимость: ' . $order->price . "\n";
                $message .= trans('admin.settings.smsconfig.fields.price') . ': ' . $order->price . " " . $order->tour->route->currency->alfa . "\n";
            }

            //driver name
            if (($_sc['key'] == 'driver_name') && ($_sc['show']==1)) {
                if ($order->tour->driver->full_name) {
//                $message .= "Водитель: +" . preg_replace('/[^0-9]/', '', $order->tour->driver->work_phone) . "\n";
                    $message .= trans('admin.settings.smsconfig.fields.driver_name') . ": " . $order->tour->driver->full_name . "\n";
                }
            }

            // driver phone
            if (($_sc['key'] == 'driver_phone') && ($_sc['show']==1)) {
                if ($order->tour->driver->work_phone) {
//                $message .= "Водитель: +" . preg_replace('/[^0-9]/', '', $order->tour->driver->work_phone) . "\n";
                    $message .= trans('admin.settings.smsconfig.fields.driver_phone') . ": +" . preg_replace('/[^0-9]/', '', $order->tour->driver->work_phone) . "\n";
                }
            }

            // ticket
            if (($_sc['key'] == 'ticket') && ($_sc['show']==1)) {
                $message .= trans('admin.settings.smsconfig.fields.ticket') . ': ' . route('index.ticket', $order->slug) . "\n";
            }

            // info text
            if (($_sc['key'] == 'info') && ($_sc['show']==1)) {
                $message .=  Setting::first()->sms_info_text  . "\n";
            }

            // pay_link
            if (($_sc['key'] == 'pay_link') && $_sc['show'] == 1 && $order->type_pay != Order::TYPE_PAY_SUCCESS && ($order->client->email == 'QRCODE' || $order->source == Order::SOURCE_OPERATOR)) {   // Для всех кто бронируется по QR-коду клиент не создается 
                $result = ServicePayService::webpayGetPaymentInvoice($order, '3 hours');
                if ($result['status'] == 'ok') {
                    $order->update(['pay_url' => $result['url']]);
                    $payLink = route('index.order-pay', $order->slug);
                } else {
                    $payLink = trans('messages.index.order.error_two');
                }
                $message .=  trans('admin.settings.smsconfig.fields.pay_link') . ': ' . $payLink . "\n";
            }

        }
        $SpCur = SmsProvider::where('default', '=', 1)->first();
        if(isset($SpCur->is_latin) ? $SpCur->is_latin : env('IS_LATIN'))
            $message = Prettifier::Transliterate($message);
        $message = str_replace(
            [
                "g.",
                "ul.",
                "Rossiya, Orenburgskaya oblast, ",
                "Rossiya, Samarskaya oblast, ",
                ", ulitsa",
                "Rossiya, "
            ], "", $message);

        return $message;
    }
}
