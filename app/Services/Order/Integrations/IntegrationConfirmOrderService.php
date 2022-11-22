<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 017 17.03.19
 * Time: 8:45
 */

namespace App\Services\Order\Integrations;


use App\Models\Tour;

class IntegrationConfirmOrderService
{
    public static function index($UId)
    {
        return MosGorTrans\ConfirmOrder::index($UId);
    }
}