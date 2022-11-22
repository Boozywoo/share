<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 017 17.03.19
 * Time: 8:37
 */

namespace App\Services\Order\Integrations;


use App\Models\Order;
use App\Models\Tour;
use App\Services\Order\Integrations\MosGorTrans\BookingOrder;

class IntegrationBookingOrderService
{
    public static function index(Tour $tour, $data)
    {
        return MosGorTrans\BookingOrder::index($tour, $data);
    }

    public static function getTicketUrl($ticket)
    {
        return MosGorTrans\BookingOrder::getTicketUrl($ticket);
    }
}