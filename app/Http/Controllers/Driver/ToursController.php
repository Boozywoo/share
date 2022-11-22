<?php

namespace App\Http\Controllers\Driver;

use App\Models\DriverAppSetting;
use App\Models\Tour;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\Tour\YandexRoutingService;
use App\Services\Pays\ServiceOnlineCheckBBV;

class ToursController extends Controller 
{
    protected $ids_time_show_driver = [];
    protected $ids_time_click_driver = [];

    protected $time_limit_db;

    public function __construct() 
    {
        $this->middleware('isDriver');
    }

    public function getToursToday() 
    {
        $driver = \Auth::guard('driver')->user();
        
        if($driver->is_admin_driver) {
            $today = date('Y-m-d');
            $date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
            $yesterday = date('Y-m-d', strtotime($date .' -1 day'));
            
            $matchThese = ['date_start' => $yesterday, 'date_finish' => $today];
            $orThose = ['date_start' => $today];

            $tours = Tour::where($matchThese)->orWhere($orThose)->get()->sortBy('date_time_start');
        } else {
            $tours = $driver->toursToday->sortBy('date_time_start');
        }
        $this->data($tours, 'time_show_driver'); 
        $this->data($tours, 'time_click_driver');

        $this->addServicesCash($tours);

        return view('driver.tours', compact('driver', 'tours'))->with('ids_time_show_driver', $this->ids_time_show_driver)
            ->with('ids_time_click_driver', $this->ids_time_click_driver)->with('time_limit_db', $this->time_limit_db);
    }

    public function getToursTomorrow() 
    {
        $driver = \Auth::guard('driver')->user(); 

        if($driver->is_admin_driver) {
            $today = date('Y-m-d');
            $date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
            $tomorrow = date('Y-m-d', strtotime($date .' +1 day')); 
              
            $matchThese = ['date_start' => $today, 'date_finish' => $tomorrow];
            $orThose = ['date_start' => $tomorrow];

            $tours = Tour::where($matchThese)->orWhere($orThose)->get()->sortBy('date_time_start');
        } else {
            $tours = $driver->toursTomorrow->sortBy('time_start');
        }
        
        $this->data($tours, 'time_show_driver'); 
        $this->data($tours, 'time_click_driver');

        $this->addServicesCash($tours);

        return view('driver.tours', compact('driver', 'tours'))->with('ids_time_show_driver', $this->ids_time_show_driver)->with('ids_time_click_driver', $this->ids_time_click_driver)
            ->with('time_limit_db', $this->time_limit_db);
    }

    public function getToursOnWeek() 
    {
        $driver = \Auth::guard('driver')->user();

        if($driver->is_admin_driver) {
            $today = date('Y-m-d');
            $endOfWeek = (date('l') == 'Sunday') ? date('Y-m-d') : date('Y-m-d', strtotime('sunday'));
            $tours = Tour::whereBetween('date_start', [$today, $endOfWeek])->get()->sortBy('date_time_start');
        } else {
            $tours = $driver->toursWeek->sortBy('date_time_start');
        }

        $this->data($tours, 'time_show_driver'); 
        $this->data($tours, 'time_click_driver');

        $this->addServicesCash($tours);

        return view('driver.tours', compact('driver', 'tours'))->with('ids_time_show_driver', $this->ids_time_show_driver)
            ->with('ids_time_click_driver', $this->ids_time_click_driver)->with('time_limit_db', $this->time_limit_db);
    }

    public function getToursOnMonth() 
    {
        $driver = \Auth::guard('driver')->user();

        if($driver->is_admin_driver) {
            $tours = Tour::where(function($query) 
            { 
                $today = date('Y-m-d'); 
                $last_day_this_month  = date('Y-m-t');

                $query->whereBetween('date_start', [$today, $last_day_this_month]);
            })->get()->sortBy('date_time_start');
        } else {
            $tours = $driver->toursMonth->sortBy('date_time_start');
        }
        
        $this->data($tours, 'time_show_driver'); 
        $this->data($tours, 'time_click_driver');

        $this->addServicesCash($tours);
        
        return view('driver.tours', compact('driver', 'tours'))->with('ids_time_show_driver', $this->ids_time_show_driver)
            ->with('ids_time_click_driver', $this->ids_time_click_driver)->with('time_limit_db', $this->time_limit_db);
    }

    public function data($tours, $type)
    {
        $date_time_now = strtotime(date('Y-m-d H:i:s'));

        $time_diff = [];
        $i = 0;

        $this->time_limit_db = DriverAppSetting::pluck($type)->first();  
        $time_limit = $this->time_limit_db * 3600;

        foreach ($tours as $tour) 
        {
            $date = $tour->date_start->format('Y-m-d');
            $time = $tour->time_start;
            $time_start = strtotime($date . ' ' . $time); 
            $diff = (int)$time_start - $date_time_now;
            array_push($time_diff, $diff);

            if($time_diff[$i] <= $time_limit) 
            {   
                if($type == 'time_show_driver'){
                    array_push($this->ids_time_show_driver, $tour->id);
                } else {
                    array_push($this->ids_time_click_driver, $tour->id);
                }
            }
            $i++;
        }
    }

    public function buildRoute(Tour $tour)
    {
        $buildRoute = YandexRoutingService::build($tour);

        if ($buildRoute['result'] == 'success'){
            return view('index.partials.redirect', ['url' => $buildRoute['url'], 'seconds' => $buildRoute['delay']]);
        } else {
            echo $buildRoute['message'];
        }

    }

