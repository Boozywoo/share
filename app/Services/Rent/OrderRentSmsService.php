<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 004 04.06.19
 * Time: 9:41
 */

namespace App\Services\Rent;

use App\Models\SmsConfig;

class OrderRentSmsService
{
    public static function index($tour)
    {
        $_smsConfig = SmsConfig::getConfig();

        $message = '';

        if ($_smsConfig['start']['show']) {
//        $message .= 'Начало: ' . $tour->date_start->format('d.m.Y') . ', ' . substr($tour->time_start, 0, -3) . "\n";
            $message .= trans('admin.settings.smsconfig.fields.start') . ': ' . $tour->date_start->format('d.m.Y') . ', ' . substr($tour->time_start, 0, -3) . "\n";
        }

        if ($tour->bus) {
            if ($_smsConfig['auto']['show']) {
//            $message .= 'Авто: ' . $tour->bus->name_tr . ' ' . $tour->bus->number . "\n";
                $message .= trans('admin.settings.smsconfig.fields.auto') . ': ' . $tour->bus->name_tr . ' ' . $tour->bus->number . "\n";
            }
        }

        if ($tour->rent->address) {
            if ($_smsConfig['from']['show']) {
//                $message .= 'Откуда: ' . $tour->rent->address . "\n";
                $message .= trans('admin.settings.smsconfig.fields.from') . ': ' . $tour->rent->address . "\n";
            }
        }

        if ($tour->rent->address_to) {
            if ($_smsConfig['to']['show']) {
//                $message .= 'Куда: ' . $tour->rent->address_to . "\n";
                $message .= trans('admin.settings.smsconfig.fields.to') . ': ' . $tour->rent->address_to . "\n";
            }
        }

        if ($_smsConfig['end']['show']) {
//            $message .= 'Окончание: ' . $tour->date_finish->format('d.m.Y') . ', ' . substr($tour->time_finish, 0, -3) . "\n";
            $message .= trans('admin.settings.smsconfig.fields.end') . ': ' . $tour->date_finish->format('d.m.Y') . ', ' . substr($tour->time_finish, 0, -3) . "\n";
        }

        if ($tour->price) {
            if ($_smsConfig['price']['show']) {
//                $message .= 'Стоимость: ' . $tour->price . "\n";
                $message .= trans('admin.settings.smsconfig.fields.price') . ': ' . $tour->price . "\n";
            }
        }

        return $message;
    }
}