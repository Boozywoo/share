<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 017 17.03.19
 * Time: 8:38
 */

namespace App\Services\Order\Integrations\MosGorTrans;


use App\Models\Tour;
use App\Services\Integrations\AvtovokzalRu\AvtovokzalRuService;

class BookingOrder
{
    public static function getTicketUrl($ticket)
    {
        $client = new AvtovokzalRuService();
        return $client->getUrlTicket($ticket);
    }

    public static function index(Tour $tour, $data)
    {
        $client = new AvtovokzalRuService();
        return $client->book_order($tour->integration_uid, $data)->id;
    }
}