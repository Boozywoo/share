<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 029 29.03.19
 * Time: 22:24
 */

namespace App\Services\Pays;


use App\Models\Order;
use Voronkovich\SberbankAcquiring\Client as SBERBANK;
use Voronkovich\SberbankAcquiring\Currency;
use App\Models\Setting;
use YooKassa\Client as YOOKASSA;
use GuzzleHttp\Client as HTTP;

use Carbon\Carbon;
use DateTime;

class ServicePayService
{
    protected $returnUrl;
    protected $order;

    public function index(Order $order, $return_url = null)
    {
        $this->order = $order;
        $this->returnUrl = env('APP_URL') . '/order/result';
        $order->update(['type_pay' => Order::TYPE_PAY_WAIT]);
        if ($this->order->return_order_id)  {
            $order->returnOrder->update(['type_pay' => Order::TYPE_PAY_WAIT]);
        }
        return call_user_func_array(array($this, env('TYPE_PAY')), array($this->order));
    }

    public function getBinding(Order $order, ?string $bindingId = null)
    {
        $this->order = $order;
        $this->returnUrl = env('APP_URL') . '/profile/tickets/show/'.$order->id;

        return $this->{env('TYPE_PAY'). 'Binding'}($this->order, $bindingId);
    }

    public function getStatus(Order $order)
    {
        try {
            if (env('TYPE_PAY') == 'LIQPAY') {

                $client = new \LiqPay(env('PAY_LOGIN'), env('PAY_PASSWORD'));
                $result = $client->api("request", array(
                    'action' => 'status',
                    'version' => '3',
                    'order_id' => $order->id,
                    'result_url' => $this->returnUrl
                ));
                if (in_array($result->status, ['error', 'failure', 'reversed', 'unsubscribed'])) {
                    return Order::TYPE_PAY_CANCEL;
                } elseif (in_array($result->status, ['success', 'subscribed'])) {
                    $this->notification($order);
                    if ($order->return_order_id)  {
                        $order->returnOrder->update(['type_pay' => Order::TYPE_PAY_SUCCESS]);
                    }
                    return Order::TYPE_PAY_SUCCESS;
                } else return Order::TYPE_PAY_WAIT;

            } else if (env('TYPE_PAY') == 'YOOKASSA') {

                $client = new YOOKASSA();
                $client->setAuth(env('PAY_ID'), env('PAY_KEY'));

                $paymentId = $order->pay_id;
                $payment = $client->getPaymentInfo($paymentId);
                if ($payment->getPaid() == false && $payment->getStatus() == 'canceled') {
                    $order->update(['is_pay' => 0]);
                    if ($order->return_order_id)  {
                        $order->returnOrder->update(['type_pay' => Order::TYPE_PAY_CANCEL]);
                    }
                    return Order::TYPE_PAY_CANCEL;
                } elseif ($payment->getPaid() == true && $payment->getStatus() == 'succeeded') {
                    $order->update(['is_pay' => 1]);
                    $this->notification($order);
                    if ($order->return_order_id)  {
                        $order->returnOrder->update(['type_pay' => Order::TYPE_PAY_SUCCESS]);
                    }
                    return Order::TYPE_PAY_SUCCESS;
                } else return Order::TYPE_PAY_WAIT;

            } else if (env('TYPE_PAY') == 'ALFABANK' || env('TYPE_PAY') == 'WEBPAY' || env('TYPE_PAY') == 'SBERBANK') {

                if ($order->type_pay == Order::TYPE_PAY_CANCEL) {
                    return Order::TYPE_PAY_CANCEL;
                } elseif ($order->type_pay == Order::TYPE_PAY_WAIT) {
                    return Order::TYPE_PAY_WAIT;
                } elseif ($order->type_pay == Order::TYPE_PAY_SUCCESS) {
                    $this->notification($order);
                    return Order::TYPE_PAY_SUCCESS;
                } else return Order::TYPE_PAY_WAIT;
            } else  if (env('TYPE_PAY') === 'SBERMOBILE') {
                $client = new SBERBANK([
                    'userName' => env('PAY_LOGIN'),
                    'password' => env('PAY_PASSWORD'),
                    'language' => 'ru',
                    'currency' => Currency::RUB,
                    'apiUri' => SBERBANK::API_URI
                ]);
                $result = $client->getOrderStatus($order->pay_id);
                if (in_array($result['paymentAmountInfo']['paymentState'], ['DECLINED', 'REFUNDED'])) {
                    return Order::TYPE_PAY_CANCEL;
                } elseif ($result['paymentAmountInfo']['paymentState'] == 'CREATED') {
                    return Order::TYPE_PAY_WAIT;
                } elseif ($result['paymentAmountInfo']['paymentState'] == 'DEPOSITED') {
                    $this->notification($order);
                    
                    if(env('EKAM') == true) {
                        $this->ekam($order);
                    }
                    return Order::TYPE_PAY_SUCCESS;
                } else return Order::TYPE_PAY_WAIT;
            }
        } catch (\Exception $e) {
            //dd($e->getMessage());
        }
        return $order->type_pay;
    }

