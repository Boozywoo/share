<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 017 17.03.19
 * Time: 15:45
 */

namespace App\Services\Order\Integrations;


use App\Models\Order;

class IntegrationUpdateOrderService
{
    public static function index($uid, $data)
    {
        return MosGorTrans\UpdateOrder::index($uid, $data);
    }
}