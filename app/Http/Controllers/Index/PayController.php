<?php

namespace App\Http\Controllers\Index;
use Illuminate\Http\Request;    

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use App\Services\Pays\ServicePayService;

use Carbon\Carbon;
use GuzzleHttp\Client as HTTP;

class PayController extends Controller
{
    public function onSuccessRNKB(Request $request)
    {  
        $order = Order::where('slug', $request->get('invoiceId'))->first();
        $order->type_pay = Order::TYPE_PAY_SUCCESS;
        $order->is_pay = 1;
        $order->save();

        $this->notification($order);

        if(env('EKAM') == true) {
            $this->ekam($order);
        }

        if ($order->returnOrder)    {
            $order->returnOrder->update(['pay_id' => $order->id, 'type_pay' => Order::TYPE_PAY_SUCCESS]);
        }
        return redirect(env('APP_URL') . '/order/result');
    }

    public function onFailRNKB(Request $request)
    {

        $order = Order::where('slug', $request->get('invoiceId'))->first();
        
        $order->type_pay = Order::TYPE_PAY_CANCEL;
        $order->is_pay = 0;
        $order->save();

        $this->notification(($order));

        if ($order->returnOrder)    {
            $order->returnOrder->update(['pay_id' => $order->id, 'type_pay' => Order::TYPE_PAY_CANCEL]);
        }

        return redirect(env('APP_URL') . '/order/result');
    }

    public function onSuccessWebpay(Request $request) {

        if($request->get('wsb_tid')) {
            
            /*$order = Order::where('slug', $request->get('wsb_order_num'))->first();
            $order->type_pay = Order::TYPE_PAY_SUCCESS;
            $order->is_pay = 1;
            $order->pay_id = $request['wsb_tid'];
            $order->save();

            $this->notification(($order));

            if(env('EKAM') == true) {
                $this->ekam($order);
            }

            if ($order->returnOrder)    {
                $order->returnOrder->update(['pay_id' => $request['wsb_tid'], 'type_pay' => Order::TYPE_PAY_SUCCESS, 'is_pay' => 1]);
            } */
            
            return redirect(env('APP_URL') . '/order/result');
        }
    }

    public function onSuccessAlfabank(Request $request)
    {
        $data = array(
            'userName' => env('PAY_LOGIN'),
            'password' => env('PAY_PASSWORD'),
            'orderId' => $request['orderId']
        );
        
        $response = $this->gateway('https://pay.alfabank.ru/payment/rest/','getOrderStatus.do', $data);
        $order = Order::where('slug', $response['OrderNumber'])->first();
        $order->type_pay = Order::TYPE_PAY_SUCCESS;
        $order->is_pay = 1;
        $order->pay_id = $request['orderId'];
        $order->save();

        $this->notification(($order));

        if(env('EKAM') == true) {
            $this->ekam($order);
        }

        if ($order->returnOrder)    {
            $order->returnOrder->update(['pay_id' => $request['orderId'], 'type_pay' => Order::TYPE_PAY_SUCCESS, 'is_pay' => 1]);
        } 
        
        return redirect(env('APP_URL') . '/order/result');
    }


    public function onSuccessSberbank(Request $request)
    {
        $data = array(
            'token' => env('PAY_TOKEN'),
            'orderId' => $request['orderId']
        );
        
        $response = $this->gateway('https://securepayments.sberbank.ru/payment/rest/','getOrderStatusExtended.do', $data);
        if($response['orderStatus'] == 2) {
            $slug = $response['orderNumber'];
            $order = Order::where('slug', $slug)->first();
            $order->type_pay = Order::TYPE_PAY_SUCCESS;
            $order->is_pay = 1;
            $order->pay_id = $request['orderId'];
            $order->save();

            $this->notification(($order));

            if(env('EKAM') == true) {
                $this->ekam($order);
            }

            if ($order->returnOrder)    {
                $order->returnOrder->update(['pay_id' => $request['orderId'], 'type_pay' => Order::TYPE_PAY_SUCCESS, 'is_pay' => 1]);
            } 
            
            return redirect(env('APP_URL') . '/order/result');
        }
    }

    public function notification(Order $order) {
        $was_pushed = 0;
        if(Setting::all()->first()->turn_on_notification_if_order_paid && $order->confirm == 1 && $order->type != 'no_completed') {
            $mail["name"] = (empty($order->client->first_name) ? 'Не введено имя' : $order->client->first_name) . " " . 
                (empty($order->client->middle_name) ? ' ' : $order->client->middle_name)
            . " " . (empty($order->client->last_name) ? ' ' : $order->client->last_name);
            $mail["phone"] = empty($order->client->phone) ? 'Не введен телефон' : $order->client->phone;
            $mail["tour"] = $order->tour->route->name;
            $mail["from"] = $order->stationFrom->name . ", " . $order->stationFrom->city->name; 
            $mail["to"] = $order->stationTo->name . ", " . $order->stationTo->city->name;
            $mail["date"] = $order->tour->date_start->format('d.m.Y');
            $mail["time"] = Carbon::createFromTimeString($order->tour->date_start->format('Y-m-d') . ' ' . $order->tour->time_start)->addMinutes($order->orderPlaces->start_min)->toW3cString();
            $mail["subject"] = "Новая оплаченная бронь!";  
            $mail["main_email"] = Setting::all()->first()->main_email;
            $mail["count"] = $order->count_places;
            $mail["price"] = $order->price;
            $mail["currency"] = ($order->tour->route->currency) ? $order->tour->route->currency->alfa : 'BYN';

            if ($was_pushed == 1) return;
            if($order->status == 'active') {
                \Mail::send('admin.mail.mail', ['data' => $mail], function ($message) use ($mail) {
                    $message->to($mail["main_email"])
                        ->subject($mail["subject"]);
                });
                $was_pushed = 1; 
            }  
        }
    }