    public function getStatusDescription(Order $order)
    {
        if (env('TYPE_PAY') == 'SBERBANK') {
            $client = new SBERBANK([
                //'userName' => env('PAY_LOGIN'),
                //'password' => env('PAY_PASSWORD'),
                'token' => env('PAY_TOKEN'),
                'language' => 'ru',
                'currency' => Currency::RUB,
                'apiUri' => SBERBANK::API_URI
            ]);
            $result = $client->getOrderStatus($order->pay_id);
            return $result['actionCodeDescription'];
        }
    }

    public function SBERBANK(Order $order)
    {
        try {
            if (!$order->pay_url) {
                // $client = new SBERBANK([
                //     // 'token' => env('PAY_TOKEN'),
                //     'language' => 'ru',
                //     'currency' => Currency::RUB,
                //     'apiUri' => 'https://3dsec.sberbank.ru/'
                // ]);
                // // Required arguments
                // $result = $client->registerOrder($order->slug, $this->getPrice(), $this->returnUrl);
                // // return $result;
                // $order->update(['pay_url' => $result['formUrl'], 'pay_id' => $result['orderId']]);
                // if ($order->return_order_id)  {
                //     $order->returnOrder->update(['pay_url' => $result['formUrl'], 'pay_id' => $result['orderId']]);
                // }
                // return redirect($result['formUrl']);

                if($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_GET['orderId'])) {
                    $data = array(
                        'token' => env('PAY_TOKEN'),
                        'amount' => intval($this->getPrice())*100,
                        'orderNumber' => $order->slug,
                        'returnUrl' => env('APP_URL') . '/order/pay/on_success_sberbank',
                        'failUrl' => env('APP_URL') . '/order/pay/on_fail_sberbank'
                    );
                    $response = $this->gateway('https://securepayments.sberbank.ru/payment/rest/', 'register.do', $data);
                    if (isset($response['errorCode'])) { // В случае ошибки вывести ее
                        echo 'Ошибка #' . $response['errorCode'] . ': ' . $response['errorMessage'];
                    } else { // В случае успеха перенаправить пользователя на платежную форму
                        header('Location: ' . $response['formUrl']);
                        die();
                    }
                }
            }
        } catch (\Exception $e) {
            return redirect(env('APP_URL') . '/order/result?result=error&message=' . $e->getMessage());
        }
    }

    public function WEBPAY(Order $order)
    {
        try {
            if(!$order->pay_url) {
                $price = round($this->getPrice(), 2);
                $id = $order->slug;
                $company = $order->tour->bus->company;
                $public_id = env('PAY_ID');
                $url = env('APP_URL');
                $storeid = env('PAY_ID');
                $secretKey = env('PAY_KEY');
                $currency = ($order->tour->route->currency) ? $order->tour->route->currency->alfa : 'BYN';

                $signature = sha1('order-'.$order->id.$storeid.$order->slug.env('PAY_TEST').$currency.$price.$secretKey);

                $order->update(['pay_id' => $order->id]);

                return view('index.order.pay', compact('price', 'id', 'public_id', 'order', 'signature', 'url', 'storeid', 'currency'));
            } else {
                return redirect($order->pay_url);
            }
        } catch (\Exception $e) {
            return redirect(env('APP_URL') . '/order/result?result=error&message=' . $e->getMessage());
        }
    }

    public function ALFABANK(Order $order)
    {
        try {
            if(!$order->pay_url) {

                if($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_GET['orderId'])) {
                    $data = array(
                        'userName' => env('PAY_LOGIN'),
                        'password' => env('PAY_PASSWORD'),
                        'orderNumber' => $order->slug,
                        'amount' => $this->getPrice(),
                        'returnUrl' => env('APP_URL') . '/order/pay/on_success_alfabank',
                        'failUrl' => env('APP_URL') . '/order/pay/on_fail_alfabank'
                    );
                    $response = $this->gateway('https://pay.alfabank.ru/payment/rest/', 'register.do', $data);
                    if (isset($response['errorCode'])) { // В случае ошибки вывести ее
                        echo 'Ошибка #' . $response['errorCode'] . ': ' . $response['errorMessage'];
                    } else { // В случае успеха перенаправить пользователя на платежную форму
                        header('Location: ' . $response['formUrl']);
                        die();
                    }
                }
            }
        } catch (\Exception $e) {
            return redirect(env('APP_URL') . '/order/result?result=error&message=' . $e->getMessage());
        }
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

    public function LIQPAY(Order $order)
    {
        try {
            if (!$order->pay_url) {
                $client = new \LiqPay(env('PAY_LOGIN'), env('PAY_PASSWORD'));
                $html = $client->cnb_form(array(
                    'action' => 'pay',
                    'amount' => $this->getPrice(),
                    'currency' => $order->tour->route->currency ? $order->tour->route->currency->alfa : 'UAH',
                    'description' => 'оплата за заказ ' . $order->id,
                    'order_id' => $order->id,
                    'version' => '3'
                ));

                $order->update(['pay_id' => $order->id]);
                return view('index.order.pay.liqpay', compact('html'));
                //return redirect($result['formUrl']);
            }
        } catch (\Exception $e) {
            return redirect(env('APP_URL') . '/order/result?result=error&message=' . $e->getMessage());
        }
    }


    //Сбербанк для мобильного приложения
    public function SBERMOBILE(Order $order)
    {
        try {
            if (!$order->pay_url) {
                $client = new SBERBANK([
                    'userName' => env('PAY_LOGIN'),
                    'password' => env('PAY_PASSWORD'),
                    'language' => 'ru',
                    'currency' => Currency::RUB,
                ]);
                // Required arguments
                $result = $client->registerOrder($order->slug, $this->getPrice() * 100, $this->returnUrl, [
                    'failUrl' => $this->returnUrl . '/error',
                    'email' => $order->client->email,
                    'phone' => $order->client->phone,
                    'clientId' => $order->client->id,
//                    'pageView' => 'MOBILE',
//                    'features' => 'AUTO_PAYMENT',
                ]);

                $order->update(['pay_url' => $result['formUrl'], 'pay_id' => $result['orderId']]);
                if ($this->order->return_order_id)  {
                    $order->returnOrder->update(['pay_url' => $result['formUrl'], 'pay_id' => $result['orderId']]);
                }

                return $result['formUrl'];
            }
        } catch (\Exception $e) {
            throw $e;
            return env('APP_URL') . '/order/result?result=error&message=' . $e->getTraceAsString();
        }
    }

    //Сбербанк для мобильного приложения
    public function SBERMOBILEBinding(Order $order, ?string $bindingId = null)
    {
        try {
            $client = new SBERBANK([
                'userName' => env('PAY_LOGIN'),
                'password' => env('PAY_PASSWORD'),
                'language' => 'ru',
                'currency' => Currency::RUB,
            ]);

            if (!$bindingId) {
                $result = $client->getOrderStatus($order->pay_id);

                return $result['bindingInfo']['bindingId'];
            }

            $result = $client->registerOrder($order->slug, $this->getPrice() * 100, $this->returnUrl, [
                'failUrl' => $this->returnUrl . '/error',
                'email' => $order->client->email,
                'phone' => $order->client->phone,
                'clientId' => $order->client->id,
                'features' => 'AUTO_PAYMENT',
            ]);

            $order->update(['pay_url' => $result['formUrl'], 'pay_id' => $result['orderId']]);
            if ($this->order->return_order_id)  {
                $order->returnOrder->update(['pay_url' => $result['formUrl'], 'pay_id' => $result['orderId']]);
            }

            $result = $client->paymentOrderBinding($result['orderId'], $bindingId);

            $order->update(['type_pay' => Order::TYPE_PAY_SUCCESS]);
            
//            $result = $client->getOrderStatus($order->pay_id);

            return $result;
        } catch (\Exception $e) {
//            return $e->getMessage();
            throw $e;
        }
    }

    public function RNKB(Order $order)
    {
        try {
            if(!$order->pay_url) {
                $price = $this->getPrice();
                $id = $order->slug;
                $public_id = env('PAY_ID');
                $url = env('APP_URL');

                $order->update(['pay_id' => $order->id]);

                return view('index.order.pay.rnkb', compact('price', 'id', 'public_id', 'url'));
            }
        } catch (\Exception $e) {
            return redirect(env('APP_URL') . '/order/result?result=error&message=' . $e->getMessage());
        }
    }

    public function YOOKASSA(Order $order)
    {
        try {
            if(!$order->pay_url) {
                $client = new YOOKASSA();
                $client->setAuth(env('PAY_ID'), env('PAY_KEY'));

                $idempotenceKey = uniqid('', true);
                $response = $client->createPayment(
                    array(
                        'amount' => array(
                            'value' => $this->getPrice(),
                            'currency' => 'RUB',
                        ),
                        'confirmation' => array(
                            'type' => 'redirect',
                            'return_url' => env('APP_URL') . '/order/result',
                        ),
                        'capture' => true,
                        'description' => 'Номер брони '.$order->slug,
                    ),
                    $idempotenceKey
                );

                $order->update(['pay_id' => $response->getId()]);

                $confirmationUrl = $response->getConfirmation()->getConfirmationUrl();
                return redirect($confirmationUrl);
            }
        } catch (\Exception $e) {
            return redirect(env('APP_URL') . '/order/result?result=error&message=' . $e->getMessage());
        }
    }

    public function getPrice()
    {
        $price = $this->order->partial_prepaid  ? $this->order->prepaid_price : $this->order->price;
        if ($this->order->return_order_id)  {
            $price += $this->order->returnOrder->partial_prepaid  ? $this->order->returnOrder->prepaid_price : $this->order->returnOrder->price;
        }

        try {
            if (null !== \Auth::user() && (\Auth::user()->isAgent || \Auth::user()->isMediator)) {
                $price = $this->order->tour->route->bonus_agent_type ?
                    $price - ($price * $this->order->tour->route->bonus_agent / 100) :
                    $price - $this->order->tour->route->bonus_agent;
            }
        } catch (\Exception $exception) {
            // do nothing, auth exception
        }

        return $price;
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

    public function webpayStatusTransaction($transaction)
    {
        $postdata = '*API=&API_XML_REQUEST='.urlencode('
  <?xml version="1.0" encoding="ISO-8859-1" ?>
    <wsb_api_request>
      <!-- Название метода -->
      <command>get_transaction</command>
      <authorization>
        <!-- Логин от личного кабинета WEBPAY -->
        <username>minsk2</username>
        <!-- Пароль от личного кабинета WEBPAY, зашифрованный в MD5 -->
        <password>c1886c44bd46de9732eb6a9379d5bed5</password>
      </authorization>
      <fields>
        <!-- ID транзакции, по которой осуществляется проверка.
          Значение приходит в нотификаторе -->
        <transaction_id>'.$transaction.'</transaction_id>
      </fields>
    </wsb_api_request>
');
        $curl = curl_init ("https://billing.webpay.by");
        curl_setopt ($curl, CURLOPT_HEADER, 0);
        curl_setopt ($curl, CURLOPT_POST, 1);
        curl_setopt ($curl, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 0);
        $response = curl_exec ($curl); curl_close ($curl);
        $xml = simplexml_load_string($response);

        $json = json_encode($xml);

        $array = json_decode($json,TRUE)['fields'];

        return ['date' => $array['batch_timestamp']];
    }

    public static function webpayGetPaymentUrl(Order $order) {
        if (!$order->pay_url) {
            $url = env('APP_URL');
            $currency = ($order->tour->route->currency) ? $order->tour->route->currency->alfa : 'BYN';
            $seed = strval(rand(100, 1000));
            $data = ['wsb_version' => 2,
                'wsb_storeid' => env('PAY_ID'),
                //'wsb_store' => Setting::all()->first()->company_name,
                'wsb_order_num' => $order->slug,
                'wsb_test' => env('PAY_TEST'),
                'wsb_currency_id' => $currency,
                'wsb_seed' => $seed,
                'wsb_invoice_item_name' => preg_filter('/^/', trans('index.order.seat') . ' ', $order->orderPlaces->pluck('number')->toArray()),    // Получаем массив в виде : Место 1, Место 2
                'wsb_invoice_item_quantity' => array_values(array_fill(1, $order->orderPlaces->count(), 1)),
                'wsb_invoice_item_price' => $order->orderPlaces->pluck('price'),
                'wsb_total' => $order->price,
                'wsb_customer_name' => $order->client->name ?? '',
                'wsb_service_date' => $order->tour->date_time_start,
                'wsb_customer_address' => $order->transferAddress(),
                'wsb_return_url' => 'https://airport.minsk.by/ticket/' . $order->slug,
                'wsb_cancel_return_url' => 'https://airport.minsk.by/order/cancel',
                'wsb_notify_url' => $url . '/order/notice_pay',
                'wsb_signature' => sha1($seed . env('PAY_ID') . $order->slug . env('PAY_TEST') . $currency . $order->price . env('PAY_KEY')),
            ];

            $client = new HTTP([
                'base_uri' => 'https://payment.webpay.by/api/v1/payment',
                'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
                'body' => json_encode($data),
                "http_errors" => false,
            ]);
            
            \Log::info('Webpay payment: '.json_encode($data, JSON_UNESCAPED_UNICODE));

            $response = $client->request('POST');
            $body = json_decode($response->getBody());
            return ['status' => $response->getStatusCode(), 'body' => $body];
        }
    }

    public static function webpayGetPaymentInvoice(Order $order, $validTime = '20 minutes')
    {
        $jsonString = '{
            "resourceId":'.env('PAY_ID').',
            "resourceOrderNumber": "'.$order->slug.'",
            "creationTime": "' . date('c') . '",
            "validThrough": "' . date('c', strtotime('+' . $validTime)) . '",
            "languageCode":"ru",
            "customer":{
                "resourceCustomerId": "1",
                "phone": "' . $order->client->phone . '",
                "name": "' . $order->client->FullName . '"
            },
            "items":[{
                "idx":0,
                "name":"Билет: ' . $order->tour->route->name . ' (место №'. implode (', место №', $order->orderPlaces->pluck('number')->toArray()) . ')",
                "quantity": 1,
                "price":{"currency":"BYN","amount":' . $order->price . '}}],
            "total":{"currency":"BYN","amount":' . $order->price . '},
            "urls":{
                "resourceReturnUrl":"' . ($order->payment_baсk_url ?? 'https:\/\/airport.minsk.by\/ticket\/' . $order->slug) . '",
                "resourceCancelUrl":"https:\/\/airport.minsk.by\/order\/cancel",
                "resourceNotifyUrl":"' . trim(json_encode(env('APP_URL')), '"') . '\/order\/notice_pay"
            }
        }';

        $nonce = bin2hex(random_bytes(18));
        $storeid = env('PAY_ID');
        $secret = env('PAY_KEY');

        $stringToSign = "POST\n/woc/order\napplication/json;charset=utf-8\n$storeid\n$nonce\n$jsonString\n";

        $digest = hash_hmac('sha512', $stringToSign, $secret, true);
        $hmacString = 'HmacSHA512 '.$storeid.':'.$nonce.':'.base64_encode($digest);

        $headers = array(
            "Authorization: $hmacString",
            "Content-type: application/json;charset=utf-8"
        );

        $url = 'https://api.webpay.by/woc/order';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonString);
        $content = curl_exec($ch);
        $curlError = curl_error($ch);
        curl_close($ch);

        \Log::info("Webpay invoice.\nData sent:\n" . $jsonString . "\n\nAnswer:\n".$content.$curlError);
        $content = json_decode($content);
        if (empty($content->errorCode) && empty($curlError))    {
            return ['status' => 'ok', 'url' => $content->invoiceUrl];
        } else {
            return ['status' => 'error', 'message' => $content->errorMessage ?? $curlError];
        }
    }
    
}
