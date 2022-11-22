<?php

namespace App\Services\Pdf;

use App\Http\Controllers\Index\PayController;
use App\Models\Company;
use App\Models\Order;
use App\Models\OrderPlace;
use App\Models\Setting;
use App\Services\Pays\ServicePayService;
use Carbon\Carbon;

class ServicePdf
{
    public static function generatePdf(Order $order)
    {
        $payService = new ServicePayService();
        $settings = Setting::first();
        $transaction = [];
        $order = Order::with(['client', 'stationFrom', 'stationTo', 'tour.route', 'orderPlaces'])
            ->whereId($order->id)
            ->first();

        if($order->custom_address_from){
            $order_from = $order->custom_address_from;
        }else{
           $order_from =
               $order->address_from_street.' '.
               $order->address_from_house.' '.
               $order->address_from_building.' '.
               $order->address_from_apart;
        }
        if($order->custom_address_to){
            $order_to = $order->custom_address_to;
        }else{
            $order_to =
                $order->address_to_street.' '.
                $order->address_to_house.' '.
                $order->address_to_building.' '.
                $order->address_to_apart;
        }

//        if($order->pay_id){
//            $pay = $payService->statusTransactionWebpay($order->pay_id);
//            $transaction['date'] = Carbon::createFromTimestamp($pay['date'])->toDateTimeString();
//        }

        $lang = $settings->ticket_language ?? 'ua';
        $filename = 'Order ' . $order->slug . '.pdf';

        $dispatcher = Company::where('dispatcher', '1')->first();
        $data = [
            'dispatcher' => $dispatcher,
            'settings' => $settings,
            'order' => $order,
            'tour' => $order->tour,
            'bus' => $order->tour->bus,
            'lang' => $lang,
            'date_issue' => Carbon::createFromTimestamp(strtotime($order->updated_at))->toDateTimeString(),
            'title' => $filename,
            'currency' => $order->tour->route->currency ? $order->tour->route->currency->alfa : 'BYN',
            'old_price' => array_sum($order->orderPlaces->pluck('price')->toArray()),
            'string_price' => self::number2string(floor($order->price)),
            'places' => $order->orderPlaces->count(),
            //'transaction' => $transaction,
            'address_from' => $order_from,
            'address_to' => $order_to,

            'day' => $order->from_date_time->format('d '),
            'month' => trans('dates.month.long.' . $order->from_date_time->format('n')),
            'year' => $order->from_date_time->format(' Yг.'),

            'from_date' => $order->from_date_time ? $order->from_date_time->format('d.m.Y') :  $order->tour->prettyTimeStart,
            'to_date' => $order->to_date_time ? $order->to_date_time->format('d.m.Y') :  $order->tour->prettyTimeFinish,
            'from_time' => $order->station_from_time ? date('H:i', strtotime($order->station_from_time)) : $order->tour->prettyDateStart,
            'to_time' => $order->station_to_time ? date('H:i', strtotime($order->station_to_time)) : $order->tour->prettyDateFinish,

            'doc_type' => $order->client->doc_type ? trans('admin_labels.doc_types')[$order->client->doc_type] : 'Паспорт',
            'doc_num' => $order->client->doc_number ?? $order->client->passport,

            'station_from' => empty($order->addressFrom) ? $order->stationFrom->name : $order->addressFrom,
            'station_to' => empty($order->addressTo) ? $order->stationTo->name : $order->addressTo,
            'price' => floor($order->price) . "р. (" . self::number2string(floor($order->price)) . " " . explode('.', $order->price)[1] ." копеек) в т.ч. НДС " . round($order->price /6, 2) . " рублей",
            'operator' => $order->operator ? 'Оператор, '.$order->operator->name : 'Онлайн-бронирование',
        ];

        switch ($settings->ticket_type) {
            case "2":
            {
                $template = 'index.print.order_inf';
                break;
            }
            case "3":
            {
                $template = 'index.print.order_ijtransfer';
                break;
            }
            case "4":
            {
                $template = 'index.print.order_norilsk.order_norilsk';
                break;
            }
            case "5":
            {
                $template = 'index.print.taxisharing';
                break;
            }
            case "6":
            {
                $template = 'index.print.order_inf_fly';
                break;
            }
            case "7":
            {
                $template = 'index.print.teshka';
                break;
            }
            case "8":
            {
                $template = 'index.print.flugbus';
                break;
            }
            default:
            {
                $template = 'index.print.order';
                break;
            }
        }

//          return view($template, $data);
        if($settings->ticket_type == '4' || $settings->ticket_type == '5') {
            $pdf = \PDF::loadView($template, $data)->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        } else {
            $pdf = \PDF::loadView($template, $data);
        }

        return $pdf->stream($filename);
    }

