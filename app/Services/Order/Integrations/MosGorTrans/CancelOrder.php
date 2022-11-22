<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 017 17.03.19
 * Time: 8:39
 */

namespace App\Services\Order\Integrations\MosGorTrans;


use App\Services\Integrations\AvtovokzalRu\AvtovokzalRuService;

class CancelOrder
{
    public function index($orderId)
    {
        $client = new AvtovokzalRuService();
        $client->cancel_order($orderId);
    }
}