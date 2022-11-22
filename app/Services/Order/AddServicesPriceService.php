<?php

namespace App\Services\Order;

use App\Models\Order;

class AddServicesPriceService
{
    public static function index(Order $order) // Добавляем цену доп. сервисов в заказ
    {
        foreach ($order->addServices as $item) {
            $order->price += $item->value*$item->pivot->quantity;
        }

        return $order;
    }

    public static function getPrice(Order $order)   // Возвращает суммарную стоимость всех доп. сервисов в заказе
    {
        $price = 0;
        foreach ($order->addServices as $item) {    
            $price += $item->value*$item->pivot->quantity;
        }
        return $price;
    }
    
}