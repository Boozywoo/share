<?php

namespace App\Models;

use App\Services\Order\FragmentationOrder;
use App\Services\Order\StationIntervalsService;
use App\Services\Prettifier;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;


class Tour extends Model
{

    const STATUS_ACTIVE = 'active';
    const STATUS_DISABLE = 'disable';
    const STATUS_REPAIR = 'repair';
    const STATUS_DUPLICATE = 'duplicate';
    const STATUS_COMPLETED = 'completed';
    const STATUS_VIRTUAL = 'virtual';
    const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_DISABLE,
        self::STATUS_REPAIR,
        self::STATUS_DUPLICATE,
        self::STATUS_COMPLETED,
        self::STATUS_VIRTUAL,
    ];

    const TYPE_DUPLICATE_DRIVER = 'driver';
    const TYPE_DUPLICATE_BUS = 'bus';
    const TYPE_DUPLICATE_ALL = 'all';
    const TYPE_DUPLICATES = [
        self::TYPE_DUPLICATE_DRIVER,
        self::TYPE_DUPLICATE_BUS,
        self::TYPE_DUPLICATE_ALL
    ];

    const TYPE_DRIVER_NEW = 'new';
    const TYPE_DRIVER_COLLECTION = 'collection';
    const TYPE_DRIVER_COLLECTION_END = 'collection_end';
    const TYPE_DRIVER_WAY = 'way';
    const TYPE_DRIVER_COMPLETED = 'completed';
    const TYPE_DRIVERS = [
        self::TYPE_DRIVER_NEW,
        self::TYPE_DRIVER_COLLECTION,
        self::TYPE_DRIVER_COLLECTION_END,
        self::TYPE_DRIVER_WAY,
        self::TYPE_DRIVER_COMPLETED,
    ];
    protected $fillable = [
        'date_time_start', 'date_start', 'time_start', 'time_finish', 'date_finish', 'date_time_finish',
        'schedule_id', 'bus_id', 'route_id', 'driver_id', 'egis_file', 'egis_status', 'egis_answer',
        'status', 'price', 'type_driver', 'shift', 'comment', 'is_edit',
        'reservation_by_place', 'is_collect', 'is_show_front', 'is_rent', 'rent_id', 'type_duplicate', 'is_reserve',
        'integration_id', 'integration_uid', 'is_show_agent', 'is_individual', 'mvrp_id',
    ];
    protected $dates = ['date_start', 'date_finish'];

    //Relationships

    public static function AutoNoticeUpdate($lastValue, $newValue)
    {

        if ($lastValue != $newValue)
            self::where('date_start', '>=', Carbon::now()->format('Y-m-d'))
                ->update(['is_edit' => $newValue]);
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function rent()
    {
        return $this->belongsTo(Rent::class);
    }

    public function integration()
    {
        return $this->belongsTo(Integration::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class)->with('stations');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function ordersCount()
    {
        return $this->orders()->count();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function ordersPull()
    {
        return $this->orders()->where('pull', 1)->whereStatus(Order::STATUS_ACTIVE);
    }

    public function ordersPullReserve()
    {
        return $this->orders()->where('pull', 1)->whereIn('status', [Order::STATUS_ACTIVE, Order::STATUS_RESERVE]);
    }

    public function ordersReady()
    {
        return $this->ordersNoPull()->whereStatus(Order::STATUS_ACTIVE);
    }

    public function ordersActive()
    {
        return $this->orders()->whereStatus(Order::STATUS_ACTIVE);
    }

    public function ordersDisabled()
    {
        return $this->orders()->whereStatus(Order::STATUS_DISABLE)->where('pull', 0);
    }

    public function ordersCompleted()
    {
        return $this->orders()->where('is_finished', true);
    }

    public function ordersConfirm()
    {
        return $this->ordersNoPull()->whereStatus(Order::STATUS_ACTIVE)->where('confirm', true);
    }

    public function orderPlaces()
    {
        return $this->hasManyThrough(OrderPlace::class, Order::class)->whereStatus(Order::STATUS_ACTIVE)->wherePull(0);
    }

    public function orderPlacesFull()
    {
        return $this->hasManyThrough(OrderPlace::class, Order::class)->whereStatus(Order::STATUS_ACTIVE)->wherePull(0)->with('order', 'order.client');
    }

    public function ordersFreeCity($cityFrom = null, $cityTo = null)
    {
        $freePlaces = 0;

        if ($cityFrom && $cityTo) {
            $stationFromId = $this->route->stations->where('city_id', $cityFrom)->last()->id ?? 0;
            $stationToId = $this->route->stations->where('city_id', $cityTo)->first()->id ?? 0;
            $freePlaces = FragmentationOrder::searchFreePlaces($this, $stationFromId, $stationToId, 'count');
        } elseif ($cityFrom) {
            $stationFromId = $this->route->stations->where('city_id', $cityFrom)->last()->id ?? 0;
            $stationToId = $this->route->stations->last()->id;
            $freePlaces = FragmentationOrder::searchFreePlaces($this, $stationFromId, $stationToId, 'count');
        }

        if ($this->bus) {
            $free = $this->bus->places - $this->orderPlaces->groupBy('number')->count() + $freePlaces;
            return $free > 0 ? $free : 0;
        }

        return 0;
    }

    public function ordersFreeStations($stationFromId, $stationToId)
    {
        $freePlaces = FragmentationOrder::searchFreePlaces($this, $stationFromId, $stationToId, 'count');
        if ($this->bus) {
            $free = $this->bus->places - $this->orderPlaces->groupBy('number')->count() + $freePlaces;
            return $free > 0 ? $free : 0;
        }
        return 0;
    }

    public function freePlacesStations($excludeId, $stationFromId, $stationToId)
    {

        $timeStations = StationIntervalsService::index($this->route->id, $stationFromId, $stationToId);
        $freePlaces = FragmentationOrder::searchFreePlacesInterval($this, $timeStations, 'count', $excludeId);
        if ($this->bus) {
            $free = $this->bus->places - $this->orderPlaces->groupBy('number')->count() + $freePlaces;
            return $free > 0 ? $free : 0;
        }
        return 0;
    }

    public function freePlacesBetween($station_from_id, $station_to_id, $type = 'count')     // Возвращает кол-во свободных мест между остановками при включенном промежуточном бронировании
    {
        if ($this->bus) {
            $freePlaces = $this->bus->template->templatePlaces->where('type', 'number')->pluck('number')->toArray();
        } else {
            return 0;
        }

        $stations = $this->route->stationsFromTo($station_from_id, $station_to_id)->pluck('id')->toArray();
        $stations[] = $station_to_id;
        $orders = $this->ordersReady;
        foreach ($orders as $order) {
            $stationsOrder = $this->route->stationsFromTo($order->station_from_id, $order->station_to_id)->pluck('id')->toArray();  // Список остановок текущего заказа
            $stationsOrder[] = $order->station_to_id;
            $sameStations = array_intersect($stations, $stationsOrder);
            if (count($sameStations)) {
                $freePlaces = array_diff($freePlaces, $order->orderPlaces->pluck('number')->toArray());
            }
        }
        return $type == 'places' ? $freePlaces : count($freePlaces);
    }

    public function nearestActive($backward = false)        // Используется для виртуальных рейсов. Возвращает ближайший активный рейс, если backward = true, то ближайший рейс ранний по времени. 
    {
        if ($backward)  {
            $tour = Tour::where('route_id', $this->route_id)->where('status', self::STATUS_ACTIVE)
                ->where('date_time_start', '<=', $this->date_time_start)->orderBy('date_time_start', 'desc')->first();
        } else {
            $tour = Tour::where('route_id', $this->route_id)->where('status', self::STATUS_ACTIVE)
                ->where('date_time_start', '>=', $this->date_time_start)->orderBy('date_time_start')->first();
        }
        return $tour;
    }

    //Mutators

    public function ordersNoPull()
    {
        return $this->orders()->with('operator', 'createdUser', 'modifiedUser', 'canceledUser')->where('pull', 0);
    }

    public function setDateStartAttribute($date)
    {
        return $this->attributes['date_start'] = Carbon::createFromFormat('d.m.Y', $date);
    }

    public function setDateFinishAttribute($date)
    {
        return $this->attributes['date_finish'] = Carbon::createFromFormat('d.m.Y', $date);
    }

    public function getPrettyTimeStartAttribute()
    {
        return Prettifier::prettifyTime($this->time_start);
    }

    public function getPrettyTimeFinishAttribute()
    {
        if ($this->route)
            return Prettifier::prettifyTime($this->time_start, $this->route->interval);
        return substr($this->time_finish, 0, 5);
    }

    public function getPrettyTimeAttribute()
    {
        $result = $this->date_start->format('d.m.Y');
        $result .= ', ' . $this->prettyTimeStart . ' - ' . $this->prettyTimeFinish;
        return $result;
    }

    public function getPrettyDateStartAttribute()
    {
        return $this->date_start->format('Y-m-d');
    }

    public function getPrettyDateFinishAttribute()
    {
        return $this->date_finish->format('Y-m-d');
    }

    public function getPrettyDateTimeStartAttribute()
    {
        return Carbon::parse($this->prettyDateStart.' '.$this->prettyTimeStart);
    }

    public function getBusyPlacesCountAttribute()
    {
        if ($this->route && $this->route->is_route_taxi)    {
            return $this->ordersNoPull()->whereStatus(Order::STATUS_ACTIVE)->where('type', '!=',Order::TYPE_COMPLETED)->sum('count_places');
        } else {
            return $this->ordersReady->sum('count_places');
        }
    }

    public function getFreePlacesCountAttribute()
    {
        return $this->bus ? $this->bus->places - $this->busyPlacesCount : 0;
    }

    public function getDurationAttribute()
    {
        $result = '';
        $start = Carbon::createFromTimestamp(strtotime($this->date_time_start));
        $finish = Carbon::createFromTimestamp(strtotime($this->date_time_finish));
        if ($days = $start->diffInDays($finish)) $result .= $days . ' д ';
        if ($hours = ($start->diffInHours($finish) % 24)) $result .= $hours . ' ч ';
        if ($min = ($start->diffInMinutes($finish) % 60)) $result .= $min . ' мин ';
        return $result;
    }

    public function getChildPlacesCountAttribute()
    {
        return $this->orders()->whereHas('orderPlaces', function ($q) {
            $q->where('is_child', true);
        })->count();
    }

    public function getCurrencyAttribute()
    {
        return trans('admin_labels.currencies_short.' . $this->route->currency->alfa);
    }

    public function getStatisticPlacesAttribute()
    {
        $CountAll = 0;
        $message = '';
        $FullCost = 0;
        $amount = 0;
        $children = 0;
        //$sales    = array();
        $saleCount = 0;
        $statuses = array();
        $price = (float)$this->price;
        $orders = $this->orders()->with('orderPlaces')->get();
        foreach ($orders as $order) {
            foreach ($order->orderPlaces as $orderPlace) {
                $CountAll++;
                $orderPlacePrice = (float)$orderPlace->price;
                $amount += $orderPlacePrice;
                if ($orderPlacePrice == $price) $FullCost++;
                elseif ($orderPlace->status_id) {
                    if (isset($statuses[$orderPlace->socialStatus->name]))
                        $statuses[$orderPlace->socialStatus->name]++;
                    else $statuses[$orderPlace->socialStatus->name] = 1;
                } elseif ($orderPlace->is_child) $children++;
                elseif ($orderPlace->sales) $saleCount++;
            }
        }

        if ($CountAll) {
            $message = 'ВСЕГО:' . $CountAll . '/';
            if ($FullCost) $message .= 'Полная стоимость:' . $FullCost . '/';
            if ($children) $message .= 'Дети:' . $children . '/';
            if ($saleCount) $message .= 'Акции:' . $children . '/';
            foreach ($statuses as $key => $status) $message .= 'статус[' . $key . ']:' . $status . '/';
            /*foreach ($sales as $key => $sale) $message .= 'акция['.$key.']:'.$sale.'/';*/
            if ($message) $message = substr($message, 0, -1);
        }
        if ($CountAll) $amount = 'сумма:' . $amount;
        else $amount = '';
        return ['tickets' => $message, 'amount' => $amount];
    }

    public function getReservedAttribute()
    {
        $ords = Order::filter([
            'tour_id' => $this->id,
            'status' => Order::STATUS_ACTIVE,
        ])
        ->wherePull(0)
        ->pluck('id')
        ->toArray();

        return OrderPlace::whereIn('order_id', $ords)->get();
    }

    public function getInfoAttribute() 
    {
        return $this->route->name . ' ' . $this->prettyTimeStart . '-' . $this->prettyTimeFinish;
    }
    
    public function getFlightNumberAttribute() {        // Возвращает номер рейса самолета в формате BL-456 или 456
        return isset($this->schedule->flight_ac_code) ? $this->schedule->flight_ac_code . '-' . ($this->schedule->flight_number ?? '') : ($this->schedule->flight_number ?? '');
    }

    //Scopes

    public function scopeFilter($query, $data)
    {
        $status = array_get($data, 'status');
        $statuses = array_get($data, 'statuses');
        $busId = array_get($data, 'bus_id');
        $buses = array_get($data, 'buses');
        $routeId = array_get($data, 'route_id');
        $routes = array_get($data, 'routes');
        $city_from_id = array_get($data, 'city_from_id');
        $city_to_id = array_get($data, 'city_to_id');
        $cityRoutes = $this->getFromCityIdTo($city_from_id, $city_to_id, 'routes');
        $routes = $cityRoutes ? $cityRoutes : $routes;
        $scheduleId = array_get($data, 'schedule_id');
        $driverId = array_get($data, 'driver_id');
        $date = array_get($data, 'date');
        $dateTo = array_get($data, 'date_to');
        $pull = array_get($data, 'pull');
        $between = array_get($data, 'between');
        $betweenTime = array_get($data, 'between_time');
        $notId = array_get($data, 'not_id');
        $forOrder = array_get($data, 'for_order');
        $exclude_type_driver = array_get($data, 'exclude_type_driver');
        $visible = array_get($data, 'visible');
        $haveOrders = array_get($data, 'have_orders');
        $company = array_get($data, 'company');

        $query
            ->when($notId, function ($q) use ($notId) {
                return $q->where('id', '!=', $notId);
            })
            ->when($status, function ($q) use ($status) {
                return $q->where('status', $status);
            })
            ->when($statuses, function ($q) use ($statuses) {
                return $q->whereIn('status', $statuses);
            })
            ->when($busId, function ($q) use ($busId) {
                return $q->where('bus_id', $busId);
            })
            ->when($routeId, function ($q) use ($routeId) {
                return $q->where('route_id', $routeId);
            })
            ->when($routes, function ($q) use ($routes) {
                if ($routes instanceof Collection) $routes = $routes->toArray();
                if (!is_array($routes)) $routes = [$routes];
                return $q->WhereIn('route_id', $routes);
            })
            ->when($buses, function ($q) use ($buses) {
                if ($buses instanceof Collection) $buses = $buses->toArray();
                if (!is_array($buses)) $buses = [$buses];
                return $q->whereIn('bus_id', $buses);
            })
            ->when($scheduleId, function ($q) use ($scheduleId) {
                return $q->where('schedule_id', $scheduleId);
            })
            ->when($driverId, function ($q) use ($driverId) {
                return $q->where('driver_id', $driverId);
            })
            ->when($date, function ($q) use ($date) {
                return $q->where('date_start', $date->format('Y-m-d'));
            })
            ->when($dateTo, function ($q) use ($dateTo) {
                return $q->where('date_start', '>', $dateTo);
            })
            ->when($between, function ($q) use ($between) {
                return $q->whereBetween('date_start', [$between['dateFrom'], $between['dateTo']]);
            })
            ->when($betweenTime, function ($q) use ($betweenTime) {
                return $q->whereBetween('date_time_start', [$betweenTime['dateTimeFrom'], $betweenTime['dateTimeTo']]);
            })
            ->when($date, function ($q) use ($date, $forOrder) {
                if ($forOrder) {
                    $currentDatetime = Carbon::now()->format("Y-m-d");

                    if (env('TIME_ZONE')) {
                        if ($currentDatetime == $date->format("Y-m-d")) {
                            $q->where('time_start', '>', $date->format('H:i:s'));
                        }
                    } else {
                        if ($currentDatetime == $date->format("Y-m-d")) {
                            $q->where('time_start', '>', date('H:i:s'));
                        }
                    }
                }
            })
            ->when($pull, function ($q) {
                return $q->whereHas('orders', function ($q) {
                    $q->wherePull(1);
                });
            })
            ->when($exclude_type_driver, function ($q) use ($exclude_type_driver) {
                return $q->where('type_driver', '!=', $exclude_type_driver);
            })
            ->when($visible, function ($q) use ($visible) {
                if ($visible == 'yes') {
                    return $q->where(function ($q1) {
                        $q1->where('is_show_front', true)->orWhereNull('schedule_id');
                    });
                }
                if ($visible == 'no') {
                    return $q->where(function ($q1) {
                        $q1->where('is_show_front', false);
                    });
                }
            })
            ->when($haveOrders, function ($q) use ($haveOrders) {
                if ($haveOrders == 'yes') {
                    return $q->whereHas('orders', function ($q) {
                        $q->whereStatus(Order::STATUS_ACTIVE);
                    });
                }
                if ($haveOrders == 'unpaid') {
                    return $q->whereHas('orders', function ($q) {
                        $q->whereStatus(Order::STATUS_ACTIVE)
                            ->whereRaw('(type_pay = "' . Order::TYPE_PAY_WAIT . '" OR type_pay is NULL)');
                    });
                }
                if ($haveOrders == 'no') {
                    return $q->whereDoesntHave('orders', function ($q) {
                        $q->whereStatus(Order::STATUS_ACTIVE);
                    });
                }
            })
            ->when($company, function ($q) use ($company) {
                return $q->whereIn('driver_id', Company::find($company)->drivers()->pluck('id'));
            })
        ;
        return $query;
    }

    public function getFromCityIdTo($city_from_id, $city_to_id = '', $returnType = 'routes')
    {
        $routesId = array();
        $allCitiesId = array();
        $routes = Route::where('status', Route::STATUS_ACTIVE)->with('stations')->get();
        if ($city_from_id && $city_to_id) {
            foreach ($routes as $route) {
                $cityGo = false;
                foreach ($route->stations as $station) {
                    if ($station->city_id == $city_from_id)
                        $cityGo = true;
                    if ($cityGo && $city_to_id == $station->city_id)
                        if (!in_array($route->id, $routesId))
                            $routesId[] = $route->id;
                }
            }
            return ($returnType == 'routes') ? $routesId : $allCitiesId;
        } elseif ($city_from_id) {
            foreach ($routes as $route) {
                $cityGo = false;
                $citiesId = array();
                foreach ($route->stations as $station) {
                    if ($city_from_id == $station->city_id && $station->pivot->tickets_from) { // Если посадка запрещена на данной остановке (tickets_from = 0), то запрещены все остановки высадки на этом маршруте
                        $cityGo = true;                                                        // Если посадка разрешена на данной остановке (tickets_from = 1), то после этой остановки можно высаживаться на следующих (если высадка на них разрешены высадки tickets_to = 1)
                    }
                    if ($city_from_id != $station->city_id && $cityGo && $station->pivot->tickets_to) {
                        $citiesId[(int)$station->city_id] = $station->city_id;
                    }
                }
                if (!empty($citiesId)) {
                    $routesId[] = $route->id;
                    $allCitiesId = $allCitiesId + $citiesId;
                }
            }
            return ($returnType == 'routes') ? $routesId : $allCitiesId;
        } else return false;
    }

    public function scopeFuture($query)
    {
        return $query->has('ordersReady')
            ->where('date_start', '>=', Carbon::now()->format('Y-m-d'))
            ->whereStatus(Tour::STATUS_ACTIVE);
    }

    public static function getTourThisMoment($route_id){
        $now = Carbon::now();
        return Tour::where('route_id', $route_id)
            ->where('date_time_start', '<=', $now)
            ->where('date_time_finish', '>=', $now)
            ->pluck('bus_id')->all();
    }

    public static function getBusTourAtThisMoment($bus_id){
        $now = Carbon::now();
        return Tour::where('bus_id', $bus_id)
            ->where('date_time_start', '<=', $now)
            ->where('date_time_finish', '>=', $now)
            ->pluck('route_id')->first();
    }
}
