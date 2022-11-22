<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 005 05.04.19
 * Time: 18:28
 */

namespace App\Services\Order;


use App\Models\Order;
use Carbon\Carbon;

class PrintOrderService
{
    public static function index(Order $order)
    {
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('app/ticket.docx'));
        $templateProcessor->setValue('orderId', $order->id);
        $templateProcessor->setValue('route', $order->tour->route->name);
        $templateProcessor->setValue('client', $order->client ? $order->client->FullName : '');
        $additionalClients = [];
        foreach ($order->orderPlaces as $orderPlace) {
            $fullName = trim($orderPlace->fullName);
            if (!empty($fullName)) {
                $additionalClients[] = $fullName;
            }
        }
        $templateProcessor->setValue('additionalClients', implode("\n,", $additionalClients));
        $templateProcessor->setValue('passenger', $order->orderPlaces->count() > 1 ? 'Пассажиры' : 'Пассажир');
        $templateProcessor->setValue('title_places', $order->orderPlaces->count() > 1 ? 'Места' : 'Место');
        $templateProcessor->setValue('price', $order->price);

        $interval = StationIntervalsService::index($order->tour->route->id, $order->stationFrom->id, $order->stationTo->id);

        $dateStart = $order->tour->date_start->format('Y-m-d');

        $DateTimeStart = Carbon::createFromTimeString($dateStart . ' ' . $order->tour->time_start)->addMinutes($interval[0]);
        $templateProcessor->setValue('timeStart', $DateTimeStart->format('H:i:s'));
        $templateProcessor->setValue('dateStart', $DateTimeStart->format('Y-m-d'));
        $templateProcessor->setValue('stationFromCity', $order->stationFrom->city->name);
        $templateProcessor->setValue('stationFromName', $order->stationFrom->name);


        $DateTimeFinish = Carbon::createFromTimeString($dateStart . ' ' . $order->tour->time_start)->addMinutes($interval[1] - $interval[0]);
        $templateProcessor->setValue('timeFinish', $DateTimeFinish->format('H:i:s'));
        $templateProcessor->setValue('dateFinish', $DateTimeFinish->format('Y-m-d'));
        $templateProcessor->setValue('stationToCity', $order->stationTo->city->name);
        $templateProcessor->setValue('stationToName', $order->stationTo->name);

        $templateProcessor->setValue('busName', $order->tour->bus->name);
        $templateProcessor->setValue('busNumber', $order->tour->bus->number);
        $templateProcessor->setValue('driverWorkPhone', $order->tour->driver->work_phone);
        $templateProcessor->setValue('agent', $order->operator ? $order->operator->fullName : '');
        $templateProcessor->setValue('place', implode(',', $order->orderPlaces->pluck('number')->toArray()));
        $templateProcessor->saveAs(storage_path("app/tickets/$order->id.docx"));

        /*$phpWord = \PhpOffice\PhpWord\IOFactory::load(storage_path("app/tickets/$order->id.docx"));

        define('PHPWORD_BASE_DIR', realpath(__DIR__));
        $domPdfPath = base_path( 'vendor/dompdf/dompdf');
        \PhpOffice\PhpWord\Settings::setPdfRendererPath($domPdfPath);
        \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');

        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord , 'PDF');
        $xmlWriter->save(storage_path("app/tickets/$order->id.pdf"));*/

        return response()->download(storage_path("app/tickets/$order->id.docx"));
    }
}