    public function ekam(Order $order) {

        $body = json_encode( [
            'order_id' => $order->id,
            'order_number' => $order->slug,
            'type' => 'SaleReceiptRequest',
            'phone_number' => $order->client->phone,
            'should_print' => true,
            'cash_amount' => ($order->type_pay == 'cash-payment' || $order->type_pay == null) ? intval($order->price) : 0,
            'electron_amount' => $order->type_pay == 'success' ? $order->price : 0,
            "lines" => [
                [
                    'price' => $order->price / $order->orderPlaces->count(),
                    'quantity' => $order->orderPlaces->count(),
                    'title' => 'Организация перевозок пассажиров и багажа по заказу',
                    'total_price' => intval($order->price),
                    'vat_rate' => null,
                    'fiscal_product_type' => 4,
                    'payment_case' => 1,
                ] 
            ] 
        ]);

        $client = new HTTP([
            'base_uri' => 'https://app.ekam.ru/api/online/v2/receipt_requests',
            'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json', 'X-Access-Token' => env('EKAM_TOKEN')],
            'body' => $body,
        ]);

        $response = $client->request('POST');
    }
    
    public function onFailWebpay(Request $request) {
        $order = Order::where('slug', $request['wsb_order_num'])->first();
        
        $order->type_pay = Order::TYPE_PAY_CANCEL;
        $order->is_pay = 0;
        $order->save();

        $this->notification(($order));

        if ($order->returnOrder)    {
            $order->returnOrder->update(['type_pay' => Order::TYPE_PAY_CANCEL, 'is_pay' => 0]);
        } 

        return redirect(env('APP_URL') . '/order/result?result=error&message=' . 'Платеж отклонен');
    }

    public function onFailAlfabank(Request $request)
    {
        $data = array(
            'userName' => env('PAY_LOGIN'),
            'password' => env('PAY_PASSWORD'),
            'orderId' => $request['orderId']
        );
        
        $response = $this->gateway('https://pay.alfabank.ru/payment/rest/', 'getOrderStatus.do', $data);
        $order = Order::where('slug', $response['OrderNumber'])->first();
        
        \Log::info($response['OrderNumber']);
        $order->type_pay = Order::TYPE_PAY_CANCEL;
        $order->pay_id = $request['orderId'];
        $order->is_pay = 0;
        $order->save();

        $this->notification(($order));

        if ($order->returnOrder)    {
            $order->returnOrder->update(['pay_id' => $request['orderId'], 'type_pay' => Order::TYPE_PAY_CANCEL, 'is_pay' => 0]);
        } 

        return redirect(env('APP_URL') . '/order/result?result=error&message=' . 'Платеж отклонен');
    }

    public function onFailSberbank(Request $request)
    {
        $data = array(
            'token' => env('PAY_TOKEN'),
            'orderId' => $request['orderId']
        );
        
        $response = $this->gateway('https://securepayments.sberbank.ru/payment/rest/','getOrderStatusExtended.do', $data);
        if($response['orderStatus'] != 2) {
            $slug = $response['orderNumber'];
            $order = Order::where('slug', $slug)->first();
            $order->type_pay = Order::TYPE_PAY_CANCEL;
            $order->pay_id = $request['orderId'];
            $order->is_pay = 0;
            $order->save();

            $this->notification(($order));

            if ($order->returnOrder)    {
                $order->returnOrder->update(['pay_id' => $request['orderId'], 'type_pay' => Order::TYPE_PAY_CANCEL, 'is_pay' => 0]);
            } 

            return redirect(env('APP_URL') . '/order/result?result=error&message=' . 'Платеж отклонен');
        }
    }

    public function paymentPage($slug)
    {
        if (!empty($slug))  {
            $order = Order::where('slug', $slug)->first();
            if ($order) {
                if  ($order->status == Order::STATUS_DISABLE ) {
                    return 'Бронь отменена'; 
                }
                if  ($order->type_pay == Order::TYPE_PAY_SUCCESS) {
                    return 'Бронь уже оплачена';
                }
                if ($order->pay_url) {
                    return redirect($order->pay_url);
                }
                $result = ServicePayService::webpayGetPaymentInvoice($order);
                if ($result['status'] == 'ok')  {
                    $order->update(['pay_url' => $result['url']]);
                    return redirect($result['url']);
                } else {
                    return trans('messages.index.order.error_two');
                }
            }
        };
        
        return 'Неверный номер брони';
    }

    function gateway($url, $method, $data) {
        $curl = curl_init(); // Инициализируем запрос
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url.$method, // Полный адрес метода
            CURLOPT_RETURNTRANSFER => true, // Возвращать ответ
            CURLOPT_POST => true, // Метод POST
            CURLOPT_POSTFIELDS => http_build_query($data) // Данные в запросе
        ));
        $response = curl_exec($curl); // Выполняем запрос
         
        $response = json_decode($response, true); // Декодируем из JSON в массив
        curl_close($curl); // Закрываем соединение
        return $response; // Возвращаем ответ
    }
}