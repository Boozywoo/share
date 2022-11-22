<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 004 04.06.19
 * Time: 7:53
 */

namespace App\Services\Tariff;


use App\Models\Rent;
use App\Models\Tariff;

class TariffRentPriceService
{
    public static function index($tour, $data)
    {
        if ($tour->rent->tariff && $tour->rent->tariff->type == Tariff::TYPE_DURATION) {
            $value = round($tour->rent->duration / 60, 2) ;
        } elseif ($tour->rent->tariff && $tour->rent->tariff->type == Tariff::TYPE_DISTANCE) {
            $value = $tour->rent->distance;
        }

        if (!empty($data['tariff_id']) && isset($value)) {
            $rate = \DB::table('tariff_rates')->where('tariff_id', $data['tariff_id'])
                ->where('min', '<', $value)
                ->where('max', '>=', $value)
                ->first();
            if ($rate) return $rate->cost*$value;
        }
        return 0;
    }
}