    public static function generatePdfOP(OrderPlace $op)
    {
        $settings = Setting::first();
        $op = OrderPlace::whereId($op->id)->first();

        $lang = $settings->ticket_language ?? 'ua';
        $filename = 'Order ' . $op->order->slug . '.pdf';

        if($op->id == $op->order->orderPlaces->first()->id) {
            $data = [
                'settings' => $settings,
                'order' => $op->order,
                'tour' => $op->order->tour,
                'lang' => $lang,
                'date_issue' => Carbon::createFromTimestamp(strtotime($op->order->updated_at))->format('H:i M d, Y'),
                'title' => $filename,
                'currency' => $op->order->tour->route->currency ? $op->order->tour->route->currency->alfa : 'BYN',
                'old_price' => array_sum($op->order->orderPlaces->pluck('price')->toArray()),
                'string_price' => self::number2string(floor($op->order->price)) ?? '',
                'places' => 1,
    
                'day' => $op->order->from_date_time->format('d '),
                'month' => trans('dates.month.long.' . $op->order->from_date_time->format('n')),
                'year' => $op->order->from_date_time->format(' Yг.'),
    
                'from_date' => $op->order->from_date_time ? $op->order->from_date_time->format('d.m.Y') :  $op->order->tour->prettyTimeStart,
                'to_date' => $op->order->to_date_time ? $op->order->to_date_time->format('d.m.Y') :  $op->order->tour->prettyTimeFinish,
                'from_time' => $op->order->station_from_time ? date('H:i', strtotime($op->order->station_from_time)) : $op->order->tour->prettyDateStart,
                'to_time' => $op->order->station_to_time ? date('H:i', strtotime($op->order->station_to_time)) : $op->order->tour->prettyDateFinish,
    
                'doc_type' => $op->order->client->doc_type ? trans('admin_labels.doc_types')[$op->order->client->doc_type] : 'Паспорт',
                'doc_num' => $op->order->client->doc_number ?? $op->order->client->passport,
    
                'station_from' => $op->order->addressFrom ?? $op->order->stationFrom->name,
                'price' => floor($op->price) . "р. (" . self::number2string(floor($op->price))  . " " . (explode('.', $op->price)[1] ?? 0) ." копеек) в т.ч. НДС " . round($op->price /6, 2) . " рублей",
                'operator' => $op->order->operator ? 'Оператор, '.$op->order->operator->name : 'Онлайн-бронирование',
            ];
        } else {
            $data = [
                'settings' => $settings,
                'order' => $op,
                'tour' => $op->order->tour,
                'lang' => $lang,
                'date_issue' => Carbon::createFromTimestamp(strtotime($op->updated_at))->format('H:i M d, Y'),
                'title' => $filename,
                'currency' => $op->order->tour->route->currency ? $op->order->tour->route->currency->alfa : 'BYN',
                'old_price' => array_sum($op->order->orderPlaces->pluck('price')->toArray()),
                'places' => 1,

                'day' => $op->order->from_date_time->format('d '),
                'month' => trans('dates.month.long.' . $op->order->from_date_time->format('n')),
                'year' => $op->order->from_date_time->format(' Yг.'),
    
                'from_date' => $op->order->from_date_time ? $op->order->from_date_time->format('d.m.Y') :  $op->order->tour->prettyTimeStart,
                'to_date' => $op->order->to_date_time ? $op->order->to_date_time->format('d.m.Y') :  $op->order->tour->prettyTimeFinish,
                'from_time' => $op->order->station_from_time ? date('H:i', strtotime($op->order->station_from_time)) : $op->order->tour->prettyDateStart,
                'to_time' => $op->order->station_to_time ? date('H:i', strtotime($op->order->station_to_time)) : $op->order->tour->prettyDateFinish,
    
                'doc_type' => $op->doc_type ? trans('admin_labels.doc_types')[$op->doc_type] : 'Паспорт',
                'doc_num' => $op->doc_number ?? $op->passport,
    
                'station_from' => $op->order->addressFrom ?? $op->order->stationFrom->name,
                'price' => floor($op->price) . "р. (" . self::number2string(floor($op->price))  . " " . (explode('.', $op->price)[1] ?? 0) ." копеек) в т.ч. НДС " . round($op->price /6, 2) . " рублей",
                'operator' => $op->order->operator ? 'Оператор, ' . $op->order->operator->name : 'Онлайн-бронирование',
            ];
        }

        switch ($settings->ticket_type) {
            case "2":
            {
                $template = 'index.print.order_inf';
                break;
            }
            case "3":
            {
                $template = 'index.print.order_ijtransfer';
                break;
            }
            case "4":
            {
                $template = 'index.print.order_norilsk.order_norilsk';
                break;
            }
            case "5":
            {
                $template = 'index.print.taxisharing';
                break;
            }
            default:
            {
                $template = 'index.print.order';
                break;
            }
        }
        //    return view($template, $data);
        if($settings->ticket_type == '4' || $settings->ticket_type == '5') {
            $pdf = \PDF::loadView($template, $data)->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        } else {
            $pdf = \PDF::loadView($template, $data);
        }

        return $pdf->download($filename);
    }

