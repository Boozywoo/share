<?php

namespace App\Validators\Tour;

use App\Models\Order;
use App\Models\OrderPlace;
use App\Models\Tour;

class StoreTourValidator
{
    public static function status($tour, $data)
    {
        $error = false;

        $status = array_get($data, 'status');
        
        if ($status == Tour::STATUS_COMPLETED) {
            $ordersCount = OrderPlace::whereHas('order', function ($q) use ($tour) {
                $q->filter([
                    'tour_id' => $tour->id,
                    'status' => Order::STATUS_ACTIVE,
                    'pull' => 0,
                ]);
            })->where('appearance', null)
                ->count();
            if ($ordersCount) $error = 'Вы должны поставить всем местам явку или неявку';
        }

        return $error;
    }
}