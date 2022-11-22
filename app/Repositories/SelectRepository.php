<?php

namespace App\Repositories;

use App\Models\Bus;
use App\Models\BusType;
use App\Models\City;
use App\Models\Company;
use App\Models\Department;
use App\Models\DiagnosticCardTemplate;
use App\Models\Driver;
use App\Models\Position;
use App\Models\ReviewActTemplate;
use App\Models\Role;
use App\Models\Route;
use App\Models\Setting;
use App\Models\Station;
use App\Models\Status;
use App\Models\Street;
use App\Models\Tariff;
use App\Models\Template;
use App\Models\Tour;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class SelectRepository
{
    public static function streetsWithStation($tour, $routeId = null, $city_id = null)
    {
        $select = [];
        $route = Route::find($routeId);
        $stations = $route->stations->load(['city', 'street']);

        foreach ($stations as $station) {
            if (!$tour->is_collect && $station->status == Station::STATUS_COLLECT) continue;
            if ($station->pivot->route_id == $routeId && $station->city_id == $city_id)
                $select[$station->street->name][$station->id] = $station->name;
        }

        return $select;
    }

    public static function orderByStation($orders, $tour)
    {
        $stations = $tour->route->stations;
        foreach ($stations as $station)

            foreach ($orders as $key => $order)
                if ($station->id == $order->station_from_id)
                    $orders[$key]->pivot_station_order = $station->pivot->order;

        return $orders->sortBy('pivot_station_order');
    }

    public static function getInfoOrder($order)
    {
        $orderHistory = $order->history->take(10);
        $settings = Cache::remember('settings', 1, function() {
            return Setting::first();
        });


        $tz = $settings->default_timezone;
        $order_info = '';
        $fio = '';
        if(ini_get('date.timezone')!=''){
            $def_timezone = ini_get('date.timezone');
        }else{
            $def_timezone = 'Europe/Moscow';
        }
        if (!$orderHistory->isEmpty()) {
            foreach ($orderHistory as $val) {

                $fio = (empty($val->client_id) || empty($order->client)) ? 'Незарегистрированный' : $order->client->FIO();

                $order_info .= '<br>';

                $dt  = Carbon::createFromFormat('Y-m-d H:i:s', $val->created_at, $def_timezone);
                $dt->setTimezone($tz);

                if ($val->source == 'operator' && !$val->operator_id && !$val->driver_id)   {
                    $val->source = 'site';
                }

                if ($val->source == 'site') {

                    switch ($val->action) {

                        case'create':
                            $order_info .= "Забронировано с сайта клиентом: <b>" . $fio . "</b><br> ";
                            $order_info .= 'Время: ' . $dt . "<br>";
                            break;
                        case'update':
                            $order_info .= "Обновлено с сайта клиентом: <b>" . $fio . "</b><br>";
                            $order_info .= 'Время: ' . $dt . "<br>";
                            $order_info .= $val->comment ? $val->comment.'<br>' : '';
                            break;
                        case'cancel':
                            $order_info .= "Отменено с сайта клиентом: <b>" . $fio . "</b><br>";
                            $order_info .= 'Время: ' . $dt . "<br>";
                            break;
                    }

                } else if ($val->source == 'operator') {

                    switch ($val->action) {
                        case'create':
                            $order_info .= "Забронировано оператором: " . $val->operator->first_name . " <br> ";
                            $order_info .= 'Время: ' .$dt . " <br> ";
                            break;
                        case'update':
                            $order_info .= "Обновлено оператором: " . $val->operator->first_name . " <br> ";
                            $order_info .= 'Время: ' . $dt. " <br> ";
                            $order_info .= $val->comment ? $val->comment.' <br> ' : '';
                            break;
                        case'cancel':
                            $order_info .= "Отменено оператором: " . $val->operator->first_name . " <br> ";
                            $order_info .= 'Время: ' . $dt . " <br> ";
                            break;
                        case 'recover':
                            $order_info .= "Восстановлено оператором: " . $val->operator->first_name . " <br> ";
                            $order_info .= 'Время: ' . $dt . " <br> ";
                            break;
                    }
                } else if ($val->source == 'driver') {

                    switch ($val->action) {
                        case'create':
                            $order_info .= "Забронировано водителем: ";
                            $order_info .= 'Время: ' .$dt . " <br> ";
                            break;
                        case'update':
                            $order_info .= "Обновлено водителем: ";
                            $order_info .= 'Время: ' . $dt . " <br> ";
                            break;
                        case'cancel':
                            $order_info .= "Отменено водителем: ";
                            $order_info .= 'Время: ' . $dt . " <br> ";
                            break;
                    }
                } else if ($val->source == 'system') {

                    switch ($val->action) {
                        case'create':
                            $order_info .= "Загружено оператором: " . ($val->operator ? $val->operator->first_name : 'Неизвестно')  . " <br> ";
                            $order_info .= 'Время: ' .$dt . " <br> ";
                            break;
                        case'update':
                            $order_info .= "Обновлено оператором: " . ($val->operator ? $val->operator->first_name : 'Неизвестно') . " <br> ";
                            $order_info .= 'Время: ' . $dt . " <br> ";
                            break;
                        case'cancel':
                            $order_info .= "Отменено оператором: " . ($val->operator ? $val->operator->first_name : 'Неизвестно') . " <br> ";
                            $order_info .= 'Время: ' . $dt . " <br> ";
                            break;
                    }
                } else if ($val->source == 'application') {

                    switch ($val->action) {
                        case'create':
                            $order_info .= "Забронировано через приложение клиентом: " . $fio . " <br> ";
                            $order_info .= 'Время: ' . $dt . " <br> ";
                            break;
                        case'update':
                            $order_info .= "Обновлено через приложение клиентом: " . $fio . " <br> ";
                            $order_info .= 'Время: ' . $dt . " <br> ";
                            break;
                        case'cancel':
                            $order_info .= "Отменено через приложение клиентом: " . $fio . " <br> ";
                            $order_info .= 'Время: ' . $dt . " <br> ";
                            break;
                    }
                } else if ($val->source == 'client_app') {

                    switch ($val->action) {
                        case'create':
                            $order_info .= "Забронировано через приложение клиентом: " . $fio . " <br> ";
                            $order_info .= 'Время: ' . $dt . " <br> ";
                            break;
                        case'update':
                            $order_info .= "Обновлено через приложение клиентом: " . $fio . " <br> ";
                            $order_info .= 'Время: ' . $dt . " <br> ";
                            break;
                        case'cancel':
                            $order_info .= "Отменено через приложение клиентом: " . $fio . " <br> ";
                            $order_info .= 'Время: ' . $dt. " <br> ";
                            break;
                    }
                }
                else if ($val->source == 'cron') {

                    switch ($val->action) {
                        case'create':
                            $order_info .= "Забронировано по CRON: " . $fio . " <br> ";
                            $order_info .= 'Время: ' . $dt . " <br> ";
                            break;
                        case'update':
                            $order_info .= "Обновлено по CRON: " . $fio . " <br> ";
                            $order_info .= 'Время: ' . $dt . " <br> ";
                            break;
                        case'cancel':
                            $order_info .= "Отменен по CRON: " . $fio . " <br> ";
                            $order_info .= 'Время: ' . $dt. " <br> ";
                            break;
                    }
                }
                else if ($val->source == 'cron_no_pay' && $val->action == 'cancel') {
                    $order_info .= "Отменен по CRON из-за неоплаты <br> ";
                    $order_info .= 'Время: ' . $dt. " <br> ";
                }


            }
        } else {


            $date = Carbon::createFromFormat('Y-m-d H:i:s', $order->created_at->format('Y-m-d H:i:s'), $def_timezone);
            $date->setTimezone($tz);


            $order_info = isset($order->operator->first_name) ?
                "Забронировал: " . $order->operator->first_name . ' ' . $order->operator->last_name . " <br> " :
                "Забронировано с сайта &#013; ";

            $order_info .= 'Время: ' . $date->format('Y-m-d H:i:s') . " <br> ";
            if ($order->modifiedUser) {
                $date = Carbon::createFromFormat('Y-m-d H:i:s', $order->updated_at->format('Y-m-d H:i:s'));
                $date->setTimezone($tz);

                $order_info .= "Изменил: " . $order->modifiedUser->fullName . "<br>";
                $order_info .= 'Время: ' . $date->format('Y-m-d H:i:s') . " <br> ";
            }
            if ($order->canceledUser) {
                $order_info .= "Отменил: " . $order->canceledUser->fullName . "<br>";
            }
        }


        return $order_info;
    }

    public static function getSMSOrder($order)
    {
        $order_info = '';
        if ($order->smsLog->count()) {
            foreach ($order->smsLog->groupBy('status') as $key => $item) {
                $order_info .= trans('admin.sms_logs.statuses.' . $key) . ': ' . $item->count() . ' <br>';
            }
        }

        return $order_info;
    }

    public function roles()
    {
        $user = auth()->user();
        return Role::whereIn('company_id', [0, $user->company_id])->get()->pluck('name', 'id');
    }

    public function rolesCompanies($company_id)
    {
        return Role::where('company_id',$company_id)->get()->pluck('name', 'id');
    }

    public function operators()
    {
        $operators = User::whereHas('roles', function ($q) {
            $q->where('slug', ['operator']);
        })->get();
        return $operators->pluck('name', 'id');
    }

    public function users()
    {
        return User::whereNull('client_id')->get()->pluck('name', 'id');
    }

    public function methodists($companyId)
    {
        return User::whereNull('client_id')->whereHas('roles', function ($q) {
            $q->where('slug', 'methodist');
        })->where('company_id', $companyId)->get()->pluck('name', 'id');
    }

    public function companies($userId = null)
    {
        $companies = Company::query()->filter(['status' => Company::STATUS_ACTIVE]);
        if ($userId) $companies->filter(['user_id' => $userId]);
        $companies = $companies->pluck('name', 'id');
        return $companies;
    }

    public function departments($company)
    {
        $departments = Department::where('company_id', $company)->get();
        $departments = $departments->pluck('name', 'id');
        return $departments;
    }

    public function busesInDepartment($department_id)
    {
        $buses = Department::find($department_id)->load('buses')->buses;
        $buses = $buses->pluck('name', 'id');
        return $buses;
    }

    /*
    Список должностей компании
    */
    public function positions($company_id)
    {
        $positions = Position::where('company_id', $company_id)
            ->orderBy('name')->get()->pluck('name', 'id');
        return $positions;
    }

    /*
    Список возможных руководителей пользователя с id=$user_id
    из компании $company_id
    */
    public function superiors($user_id, $company_id)
    {
        $superiors = User::where('company_id', $company_id)
            ->where('id', '<>', $user_id)
            ->orderBy('first_name')->get();
        $superiors = $superiors->pluck('first_name', 'id');
        return $superiors;
    }

    public function companyCarriers()
    {
        $companies = Company::query()->filter(['status' => Company::STATUS_ACTIVE, 'is_carrier' => true]);
        $companies = $companies->pluck('name', 'id');
        return $companies;
    }

    public function companyCustomers()
    {
        $companies = Company::query()->filter(['status' => Company::STATUS_ACTIVE, 'is_customer' => true]);
        $companies = $companies->pluck('name', 'id');
        return $companies;
    }

    public function tariffs()
    {
        $tariffs = Tariff::query()->filter(['status' => Company::STATUS_ACTIVE]);
        return $tariffs->pluck('name', 'id');
    }

    public function buses($companies = null, $bus_statuses = [Bus::STATUS_ACTIVE, Bus::STATUS_SYSTEM], $is_rent = false)
    {
        $result = collect(['' => trans('admin.clients.sel_bus')]);
        $buses = Bus::query()->filter(['status' => $bus_statuses]);

        if ($companies) {
            $buses->filter(['companies' => $companies]);
        }

        $buses = $buses->orderBy('id')->get();
        if ($is_rent) {
            $buses = $buses->where('is_rent', true);
        }

        foreach ($buses as $bus) {
            $result->put($bus->id, $bus->number . ' ' . $bus->name . ' ' . $bus->places);
        }

        return $result;
    }

    public function getTimes($busId, $routeId)
    {
        $offset = 1000 * 60 * 30;
        $result = collect(['' => trans('admin.monitoring.sel_time')]);

        $tours = Tour::query()->filter(['bus_id' => $busId, 'route_id' => $routeId])->orderBy('time_start')->get();
        foreach ($tours as $tour) {
            $start = strtotime($tour->time_start);
            $finish = strtotime($tour->time_finish);
            if (time() >= $start - $offset && time() <= $finish + $offset) {
                $result->put($tour->id, $tour->time_start);
            }
        }

        return $result;
    }

    public function getBuses()
    {
        $result = collect(['' => trans('admin.clients.sel_bus')]);

        $buses = Bus::query()->filter(['status' => Bus::STATUS_ACTIVE])->orderBy('number')->get();
        foreach ($buses as $bus) {
            $result->put($bus->id, $bus->number . ' ' . $bus->name . ' ' . $bus->places);
        }

        return $result;
    }

    public function getRoutes($bus_id)
    {
        if ($bus_id != "") {
            $now = Carbon::now();

            $routeIds = Tour::query()
                ->where(['bus_id' => $bus_id])
                ->where('date_time_start', '<=', $now)
                ->where('date_time_finish', '>=', $now)
                ->pluck('route_id')->toArray();

            $routes = Route::where('status', Route::STATUS_ACTIVE)
                ->whereIn('id', $routeIds);
        }else{
            $routes = Route::query()->filter(['status' => [Route::STATUS_ACTIVE]]);
        }

        return $routes->pluck('name', 'id');
    }

    public function busStatuses($status)
    {
        $statuses = trans('admin.buses.statuses');
        unset($statuses[Bus::STATUS_REPAIR]);
        if ($status != Bus::STATUS_OF_REPAIR) {
            unset($statuses[Bus::STATUS_OF_REPAIR]);
        }
        return $statuses;
    }

    public function busTypes()
    {
        $busTypes = BusType::select('id', 'name')->get()->keyBy('id');
        $busTypes->transform(function ($item, $key) {
            return $item['name'];
        });
        return $busTypes->toArray();
    }

    public function routes($userId = null, $active = true, $inactive_front = false, $bus_id = null)
    {
        $routes = Route::query();
        if ($userId) $routes->filter(['user_id' => $userId]);
        if ($active && !$inactive_front) $routes->filter(['status' => [Route::STATUS_ACTIVE]]);
        if ($active && $inactive_front) $routes->filter(['status' => [Route::STATUS_ACTIVE, Route::STATUS_INACTIVE_FRONT]]);
        if ($bus_id) {
            $routeIds = Tour::query()->where(['bus_id' => $bus_id])->pluck('route_id')->toArray();
            $routes->whereIn('id', $routeIds);
        }
        return $routes->pluck('name', 'id');
    }

    public function drivers($companies = null, $lang_key_for_title = null)
    {
        $drivers = Driver::whereIn('status', [Driver::STATUS_SYSTEM, Driver::STATUS_ACTIVE])
            ->select(\DB::raw('id, CONCAT(IFNULL(last_name, ""), " ", IFNULL(full_name, ""), " ", middle_name) as full_name'))->orderBy('full_name');
        if ($companies) $drivers->filter(['companies' => $companies])->orderBy('id');
        $title = !empty($lang_key_for_title) ? $lang_key_for_title : 'admin.clients.sel_driver';
        return $drivers->pluck('full_name', 'id')->prepend(trans($title), '');
    }

    public function mechanics($companies = null, $lang_key_for_title = null)
    {
        $drivers = Driver::whereIn('status', [Driver::STATUS_SYSTEM, Driver::STATUS_ACTIVE])
            ->select(\DB::raw('id, CONCAT(IFNULL(last_name, ""), " ", IFNULL(full_name, ""), " ", middle_name) as full_name'));
        if ($companies) $drivers->filter(['companies' => $companies])->orderBy('id');
        $title = !empty($lang_key_for_title) ? $lang_key_for_title : 'admin_labels.select_mechanic';
        return $drivers->pluck('full_name', 'id')->prepend(trans($title), '');
    }

    public function driverFineTypes()
    {
        return trans('admin.drivers.type_fines');
    }

    public function cities($isAll = false)
    {
        $tour = new Tour();
        $AllCities = City::query()
            ->filter(['status' => Driver::STATUS_ACTIVE])
            ->orderBy('name')
            ->pluck('name', 'id');

        if ($isAll) return $AllCities;

        $cities = array();
        foreach ($AllCities as $key => $city)
            if ($tour->getFromCityIdTo($key, '', 'cities')) $cities[$key] = $city;
        return collect($cities);
    }

    public function citiesRent()
    {
        $cities = City::query()
            ->filter(['status' => Driver::STATUS_ACTIVE, 'is_rent' => true])
            ->orderBy('name')
            ->pluck('name', 'id');
        return $cities;
    }

    public function streets($data)
    {
        return Street::query()
            ->filter(['city_id' => $data['city_id']])
            ->orderBy('name')
            ->pluck('name', 'id');
    }

    public function templates($countPlaces = null)
    {
        $templates = Template::query();
        if ($countPlaces) $templates->where('count_places', $countPlaces);
        return $templates->pluck('name', 'id')->prepend(trans('admin.buses.sel_temp'), '');
    }

    public function cityWithStation($routeId = null, $stationFromId = null, $type = null, $CityFromId = null, $isCollect = true, $isShort = true)
    {
        if ($routeId) {
            $select = [];
            $types = ['collect' => '*', 'active' => ''];

            $route = Route::find($routeId);
            $stations = $route->stations->load(['city', 'street']);
            if ($type) $stations = $stations->where('status', $type);

            $stationGo = $stationFromId ? false : true;
            foreach ($stations as $station) {
                if ($stationGo && $station->pivot->route_id == $routeId) {
                    /*if (isset($stationFromId) && (isset($stationCityGo) && $stationCityGo == $station->city_id)) {
                        if (!$route->is_route_taxi) {
                            continue; // убираем остановки в городе, где посадка
                        }
                    }*/
                    if ($stationFromId == $station->id) {
                        continue;
                    }
                    $select[$station->city->name][$station->id] = $types[$station->status] .
                        $station->name . (empty($station->street->name) ? '' : " (" . $station->street->name . ")");
                }

                if (!$stationGo && isset($stationFromId) && $stationFromId == $station->id) {
                    $stationGo = true;
                    $stationCityGo = $station->city_id;
                }

                if (!$stationGo && isset($CityFromId) && $CityFromId == $station->city_id) {
                    $stationGo = true;
                    $stationCityGo = $station->city_id;
                }
            }
            return $select;
        }

        $models = City::query();
        $select = [];
        $statuses = [Station::STATUS_ACTIVE];
        if ($isCollect) $statuses[] = Station::STATUS_COLLECT;

        $models = $models
            ->has('stations')
            ->with(['stations' => function ($q) use ($statuses) {
                $q->whereIn('status', $statuses);
            }])->get();

        foreach ($models as $model) {
            $stations = $model->stations->pluck('name', 'id')->toArray();
            if ($isShort) $select[$model->name] = $stations;
            else $select[$model->name] = preg_filter('/^/', '[' . $model->name . ']   ', $stations);
        }
        return $select;
    }

    public function stations($routeId = null, $stationFromId = null, $all = false, $type = NULL)
    {
        $route = Route::find($routeId);
        $select = [];

        $stations = $route->stations->load('city');
        $cityFirstId = $stations->first()->city_id;
        $cityLastId = $stations->last()->city_id;

        $stationGo = $stationFromId ? false : true;
        foreach ($stations as $station) {
            if ($stationGo) {
                if ((!$stationFromId && $cityLastId != $station->city_id) || ($stationFromId && $cityFirstId != $station->city_id) || $all)
                    $select[$station->id] = $station->city->name . ' ' . $station->name;
            }
            if (!$stationGo && $stationFromId == $station->id) $stationGo = true;
        }

        return $select;
    }

    public function stationsActive($routeId = null, $stationFromId = null, $all = false, $type = NULL)
    {
        $route = Route::find($routeId);
        $select = [];

        $stations = $route->stationsActive->load('city');
        $cityFirstId = $stations->first()->city_id;
        $cityLastId = $stations->last()->city_id;

        $stationGo = $stationFromId ? false : true;
        foreach ($stations as $station) {
            if ($stationGo) {
                if ((!$stationFromId && $cityLastId != $station->city_id) || ($stationFromId && $cityFirstId != $station->city_id) || $all)
                    $select[$station->id] = $station->city->name . ' ' . $station->name;
            }

            if (!$stationGo && $stationFromId == $station->id) $stationGo = true;
        }

        return $select;
    }

    public function socialStatuses()
    {
        $statuses = Status::query()->get();

        $result = collect(['' => trans('admin.buses.sel_status')]);
        foreach ($statuses as $status) {
            $result->put($status->id, $status->name . ' ' . $status->percent . '%');
        }
        return $result;
    }

    public function getFirstActiveStationCityRoute(Route $route, $cityId)
    {
        return $route->stations->where('city_id', $cityId)->first();
        foreach ($route->stations as $station)
            if ($station->city_id == $cityId && $station->status == 'active')
                return $station->id;
        return false;
    }

    public function getLastActiveStationCityRoute(Route $route, $cityId)
    {
        $stationTo = false;
        foreach ($route->stations as $station)
            if ($station->city_id == $cityId && $station->status == 'active')
                $stationTo = $station->id;
        return $stationTo;
    }

    public function actTemplates()
    {
        $user = auth()->user();
        $templates = ReviewActTemplate::with('items')->has('items')->ofStatus('active')->get();
        return $templates->pluck('name', 'id')->prepend(trans('admin_labels.act_templates'), '');
    }

    public function cardTemplates()
    {
        $user = auth()->user();
        $templates = DiagnosticCardTemplate::with('items')->has('items')->ofStatus('active')->get();
        return $templates->pluck('name', 'id')->prepend(trans('admin_labels.act_templates'), '');
    }
}

