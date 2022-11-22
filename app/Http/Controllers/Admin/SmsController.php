<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\Order;
use App\Models\Role;
use App\Models\Route;
use App\Models\Station;
use App\Models\Street;
use App\Models\Route as RouteModel;
use App\Notifications\Client\CustomNotification;
use App\Services\Prettifier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\Order\ChangeOrderNotification;
use  GuzzleHttp\Client;
use Log;

class SmsController extends Controller
{
    public function individualPopup($orderId, Request $request)
    {
        return ['html' => view('admin.sms.popups.individual', compact('orderId'))->render()];
    }

    public function send(Request $request)
    {
        $order = Order::find($request->get('orderId'));
        $order->client->notify(new CustomNotification($order, $request->get('message')));
        return $this->responseSuccess();
    }

    public function index()
    {
        //$this->test();
        die;

        try {
            $body = json_encode([
                'client_message_id' => 236234857,
                'sender' => "AMIDTRAVEL",
                'recipient' => 77015165728,
                'message_text' => "тест",
                'priority' => 2
            ]);

            $client = new Client([
                'base_uri' => 'https://api.kcell.kz/app/smsgw/rest/v2/',
                'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
                'body' => $body,
                'auth' => ['amid', 'ami!@#']
            ]);

            $response = $client->request('POST', 'messages');
            echo $response->getBody();
            echo $response->getStatusCode();
        } catch (\Exception $e) {
            \Log::info('SMS_SEND: ' . $e->getCode() . '' . $e->getMessage());
            echo 'SMS_SEND: ' . $e->getCode() . '' . $e->getMessage();
        }
    }

    public function statistic()
    {
        /*$orders = Order::with('client')->whereHas('tour',function($query){
            $query->where('date_start', '>=','2019-05-01');
        })
            ->groupBy('client_id')
            ->select('client_id', \DB::raw('sum(count_places_appearance) as cnt_appearance'))
            ->orderBy('cnt_appearance', 'desc')
            ->get()
            ->take(15);
        $data = [];
        foreach ($orders as $order) {
            $data[] = [
                'client' => $order->client->fullName,
                'phone' => $order->client->phone,
                'cnt' => $order->cnt_appearance,
            ];
        }

        \Excel::create('статистика с мая', function ($excel) use ($data) {
            $excel->sheet('статистика', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->export('xlsx');*/
    }

    public function test()
    {
        //\DB::table('cities')->delete();
        //\DB::table('stations')->delete();
        //\DB::table('routes')->delete();
        //\DB::table('route_station')->delete();
        //\DB::table('route_user')->delete();
        //\DB::table('streets')->delete();
        //\DB::table('buses')->delete();
        //\DB::table('drivers')->delete();

        $data = \Excel::load(storage_path('app/file.xlsx'), function ($reader) {
            $sheets = $reader->get(); // get first sheet
            foreach ($sheets as $sheet) {
                $routeName = $sheet->getTitle();
                if (!in_array($routeName, ['Автобусы', 'Водители', 'Пользователи'])) {
                    $stations = [];
                    foreach ($sheet->toArray() as $item) {
                        if (isset($item['gorod']) && $item['gorod']) {
                            $city = $this->getCity($item['gorod']);
                            if (isset($item['ulitsa']) && $item['ulitsa']) {
                                $street = $this->getStreet($city->id, $item['ulitsa']);
                                if (isset($item['ostanovka']) && $item['ostanovka']) {
                                    $status = in_array($item['status_ostanovki'], ['Сбор', 'сбор']) ? Station::STATUS_COLLECT : Station::STATUS_ACTIVE;
                                    $station = $this->getStation($city->id, $street->id, $item['ostanovka'], $status);
                                    $stations[] = ['station' => $station, 'item' => $item];
                                }
                            }
                        } else break;
                    }
                    $route = $this->getRoute($stations, $routeName);
                    $routeStations = $this->prepareRouteData($stations);
                    $route->stations()->sync($routeStations);
                } elseif ($routeName == 'Автобусы') {
                    $this->busInsert($sheet->toArray());
                    continue;
                } elseif ($routeName == 'Водители') {
                    $this->driverInsert($sheet->toArray());
                    continue;
                } elseif ($routeName == 'Пользователи') {
                    $this->userInsert($sheet->toArray());
                    continue;
                }
            }
        });
        return 'success';
    }

    public function userInsert($data)
    {
        foreach ($data as $user) {
            if (!empty($user['imya'])) {
                $route_id = Route::all()->pluck('id')->toArray();

                $superAdminRole = Role::where('slug', $user['rol'])->first();
                $user->attachRole($superAdminRole);
                $user->routes()->sync($route_id);
            }
        }
    }



    public function prepareRouteData($stations)
    {
        $data = [];
        $order = 1;
        $interval = 0;

        foreach ($stations as $station) {
            $time = $this->getStationInterval($station) ? $this->getStationInterval($station) : 0;
            $interval += $time;
            $data[$station['station']->id] = ['order' => $order++, 'time' => $time, 'interval' => $interval];
        }
        return $data;
    }

    public function getCity($name)
    {
        $name = trim($name);
        if ($city = City::where('name', $name)->first())
            return $city;
        else return City::create([
            'name' => $name,
            'name_tr' => Prettifier::Transliterate($name),
            'status' => City::STATUS_ACTIVE
        ]);
    }

    public function getStreet($city_id, $name)
    {
        if ($street = Street::where('name', $name)->first())
            return $street;
        else return Street::create([
            'city_id' => $city_id,
            'name' => $name,
        ]);
    }

    public function getStation($city_id, $street_id, $name, $status)
    {
        if ($station = Station::where('name', $name)
            ->where('city_id', $city_id)
            ->where('street_id', $street_id)
            ->first())
            return $station;
        else return Station::create([
            'city_id' => $city_id,
            'street_id' => $street_id,
            'name' => $name,
            'name_tr' => Prettifier::Transliterate($name),
            'latitude' => '27.537097',
            'longitude' => '53.901717',
            'status' => $status

        ]);
    }

    public function getRoute($stations, $routeName)
    {
        if ($route = RouteModel::where('name', $routeName)->first())
            return $route;
        else {
            return RouteModel::create([
                'name' => $routeName,
                'name_tr' => Prettifier::Transliterate($routeName),
                'status' => RouteModel::STATUS_ACTIVE,
                'interval' => $this->getRouteInterval($stations),
                'is_international' => true,
            ]);
        }
    }

    public function getRouteInterval($stations)
    {
        $interval = 0;
        foreach ($stations as $station)
            $interval += $this->getStationInterval($station);
        return $interval;
    }

    public function getStationInterval($station)
    {
        if (isset($station['item'])) {
            $item = $station['item'];
            return isset($item['raznitsa']) ? $item['raznitsa'] : 0;
        }
    }

    public function sendActualOrder(Order $order)
    {
        $order = $order->where('id', request('id'))->first();
        $order->tour->is_edit = false;
        $order->client->notify(new ChangeOrderNotification($order, 'on'));
    }

    public function getCallbackInformationMTS(Request $request){
        \Log::info($request);
    }
}
