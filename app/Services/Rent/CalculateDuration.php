<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 009 09.07.19
 * Time: 15:41
 */

namespace App\Services\Rent;


use Carbon\Carbon;

class CalculateDuration
{
    public static function index($timeStart, $timeFinish)
    {
        return Carbon::createFromTimeString($timeStart)->diffInMinutes(Carbon::createFromTimeString($timeFinish));
    }
}