    public function navigatorLink(Tour $tour)       // Возвращает ссылку для постороения маршрута по всем точкам в яндекс-навигаторе 
    {
        $json = file_get_contents('https://courier.yandex.ru/vrs/api/v1/result/mvrp/'.$tour->mvrp_id);
        $obj = json_decode($json);
        if (!empty($obj->result))   {
            $routePoints = $obj->result->routes[0]->route;
            $last = count($routePoints)-1;
            $link = 'yandexnavi://build_route_on_map?lat_to=' . $routePoints[$last]->node->value->point->lat . '&lon_to=' . $routePoints[$last]->node->value->point->lon;
            for ($i = 1; $i < $last; $i++) {
                $link .= '&lat_via_' . ($i-1) . '=' . $routePoints[$i]->node->value->point->lat;
                $link .= '&lon_via_' . ($i-1) . '=' . $routePoints[$i]->node->value->point->lon;
            }
            return redirect($link);
        } else {
            echo $obj->message . ($obj->error ?? '') . ($obj->error->message ?? '');
        }
    }

    public function calcTime(Tour $tour)    {
        $json = file_get_contents('https://courier.yandex.ru/vrs/api/v1/result/mvrp/'.$tour->mvrp_id);
        $obj = json_decode($json);
        if (!empty($obj->result))   {
            $routePoints = $obj->result->routes[0]->route;
            $toTime = Carbon::createFromFormat('Y-m-d H:i:s', $tour->date_time_start);
            $toTime->startOfDay();
            for ($i = 0; $i < count($routePoints); $i++) {
                $order = Order::find((int)$routePoints[$i]->node->value->ref);
                if ($order && $order->count()) {
                    $newFinishTime = $toTime->copy()->addSeconds($routePoints[$i]->arrival_time_s);
                    if ($order->tour->route->flight_type == 'arrival') {
                        $order->update(['station_to_time' => $newFinishTime->format('H:i:s'), 'to_date_time' => $newFinishTime->format('Y-m-d H:i:s')]);
                        echo $order->transferAddress() . '. Прибытие: ' . $order->station_to_time . '<br><br>';
                    } else {
                        $order->update(['station_from_time' => $newFinishTime->format('H:i:s'), 'from_date_time' => $newFinishTime->format('Y-m-d H:i:s')]);
                        echo $order->transferAddress() . '. Отправление: ' . $order->station_from_time . '<br><br>';
                    }
                }
            }
            
        }
    }

    public function bbvAuth() {
        $auth = ServiceOnlineCheckBBV::auth();
        if (isset($auth->token)) {
            $shift = ServiceOnlineCheckBBV::openShift();
            if (isset($shift->open_datetime))  {
                return response()->json(['status' => 'success', 'message' => 'Смена успешно открыта!']);
            } else {
                return response()->json(['status' => 'error', 'message' => $shift->message]);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'Ошибка авторизации!']);
        }

    }

    public function bbvReceipt(Request $request) {
        $order = $this->getOrderActive($request->orderId);
        $receipt = ServiceOnlineCheckBBV::receipt($order);
        if (isset($receipt->document_number))  {
            $order->update(['bbv_receipt' => $receipt->unique_code]);
            return response()->json(['status' => 'success', 'message' => 'Пробит чек на сумму ' . $receipt->total_amount . 'р.']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Ошибка регистрации чека - '. $receipt->message]);
        }
    }

    public function bbvCalcCash(Request $request) {     // Возвращает на какую сумму чеков выбито на рейсе
        $info = ServiceOnlineCheckBBV::info();
        if (isset($info->cashbox->current_amount))  {
            return response()->json(['status' => 'success', 'total' => $info->cashbox->current_amount]);
        } else {
            return response()->json(['status' => 'error', 'message' => $info->message]);
        }
    }

    public function bbvClose(Request $request) {       // Сдать сумму $amount и закрыть кассу
        $amount = $request->withdraw;
        if ($amount !== '0.00')  {
            $withdraw = ServiceOnlineCheckBBV::withdraw($request->withdraw);
        };
        if (isset($withdraw->document_number) || $amount == '0.00')  {
            $close = ServiceOnlineCheckBBV::close();
            if (isset($close->document_number))  {
                session()->forget('bbv_token');
                return response()->json(['status' => 'success', 'message' => 'Смена успешно закрыта!']);
            } else {
                return response()->json(['status' => 'error', 'message' => $close->message ?? print_r($close, true)]);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => $withdraw->message ?? print_r($withdraw, true)]);
        }
    }

    protected function getOrderActive($id)
    {
        return Order::whereId($id)
            ->has('client')
            ->filter([
                'driver_id' => \Auth::guard('driver')->user()->id,
            ])
            ->active()
            ->first();
    }


    private function addServicesCash($tours)    {   // Подсчитываем количество доп.сервисов и кассу рейсов для отображения водителю
        foreach ($tours as $tour)   {
            $addServices = [];
            $tour->cash = 0;
            foreach ($tour->ordersReady as $order) {
                $tour->cash += $order->priceApp();
                foreach ($order->addServices as $item) {
                    $addServices[$item->name] = ($addServices[$item->name] ?? 0) + $item->pivot->quantity;
                }
                $tour->addServices = $addServices;
            }
        }
    }
    
}