    public static function number2string($number) {
        try{
            // обозначаем словарь в виде статической переменной функции, чтобы
            // при повторном использовании функции его не определять заново
            static $dic = array(

                // словарь необходимых чисел
                array(
                    -2	=> 'две',
                    -1	=> 'одна',
                    1	=> 'один',
                    2	=> 'два',
                    3	=> 'три',
                    4	=> 'четыре',
                    5	=> 'пять',
                    6	=> 'шесть',
                    7	=> 'семь',
                    8	=> 'восемь',
                    9	=> 'девять',
                    10	=> 'десять',
                    11	=> 'одиннадцать',
                    12	=> 'двенадцать',
                    13	=> 'тринадцать',
                    14	=> 'четырнадцать' ,
                    15	=> 'пятнадцать',
                    16	=> 'шестнадцать',
                    17	=> 'семнадцать',
                    18	=> 'восемнадцать',
                    19	=> 'девятнадцать',
                    20	=> 'двадцать',
                    30	=> 'тридцать',
                    40	=> 'сорок',
                    50	=> 'пятьдесят',
                    60	=> 'шестьдесят',
                    70	=> 'семьдесят',
                    80	=> 'восемьдесят',
                    90	=> 'девяносто',
                    100	=> 'сто',
                    200	=> 'двести',
                    300	=> 'триста',
                    400	=> 'четыреста',
                    500	=> 'пятьсот',
                    600	=> 'шестьсот',
                    700	=> 'семьсот',
                    800	=> 'восемьсот',
                    900	=> 'девятьсот'
                ),

                // словарь порядков со склонениями для плюрализации
                array(
                    array('рубль', 'рубля', 'рублей'),
                    array('тысяча рублей', 'тысячи рублей', 'тысяч рублей'),
                    array('миллион рублей', 'миллиона рублей', 'миллионов рублей'),
                    array('миллиард рублей', 'миллиарда рублей', 'миллиардов рублей'),
                    array('триллион рублей', 'триллиона рублей', 'триллионов рублей'),
                    array('квадриллион рублей', 'квадриллиона рублей', 'квадриллионов рублей'),
                    // квинтиллион, секстиллион и т.д.
                ),

                // карта плюрализации
                array(
                    2, 0, 1, 1, 1, 2
                )
            );

            // обозначаем переменную в которую будем писать сгенерированный текст
            $string = array();

            // дополняем число нулями слева до количества цифр кратного трем,
            // например 1234, преобразуется в 001234
            $number = str_pad($number, ceil(strlen($number)/3)*3, 0, STR_PAD_LEFT);

            // разбиваем число на части из 3 цифр (порядки) и инвертируем порядок частей,
            // т.к. мы не знаем максимальный порядок числа и будем бежать снизу
            // единицы, тысячи, миллионы и т.д.
            $parts = array_reverse(str_split($number,3));

            // бежим по каждой части
            foreach($parts as $i=>$part) {

                // если часть не равна нулю, нам надо преобразовать ее в текст
                if($part>0) {

                    // обозначаем переменную в которую будем писать составные числа для текущей части
                    $digits = array();

                    // если число треххзначное, запоминаем количество сотен
                    if($part>99) {
                        $digits[] = floor($part/100)*100;
                    }

                    // если последние 2 цифры не равны нулю, продолжаем искать составные числа
                    // (данный блок прокомментирую при необходимости)
                    if($mod1=$part%100) {
                        $mod2 = $part%10;
                        $flag = $i==1 && $mod1!=11 && $mod1!=12 && $mod2<3 ? -1 : 1;
                        if($mod1<20 || !$mod2) {
                            $digits[] = $flag*$mod1;
                        } else {
                            $digits[] = floor($mod1/10)*10;
                            $digits[] = $flag*$mod2;
                        }
                    }

                    // берем последнее составное число, для плюрализации
                    $last = abs(end($digits));

                    // преобразуем все составные числа в слова
                    foreach($digits as $j=>$digit) {
                        $digits[$j] = $dic[0][$digit];
                    }

                    // добавляем обозначение порядка или валюту
                    $digits[] = $dic[1][$i][(($last%=100)>4 && $last<20) ? 2 : $dic[2][min($last%10,5)]];

                    // объединяем составные числа в единый текст и добавляем в переменную, которую вернет функция
                    array_unshift($string, join(' ', $digits));
                }
            }

            // преобразуем переменную в текст и возвращаем из функции, ура!
            return join(' ', $string);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return;
        }
        
    }
}
