<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 017 17.03.19
 * Time: 8:39
 */

namespace App\Services\Order\Integrations\MosGorTrans;


use App\Models\Tour;
use App\Services\Integrations\AvtovokzalRu\AvtovokzalRuService;

class ConfirmOrder
{
    public static function index($Uid)
    {
        $client = new AvtovokzalRuService();
        $order = $client->confirm_order($Uid);
        \Log::info(print_r($order,1));
        return $order;
    }
}