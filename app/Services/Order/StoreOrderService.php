<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Models\Station;
use App\Models\Setting;
use App\Models\Tour;
use App\Services\Client\StoreClientService;
use App\Services\Log\TelegramLog;
use App\Services\Order\Integrations\IntegrationBookingOrderService;
use App\Services\Order\Integrations\IntegrationConfirmOrderService;
use App\Services\Order\Integrations\IntegrationUpdateOrderService;
use App\Services\Prettifier;
use App\Services\Sale\SaleToOrderService;
use App\Services\Social\SocialToOrderService;
use App\Services\Support\HandlerError;
use App\Validators\Order\StoreOrderValidator;
use App\Services\Geo\GeoService;
use DB;
use DateTime;
use App\Models\OrderHistory;
use Carbon\Carbon;

class StoreOrderService
{
    /*
     * Функция получения времени поездки
     *
     */
    public static function index($data, $tour)
    {
        try {
            DB::beginTransaction();

            $places = array_get($data, 'places', []);
            
            $id = array_get($data, 'id');

            $data['source'] = isset($data['source']) ? $data['source'] : Order::SOURCE_OPERATOR;
            if ($tour->route->is_route_taxi) {
                $taxiInterval = \DB::table('route_station_price')
                    ->where('route_id', $tour->route_id)
                    ->where('station_from_id', $data['station_from_id'])
                    ->where('station_to_id', $data['station_to_id'])
                    ->pluck('interval')->first() ?? 0;
                $taxiInterval += $data['delay'] ?? 0;
                $data['station_from_time'] = Carbon::now()->addMinutes($data['delay'] ?? 0)->format('H:i');
                $data['from_date_time'] = Carbon::now()->addMinutes($data['delay'] ?? 0)->format('Y-m-d H:i:s');
                $data['station_to_time'] = Carbon::now()->addMinutes($taxiInterval)->format('H:i');
                $data['to_date_time'] = Carbon::now()->addMinutes($taxiInterval)->format('Y-m-d H:i:s');
            } else {
                $data['station_from_time'] = StationIntervalsService::getDepartureDateTimeFromStation($tour, $data['station_from_id']);
                $data['from_date_time'] = StationIntervalsService::getDepartureDateTimeFromStation($tour, $data['station_from_id'], 'Y-m-d H:i:s');
                $data['station_to_time'] = StationIntervalsService::getDepartureDateTimeFromStation($tour, $data['station_to_id']);
                $data['to_date_time'] = StationIntervalsService::getDepartureDateTimeFromStation($tour, $data['station_to_id'], 'Y-m-d H:i:s');
            }

            if (isset($data['client_id']) && !empty($data['client_id']) && $data['source'] == Order::SOURCE_OPERATOR) {
                $data['client_id'] = StoreClientService::index($data);
            } elseif ($data['source'] == Order::SOURCE_CLIENT_APP) ;
            elseif ($data['source'] == Order::SOURCE_DRIVER) ;
            else {
                if (isset(auth()->user()->client_id)) {
                    $data['client_id'] = auth()->user()->client_id;
                } else {
                    $data['client_id'] = StoreClientService::index($data);
                }
            }

            $data['slug'] = $data['client_id'];

            //баг каждая Х поездка, обновление в конце транзакции
            if (isset($data['type']) && $data['type']) {
                $orderType = $data['type'];
                unset($data['type']);
            }

            $is_order_new = false;

            if ($id) {
                if ($order = Order::find($id)) {
                    if ($order->type_pay == 'success' && (count($data['places']) > $order->count_places)) {   // Нельзя добавлять новых людей в уже оплаченную бронь
                        DB::rollBack();
                        $order = $order->fresh();
                        return [$order, trans('messages.admin.order.adding_disabled')];
                    }

                    $orderHistory = new OrderHistory();
                    $orderHistory->order_id = $order->id;
                    $orderHistory->action = OrderHistory::ACTIVE_UPDATE;
                    if ($order->source == Order::SOURCE_OPERATOR) {
                        $data['modified_user_id'] = \Auth::id();
                        $data['operator_id'] = \Auth::id();
                        $orderHistory->operator_id = \Auth::id();

                        $orderHistory->source = Order::SOURCE_OPERATOR;
                    } else if ($order->source == Order::SOURCE_CLIENT_APP) {

                        $orderHistory->source = Order::SOURCE_CLIENT_APP;
                        if(auth()->user()->client_id>0)
                            $orderHistory->client_id = auth()->user()->client_id;
                        else if($order->client_id>0)
                            $orderHistory->client_id = $order->client_id;


                    } else if ($order->source == Order::SOURCE_DRIVER) {
                        $orderHistory->source = Order::SOURCE_DRIVER;

                    } else if ($order->source == Order::SOURCE_SYSTEM) {
                        $orderHistory->source = Order::SOURCE_SYSTEM;
                        $data['modified_user_id'] = \Auth::id();
                        $data['operator_id'] = \Auth::id();
                        $orderHistory->operator_id = \Auth::id();
                    } else {
                        $orderHistory->source = Order::SOURCE_SITE;
                        if(isset(auth()->user()->client_id))
                            $orderHistory->client_id = auth()->user()->client_id;
                        else
                            $orderHistory->client_id = 0;
                    }

                    $orderHistory->save();

                    $order->update($data);
                }
            } else {
                $is_order_new = true;
                $order = Order::create($data);
                $orderHistory = new OrderHistory();
                $orderHistory->order_id = $order->id;
                $orderHistory->action = OrderHistory::ACTIVE_CREATE;

                if ($data['source'] == Order::SOURCE_OPERATOR) {
                    $order->created_user_id = \Auth::id();
                    $order->operator_id = \Auth::id();

                    $orderHistory->operator_id = \Auth::id();
                    $orderHistory->source = Order::SOURCE_OPERATOR;
                } else if ($order->source == Order::SOURCE_CLIENT_APP) {
                    $orderHistory->source = Order::SOURCE_CLIENT_APP;


                   /* if(auth()->user()->client_id>0)
                        $orderHistory->client_id = auth()->user()->client_id;*/
                     if($order->client_id>0)
                        $orderHistory->client_id = $order->client_id;

                } else if ($order->source == Order::SOURCE_DRIVER) {
                    $orderHistory->source = Order::SOURCE_DRIVER;

                } else if ($order->source == Order::SOURCE_SYSTEM) {
                    $orderHistory->source = Order::SOURCE_SYSTEM;
                    $order->created_user_id = \Auth::id();
                    $order->operator_id = \Auth::id();

                    $orderHistory->operator_id = \Auth::id();
                } else {

                    $orderHistory->source = Order::SOURCE_SITE;
                    if(isset(auth()->user()->client_id))
                        $orderHistory->client_id = auth()->user()->client_id;
                    else
                        $orderHistory->client_id = 0;
                }
                $orderHistory->save();
            }

            if (($order->source=='operator' && $order->operator ? $order->operator->hasRole('agent') : false)
                && Setting::all()->first()->is_pay_on && $order->type_pay !== 'success') {
                $order->type_pay = Order::TYPE_PAY_WAIT;
            }

            $addServices = [];
            foreach ($data['add_services'] ?? [] as $key => $item)    {
                if ($item)  {
                    $addServices[$key] = ['quantity' => $item];
                }
            }
            $order->addServices()->sync($addServices);           // Сохраняем доп. сервисы

            $order->orderPlaces()->delete();
            $error = StoreOrderValidator::tour($tour, StoreOrderValidator::TYPE_OPERATOR);

            if (!$error) {
                $error = StoreOrderValidator::limitDayRoute($order);
            }

            if (!$error) {
                $error = StoreOrderValidator::places_new($tour, $order, $places, $data['station_from_id'], $data['station_to_id']); //разбиение рейса
            }
            //if (!$error) $error = StoreOrderValidator::places($tour, $order, $places);

            if ($error) {
                DB::rollBack();
                $order = $order->fresh();
                return [$order, $error];
            }

            //Different cost from stations
            $pricePlace = self::getPlacePrice($tour, $order, $data['station_from_id'], $data['station_to_id']);

            if($order->source == 'operator' && \Auth::user()->isMediator) {
                $userRoutes =  \Auth::user()->routes->keyBy('id');

                $pricePlace += (isset($userRoutes[$tour->route->id]) ? $userRoutes[$tour->route->id]->pivot->added_price : 0);
            }
            
            list($order, $oldPlaces, $error) = SaleToOrderService::index($order, $tour, $places, $pricePlace);

            if ($error) {
                DB::rollBack();
                $order = $order->fresh();
                return [$order, $error];
            }

            if (($order->source == Order::SOURCE_DRIVER || $order->source == Order::SOURCE_SYSTEM) && !empty($data['price'])){
                $order->price = $data['price'];
            }

            $order = SocialToOrderService::index($order, $tour, $order->price);

            $oldPlaces['count_places'] = $order->count_places;
            $oldPlaces['price'] = $order->price;
            if ($order->type === Order::TYPE_WAITING) {
                $order->old_places = $oldPlaces;
            }

            if (isset($orderType)) {
                $order->type = $orderType; // баг каждая поездка
            }

            $was_pushed = 0;
            if(Setting::all()->first()->is_send_to_email && $order->confirm == 1 && $order->type != 'no_completed') {
                $mail["name"] = (empty($order->client->first_name) ? 'Не введено имя' : $order->client->first_name) . " " . (empty($order->client->middle_name) ? ' ' : $order->client->middle_name)
                . " " . (empty($order->client->last_name) ? ' ' : $order->client->last_name);
                $mail["phone"] = empty($order->client->phone) ? 'Не введен телефон' : $order->client->phone;
                $mail["tour"] = $order->tour->route->name;
                $mail["from"] = $order->stationFrom->name . ", " . $order->stationFrom->city->name;
                $mail["to"] = $order->stationTo->name . ", " . $order->stationTo->city->name;
                $mail["date"] = $order->tour->date_start->format('d.m.Y');
                $mail["time"] = $order->tour->time_start;
                $mail["subject"] = "Новая бронь!";
                $mail["main_email"] = Setting::all()->first()->main_email;
                $mail["count"] = $order->count_places;
                $mail["price"] = $order->price;
                $mail["currency"] = ($order->tour->route->currency) ? $order->tour->route->currency->alfa : 'BYN';
                $one = new DateTime(date("Y-m-d H:i:s"));
                $two = new DateTime($order->created_at);
                $interval = $one->diff($two)->format('%i');
                if ($was_pushed == 1) return;
                if($order->status == 'active' && $interval <= 1) {
                    \Mail::send('admin.mail.mail', ['data' => $mail], function ($message) use ($mail) {
                        $message->to($mail["main_email"])
                            ->subject($mail["subject"]);
                    });
                    $was_pushed = 1; 
                }  
            }

            if ($tour->route->is_transfer && $order->transferAddress())  {
                list($order->longitude, $order->latitude) = GeoService::getCoordinates($order->transferAddress(true));
            }

            if (!empty($tour->egis_status)) {
                $tour->update(['egis_status' => null, 'egis_answer' => '']);
            }
            $order->save();
            DB::commit();
            return [$order, $error];
        } catch (\Exception $e) {
            DB::rollBack();
            //if ($i == 2) {
            HandlerError::index($e);
            return [$data, 'При обработке данных произошла ошибка, свяжитесь с диспетчером. '.$e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile()];
            //}
        }
        // }
    }


    public static function getPlacePrice(Tour $tour, Order $order, int $stationFromId, int $stationToId): float
    {
        $pricePlace = StationCostIntervalService::index($tour, $stationFromId, $stationToId);

        if ($order->source === ORDER::SOURCE_SITE) {
            $pricePlace = $tour->route->discount_front_type ?
                $pricePlace - ($pricePlace * $tour->route->discount_front / 100) :
                $pricePlace - $tour->route->discount_front;
        }

        if ($order->source === Order::SOURCE_CLIENT_APP) {
            $pricePlace = $tour->route->discount_mobile_type ?
                $pricePlace - ($pricePlace * $tour->route->discount_mobile / 100) :
                $pricePlace - $tour->route->discount_mobile;
        }

        return $pricePlace;
    }
}
