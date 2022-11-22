<?php

namespace App\Services;


use Carbon\Carbon;

class Prettifier
{
    public static function prettifyPrice($price, $currency = 'BYN')
    {
        return number_format($price, 2, ',', ' ').' '.trans('admin_labels.currencies_short.'.$currency);
    }

    public static function prettifyTextArea($text)
    {
        return str_replace("\n", "<br />", $text);
    }

    public static function prettifyTime($time, $interval = null, $sum = true)
    {
        if (empty($time)) return '';
        if (strlen($time) == 5) $time.=':00';
        $time = Carbon::createFromFormat('H:i:s', $time);
        if ($interval) {
            if ($sum) $time->addMinutes($interval);
            if (!$sum) $time->subMinutes($interval);
        }
        return $time->format('H:i');
    }

    public static function prettifyDateTime($date, $time = '', $interval = null, $sup = false)
    {
        if (strlen($time) == 5) {
            $time.=':00';
        }
        $time = Carbon::createFromFormat('Y-m-d H:i:s', trim($date.' '.$time));
        $time->addMinutes($interval ?? 0);
        if ($sup) {
            return $time->formatLocalized('%R <sup>%e&nbsp;%b</sup>');
        } else {
            return $time->formatLocalized('%R %e&nbsp;%b');
        }
    }

    public static function prettifyDateTimeFull($date, $time = '', $interval = null)
    {
        $dtime = Carbon::createFromFormat('Y-m-d H:i:s', trim($date.' '.$time));
        $dtime->addMinutes($interval ?? 0);
        return  $dtime->format('d ').trans('dates.month.long.' . $dtime->format('n')).$dtime->format(' H:i');
    }


    public static function prettifyPhone($phone)
    {
        try {
            $matches = [];
            preg_match_all('/(\d{3})(\d{2})(\d{3})(\d{2})(\d{2})/', $phone, $matches);
            if(count($matches) && $phone) {
                return "+{$matches[1][0]} ({$matches[2][0]}) {$matches[3][0]}-{$matches[4][0]}-{$matches[5][0]}";
            }
            return '';
        } catch (\Exception $e) {
            return $phone;
        }
    }

    public static function prettifyPhoneClear($phone)
    {
        return str_replace(['+', '-', '_', ' ', '(', ')'], '', trim($phone));
    }

    public static function phoneWithoutCode($phone)
    {
        return substr($phone, -9);
    }

    public static function percent($price, $percent)
    {
        return $price - $price / 100 * $percent;
    }

    public static function Transliterate($string) {
        $roman = array("Sch","sch",'Yo','Zh','Kh','Ts','Ch','Sh','Yu','ya','yo','zh','kh','ts','ch','sh','yu','ya','A','B','V','G','D','E','Z','I','Y','K','L','M','N','O','P','R','S','T','U','F','','Y','','E','a','b','v','g','d','e','z','i','y','k','l','m','n','o','p','r','s','t','u','f',"'",'y',"'",'e');
        $cyrillic = array("Щ","щ",'Ё','Ж','Х','Ц','Ч','Ш','Ю','я','ё','ж','х','ц','ч','ш','ю','я','А','Б','В','Г','Д','Е','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Ь','Ы','Ъ','Э','а','б','в','г','д','е','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','ь','ы','ъ','э');
        return str_replace($cyrillic, $roman, $string);
    }

    public static function dateForOrderPrint($format, $timestamp)
    {
        $locale_time = setlocale (LC_TIME, 'ru_RU.UTF-8', 'Rus');

        $date_str = strftime($format, $timestamp);
        if (strpos($locale_time, '1251') !== false)
        {
            return iconv('cp1251', 'utf-8', $date_str);
        }
        else
        {
            return $date_str;
        }
    }

}