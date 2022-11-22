<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 017 17.03.19
 * Time: 15:53
 */

namespace App\Services\Order\Integrations\MosGorTrans;


use App\Models\Order;
use App\Services\Integrations\AvtovokzalRu\AvtovokzalRuService;

class UpdateOrder
{
    public static function index($uid, $data)
    {
        $client = new AvtovokzalRuService();
        $order = $client->get_order($uid);
        $order = $client->update_ticket($order->tickets[0]->id, $data);
        return $order->id;
    }
}