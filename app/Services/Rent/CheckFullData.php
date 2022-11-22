<?php


namespace App\Services\Rent;


use App\Models\Tour;

class CheckFullData
{
    public static function index(Tour $tour)
    {
        $checkedArrayTour = ['driver_id', 'bus_id'];
        foreach ($checkedArrayTour as $item) {
            if (!$tour->$item) {
                return false;
            }
        }

        $checkedArrayRent = ['agreement_id', 'tariff_id', 'company_carrier_id', 'company_customer_id'];
        foreach ($checkedArrayRent as $item) {
            if ($tour->rent && !$tour->rent->$item) {
                return false;
            }
        }
        return true;
    }
}