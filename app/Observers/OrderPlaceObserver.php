<?php

namespace App\Observers;

use App\Models\OrderPlace;
use App\Services\Order\FragmentationOrder;

class OrderPlaceObserver
{
    public function creating(OrderPlace $place)
    {
        FragmentationOrder::setNumber($place);
        FragmentationOrder::setStationToFrom($place);
    }
}