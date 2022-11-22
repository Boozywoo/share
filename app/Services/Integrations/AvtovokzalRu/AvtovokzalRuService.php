<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 004 04.03.19
 * Time: 9:04
 */

namespace App\Services\Integrations\AvtovokzalRu;

use App\Models\Client;

define('SYSPATH', '');
//define('DEBUG_MODE', true);

require __DIR__ . '/classes/Tools.php';
require __DIR__ . '/classes/Server.php';
require __DIR__ . '/classes/Depot.php';
require __DIR__ . '/classes/Country.php';
require __DIR__ . '/classes/Region.php';
require __DIR__ . '/classes/Point.php';
require __DIR__ . '/classes/Race.php';
require __DIR__ . '/classes/RaceType.php';
require __DIR__ . '/classes/RaceClass.php';
require __DIR__ . '/classes/RaceStatus.php';
require __DIR__ . '/classes/RaceSummary.php';
require __DIR__ . '/classes/Stop.php';
require __DIR__ . '/classes/Seat.php';
require __DIR__ . '/classes/TicketType.php';
require __DIR__ . '/classes/DocType.php';
require __DIR__ . '/classes/Sale.php';
require __DIR__ . '/classes/Benefit.php';
require __DIR__ . '/classes/Order.php';
require __DIR__ . '/classes/Ticket.php';
require __DIR__ . '/classes/TicketFare.php';
require __DIR__ . '/classes/Company.php';
require __DIR__ . '/classes/User.php';
require __DIR__ . '/classes/Reference.php';
require __DIR__ . '/classes/ReferenceItem.php';

class AvtovokzalRuService
{
    protected $clientAvtovokzal;
    protected $server_url;

    public function __construct()
    {
        date_default_timezone_set('Asia/Novosibirsk');

        $this->server_url = 'http://webapp.avtovokzal.ru/gdstest';
        $login = 'minsktrans-ap2';
        $password = 'VbycrNhfyc';

        //$dispatch_point_name = 'Москва';
        //$arrival_point_name = 'Тула';

        $this->clientAvtovokzal = new \Gate_Gds_Server($this->server_url . '/soap/sales?wsdl', $login, $password);
    }

    public function get_dispatch_points()
    {
        return $this->clientAvtovokzal->get_dispatch_points(/*$region->id*/); // Получение списка мест, в которых есть автовокзалы
    }

    public function get_point_depots($dispatch_point_id)
    {
        return $this->clientAvtovokzal->get_point_depots($dispatch_point_id);
    }

    public function get_arrival_points($dispatch_point_id, $arrival_point_name)
    {
        return $this->clientAvtovokzal->get_arrival_points($dispatch_point_id, $arrival_point_name);
    }

    public function get_races($dispatch_point_id, $arrival_point_id, $dispatch_date)
    {
        return $this->clientAvtovokzal->get_races($dispatch_point_id, $arrival_point_id, $dispatch_date);
    }

    public function get_race_summary($race_uid)
    {
        return $this->clientAvtovokzal->get_race_summary($race_uid);
    }

    public function book_order($raceUid, $data)
    {
        //$countries = $this->clientAvtovokzal->get_countries();
        //$country = find_by_code($countries, 'BY');

        $race = $this->get_race_summary($raceUid);

        $sale = new \Gate_Gds_Sale();
        $sale->lastName = $data['last_name'];
        $sale->firstName = $data['first_name'];
        $sale->middleName = $data['middle_name'];
        $sale->docTypeCode = 52;
        $sale->docSeries = preg_replace('/[0-9]+/', '', $data['passport']);
        $sale->docNum = preg_replace('/\D/', '', $data['passport']);
        $sale->gender = \Gate_Gds_Sale::GENDER_MALE;
        $sale->birthday = $data['birth_day'];
        $sale->phone = $data['phone'];
        $sale->citizenship = 'Беларусь';

        $sale->seatCode = $race->seats[0]->code;
        if (!empty($data['places'][0])) {
            foreach ($race->seats as $seat) {
                if ($seat->name == 'Место ' . $data['places'][0]) {
                    $sale->seatCode = $seat->code;
                    break;
                }
            }
        }
        $sale->ticketTypeCode = $race->ticketTypes[0]->code;

        // Бронирование билета
        return $this->clientAvtovokzal->book_order($race->depot->id, $race->race->uid, array($sale));
    }

    public function confirm_order($orderId)
    {
        return $this->clientAvtovokzal->confirm_order($orderId, 'Электронный платеж');
    }

    public function get_order($order_id)
    {
        return $this->clientAvtovokzal->get_order($order_id);
    }

    public function get_ticket($ticket)
    {
        return $this->clientAvtovokzal->get_ticket($ticket->id);
    }

    public function update_ticket($ticketId, $data)
    {
        $countries = $this->clientAvtovokzal->get_countries();
        $country = find_by_code($countries, 'BY');
        $sale = new \Gate_Gds_Sale();
        $sale->lastName = $data['last_name'];
        $sale->firstName = $data['first_name'];
        $sale->middleName = $data['middle_name'];
        $sale->docTypeCode = 52;
        $sale->docSeries = preg_replace('/[0-9]+/', '', $data['passport']);
        $sale->docNum = preg_replace('/\D/', '', $data['passport']);

        $sale->gender = \Gate_Gds_Sale::GENDER_MALE;
        $sale->citizenship = $country->name;
        $sale->birthday = $data['birth_day'];

        return $this->clientAvtovokzal->update_ticket($ticketId, $sale);
    }

    public function cancel_order($orderId)
    {
        return $this->clientAvtovokzal->cancel_order($orderId);
    }

    public function getUrlTicket($ticket)
    {
        return $this->server_url . '/mvc/download/' . $ticket->hash . '.pdf';
    }
}