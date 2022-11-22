<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 009 09.07.19
 * Time: 14:49
 */

namespace App\Services\Rent;


class CalculateDistance
{
    public static function index($point1, $point2)
    {
        if ($point1 && $point2) {
            $dataRequest = [
                'origins' => str_replace(' ', '+', $point1),
                'destinations' => str_replace(' ', '+', $point2),
                'language' => 'ru-RU',
                'key' => 'AIzaSyCviKcNX7gYtsUbZDaeX_opk0ZprmNL_fY'
            ];
            $url = 'https://maps.googleapis.com/maps/api/distancematrix/json?';
            $data = file_get_contents($url . http_build_query($dataRequest));
            $data = json_decode($data);
            if (isset($data->rows[0]->elements[0]->distance->value)) {
                return round($data->rows[0]->elements[0]->distance->value / 1000);
            }
        }
        return null;
    }
}