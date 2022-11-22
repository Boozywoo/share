<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use Carbon\Carbon;

use Illuminate\Http\Request;
use PDF;

class OrderToEmailController extends Controller
{
    public function sendPDF($orderId)
    {
        $order = $orderId;
        $error = (!auth()->user()->client->email) ? true : false;

        if ($error) {
            return $this->responseError(
              ['message' => trans('index.profile.please_enter_email_title'),
                'view' => view('index.partials.elements.pop_up.input_email', compact('order', 'data'))
                  ->render()]);
        }

        $order = Order::with(['client', 'stationFrom', 'stationTo', 'tour.route', 'orderPlaces'])
          ->whereId($orderId)
          ->where('client_id', auth()->user()->client_id)
          ->first();

        $settings = Setting::first();

        $lang = $settings->ticket_language ?? 'ua';
        $filename = 'Order ' . $order->slug . '.pdf';

        $data = [
          'settings' => $settings,
          'order' => $order,
          'lang' => $lang,
          'date_issue' => Carbon::createFromTimestamp(strtotime($order->updated_at))->format('H:i M d, Y'),
          'title' => $filename
        ];

        $template = $this->setTemplate($settings);

        $pdf = PDF::loadView($template, $data);

        return $this->sendMail($pdf);
    }

    private function setTemplate(Setting $settings)
    {
        switch ($settings->ticket_type) {
            case "2":
            {
                $template = 'index.print.order_inf';
                break;
            }
            case "3":
                {
                    $template = 'index.print.order_ijtransfer';
                    break;
                }
            default:
            {
                $template = 'index.print.order';
                break;
            }
        }

        return $template;
    }

    private function sendMail($pdf)
    {
        $data["email"] = auth()->user()->client->email;
        $data["client_name"] = auth()->user()->client->first_name;
        $data["subject"] = "Билет";
        $data["filename"] = "ticket";

        try {
            \Mail::send('mail.order.new_ticket', $data, function ($message) use ($data, $pdf) {
                $message->to($data["email"], $data["client_name"])
                  ->subject($data["subject"])
                  ->attachData($pdf->output(), $data["filename"] . ".pdf", [
                    'mime' => 'application/pdf',
                  ]);
            });
        } catch (JWTException $exception) {

        }

        if (\Mail::failures()) {
            return $this->responseError(['message' => trans('index.profile.email_sent_error')]);
        } else {
            return $this->responseSuccess(['message' => trans('index.profile.email_sent_success')]);
        }


    }
}