<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 025 25.08.18
 * Time: 11:19
 */

namespace App\Services\Rent;

use App\Models\Tariff;
use App\Models\Tour;
use App\Services\Tariff\TariffRentPriceService;

class RentService
{
    public static function index(Tour $tour, $data)
    {
        $tariff = isset($data['tariff_id']) && !empty($data['tariff_id']) ? Tariff::find($data['tariff_id']) : null;
        if ($tariff && $tariff->type == Tariff::TYPE_DISTANCE) {
            if (isset($data['address']) && isset($data['address_to'])) {
                $changeAddress = false;

                if ($tour->rent->tariff_id != $data['tariff_id']) {
                    $changeAddress = true;
                }
                if ($tour->rent->address != $data['address']) {
                    $changeAddress = true;
                }
                if ($tour->rent->address_to != $data['address_to']) {
                    $changeAddress = true;
                }
                if ($changeAddress) {
                    $tour->rent->distance = CalculateDistance::index($data['address'], $data['address_to']);
                }
            }
        } elseif ($tariff && $tariff->type == Tariff::TYPE_DURATION) {
            $timeStart = $data['date_start'] . ' ' . $data['time_start'];
            $timeFinish = $data['date_finish'] . ' ' . $data['time_finish'];
            $tour->rent->duration = CalculateDuration::index($timeStart, $timeFinish);
        }

        $fields = $tour->rent->getFillable();
        foreach ($fields as $field) {
            if (isset($data[$field])) $tour->rent->$field = empty($data[$field]) ? null : $data[$field];
        }

        $tour->rent->save();
        if ($tour->rent->tariff_id) {
            $price = TariffRentPriceService::index($tour, $data);
        } else {
            $price = $data['price'] ?? $tour->price;
        }
        
        $tour->update(['price' => $price]);
    }
}