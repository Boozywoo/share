<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Jobs\User\UserImportJob;
use App\Models\Bus;
use App\Models\Currency;
use App\Models\Department;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\Salary;
use App\Models\User;
use App\Models\Tour;
use App\Models\Order;
use App\Models\MonitoringSetting;
use App\Models\MonitoringBus;
use App\Models\Company;
use App\Models\Route;
use App\Repositories\SelectRepository;
use App\Models\City;
use Carbon\Carbon;

class UserController extends Controller
{
    protected $entity = 'users';
    protected $select;

    public function __construct(SelectRepository $selectRepository)
    {
        $this->select = $selectRepository;
    }

    public function import()
    {
        if (!Company::all()->count()) {
            return $this->responseError(['message' => 'Отсуствуют пользователи']);
        }

        $results = \Excel::load(request()->file('file'))->get();
        dispatch(new UserImportJob($results));
        return $this->responseSuccess(['message' => 'Пользователи загружаются']);
    }

    public function index()
    {
        $users = User::whereNull('client_id')->filter(request()->all())->latest()->paginate();
        if (request()->ajax() && !request('_pjax')) return $this->ajaxView($users);
        $roles = $this->select->roles();
        return view('admin.' . $this->entity . '.index', compact('users', 'roles') + ['entity' => $this->entity]);
    }

    public function create()
    {
        $user = new User();
        $timezonelist = City::getTimezoneList();
        $rolesSelect = $this->select->roles();
        $companies = $this->select->companies();
        $userCompanies = $this->select->companies();
        $userCompanies->prepend('- ' . trans('admin_labels.not_selected') . ' -', 0);
        $routes = $this->select->routes(null, false);
        $userCompanyPays = [];
        $departments = [];
        $positions = [];
        if (request()->has('company_id')) {
            $user->company_id = request('company_id');
            $positions = $this->select->positions(request('company_id'));
            request()->has('company_id') ? $departments = $this->select->departments(request('company_id')) : $departments = [];
        }
        if (request()->has('department_id')) {
            $user->department_id = request('department_id');
        }

        $currencies = Currency::all('id', 'name')->pluck('name', 'id');

        $notification = null;

        return view('admin.' . $this->entity . '.edit',
            compact(
                'user',
                'departments',
                'positions',
                'userCompanyPays',
                'rolesSelect',
                'companies',
                'userCompanies',
                'routes',
                'timezonelist',
                'notification',
                'currencies') + ['entity' => $this->entity]);
    }

    public function edit(User $user)
    {
        if ($user->client) abort(404);
        $rolesSelect = $this->select->roles();
        $companies = $this->select->companies();
        $userCompanies = $companies;
        $timezonelist = City::getTimezoneList();
        $userCompanies->prepend('- ' . trans('admin_labels.not_selected') . ' -', 0);
        $routes = $this->select->routes(null, false);
        $userRoutes = $user->routes->keyBy('id');
        $userCompanyPays = $user->companies->keyBy('id');
        
        $user->company_id ? $departments = $this->select->departments($user->company_id) : $departments = [];
        if (count($departments) > 0) {
            $departments->prepend('- ' . trans('admin_labels.not_selected') . ' -', 0);
        }
        
        $user->company_id ? $positions = $this->select->positions($user->company_id) : $positions = [];
        if (count($positions) > 0) {
            $positions->prepend('- ' . trans('admin_labels.not_selected') . ' -', 0);
        }
        
        $superiors = [];
        if ($user->company_id) {
            $superiors = $this->select->superiors($user->id, $user->company_id);
        }
        if (count($superiors) > 0) {
            $superiors->prepend('- ' . trans('admin_labels.not_selected') . ' -', 0);
        }

        $currencies = Currency::all('id', 'name')->pluck('name', 'id');

        $notification = Notification::find(request()->query('noti'));

        return view('admin.' . $this->entity . '.edit', ['entity' => $this->entity] +
            compact(
                'user',
                'rolesSelect',
                'companies',
                'routes',
                'departments',
                'positions',
                'userCompanies',
                'userCompanyPays',
                'userRoutes',
                'timezonelist',
                'currencies',
                'superiors',
                'notification'));
    }

    public function pays(User $user)
    {
        if ($dateStart = \request()->get('date_from')) {
            $dateStart = Carbon::parse(\request('date_from'));
        } else {
            $dateStart = Carbon::now()->addMonth(-1);
        }

        if ($dateFinish = \request()->get('date_to')) {
            $dateFinish = Carbon::parse(\request('date_to'));
        } else {
            $dateFinish = Carbon::now();
        }
        $appearance = request('appearance');

        $urlExcel = \route('admin.users.pays.excel', $user) . '?' .
            http_build_query([
                'year_start' => $dateStart->year,
                'month_start' => $dateStart->month,
                'day_start' => $dateStart->day,
                'year_finish' => $dateFinish->year,
                'month_finish' => $dateFinish->month,
                'day_finish' => $dateFinish->day,
                'appearance' => $appearance
            ]);

        $targetCurrencyId = (int)request('currency_id');
        

        $between = [$dateStart->startOfDay()->format('Y-m-d H:i:s'), $dateFinish->endOfDay()->format('Y-m-d H:i:s')];
        $betweenDate = [$dateStart->startOfDay()->format('Y-m-d'), $dateFinish->endOfDay()->format('Y-m-d')];

        $pays = $user->pays($dateStart->year, $dateStart->month, $dateFinish->year, $dateFinish->month);

        $payMonth = $pays['month']->reduce(function ($result, $value) use ($targetCurrencyId, $user) {
            if ($value->route_id) {
                $route = Route::query()->find($value->route_id)->with('currency')->first();
                if ($targetCurrencyId === 0 || $targetCurrencyId === $route->currency_id) {
                    $result[$route->currency->alfa] = $result[$route->currency->alfa] ?? 0 + $value->sum;
                }
            } elseif ($value->company_id) {
                if ($targetCurrencyId === 0 || $targetCurrencyId === $user->currency_id) {
                    $result[$user->currency->alfa] = $result[$user->currency->alfa] ?? 0 + $value->sum;
                }
            }

            return $result;
        }, []);

        $payMonth = $this->sumNotNull($payMonth);

        $monthCompany = $pays['month']->pluck('company_id');
        $monthCompany = Company::whereIn('id', $monthCompany)->get()->keyBy('id');

        $monthRoute = $pays['month']->pluck('route_id');
        $monthRoute = Route::whereIn('id', $monthRoute)->get()->keyBy('id');

        $payOrder = $pays['order']->reduce(function ($result, $value) use ($targetCurrencyId) {
            if ($value->route_id) {
                $route = Route::query()->find($value->route_id)->with('currency')->first();
                if ($targetCurrencyId === 0 || $targetCurrencyId === $route->currency_id) {
                    $result[$route->currency->alfa] = $result[$route->currency->alfa] ?? 0 + $value->sum;
                }
            }

            return $result;
        }, []);
        $payOrder = $this->sumNotNull($payOrder);
        
        $totalPay = $payMonth;
        foreach ($payOrder as $alfa => $value) {
            $totalPay[$alfa] = $totalPay[$alfa] ?? 0 + $value;
        }

        $payOrders = $pays['order']->pluck('order_id');
        $payOrders = Order::with('tour', 'client')
            ->whereIn('id', $payOrders)
            ->whereBetween('from_date_time', $between)
            ->whereStatus('active')->get();

        $orders = Order::with('orderPlaces', 'tour.route.currency', 'client', 'stationFrom.city', 'stationTo.city')//Брони, которые на рейсе в этот период
            ->whereBetween('from_date_time', $between)
            ->whereHas('tour', static function ($query) use ($betweenDate) {
                $query->whereBetween('date_start', $betweenDate);
            })
            ->where('created_user_id', $user->id)
            ->whereStatus('active')->get();

        foreach($orders as $order) {
            if($order->appearance === null) {
                foreach($order->orderPlaces as $op) {
                    if($op->appearance == 1) {
                        $order->appearance = 1;
                        $order->save();
                    }
                } 
            }
        }

        if($appearance === '0' || $appearance === '1'){
            $orders = $orders->where('appearance', $appearance);
            $payOrders = $payOrders->where('appearance', $appearance)->keyBy('id');
        }

        if (isset($targetCurrencyId) && $targetCurrencyId > 0) {
            $orders = $orders->where('tour.route.currency_id', $targetCurrencyId);
        }

        $paysSum = [];
        $resultSum = [];
        $resultPrice = [];
        $resultPriceActive = [];

        foreach ($orders->where('status', 'active') as $order) {
            $currency = $order->getCurrencyAttribute();

            $resultPrice[$currency->alfa] = ($resultPrice[$currency->alfa] ?? 0) + $order->price;

            if ($order->status === \App\Models\Order::STATUS_ACTIVE && $order->appearance == $appearance) {
                $resultPriceActive[$currency->alfa] = ($resultPriceActive[$currency->alfa] ?? 0) + $order->price;
            }

            $route = $user->routes->find($order->tour->route);

            $sum = 0;
            if ($route) {
                if ($route->bonus_agent !== 0) {
                    if ($route->bonus_agent_type) {
                        $sum = $order->price * $route->bonus_agent * 0.01;
                        // валюта остается как есть, от заказа
                    } else {
                        $sum = $route->bonus_agent;
                        // валюта остается как есть, от направления
                    }
                }

                if ($route->pivot->pay_order_fix > 0.0) {
                    if ($user->currency_id !== $currency->id) {
                        $sum = $route->pivot->pay_order_fix;
                        $currency = $user->currency;
                    } else {
                        $sum += $route->pivot->pay_order_fix;
                    }
                } elseif ($route->pivot->pay_order_percent > 0) {
                    $sum += $order->price * $route->pivot->pay_order_percent * 0.01;
                    // валюта остается как есть, от заказа
                }
            }

            $resultSum[$currency->alfa] = ($resultSum[$currency->alfa] ?? 0) + $sum;

            $paysSum[$order->id] = [
                'sum' => $sum,
                'currency_alfa' => $currency->alfa,
            ];
        }

        $resultPrice = $this->sumNotNull($resultPrice);
        $resultPriceActive = $this->sumNotNull($resultPriceActive);
        $resultSum = $this->sumNotNull($resultSum);

        $salaries = Salary::query()
            ->whereBetween('created_at', $between)
            ->where('user_id', $user->id)
            ->with('currency');

        if (isset($targetCurrencyId) && $targetCurrencyId > 0) {
            $salaries = $salaries->where('currency_id', $targetCurrencyId);
        }

        $salaries = $salaries->get();

        $salariesSum = $this->sumNotNull($salaries->reduce(function ($result, $value) {
            $result[$value->currency->alfa] = ($result[$value->currency->alfa] ?? 0) + $value->sum;
            return $result;
        }, []));

        $balance = $payMonth + $resultSum;
        foreach ($salariesSum as $alfa => $value) {
            $balance[$alfa] = ($balance[$alfa] ?? 0) - $value;
        }

        $totalPay = $payMonth + $resultSum;

        $orderCompany = $orders->pluck('company_id');
        $orderCompany = Company::whereIn('id', $orderCompany)->get()->keyBy('id');

        $orderRoute = $orders->pluck('route_id');
        $orderRoute = Route::whereIn('id', $orderRoute)->get()->keyBy('id');

        $compact = compact('user', 'payOrders', 'monthCompany', 'monthRoute', 'orderCompany', 'orderRoute',
            'payMonth', 'payOrder', 'pays', 'salaries', 'urlExcel', 'orders',
            'paysSum', 'resultPrice', 'resultSum', 'resultPriceActive',
            'totalPay', 'salariesSum', 'balance', 'appearance'
        );

        if (request()->ajax() && !request('_pjax')) {
            return response([
                'view' => view('admin.' . $this->entity . '.pays.table', ['entity' => $this->entity] + $compact)->render(),
            ])->header('Cache-Control', 'no-cache, no-store');
        }

        return view('admin.' . $this->entity . '.pays', ['entity' => $this->entity] + $compact);
    }

    protected function sumNotNull(array $array)
    {
        return array_where($array, static function ($value) {
            return (float)$value !== 0.0;
        });
    }

    public function paysExcel(User $user)
    {
        $year_start = \request()->get('year_start', date('Y'));
        $month_start = \request()->get('month_start', date('m'));
        $day_start = \request()->get('day_start', date('d'));
        $year_finish = \request()->get('year_finish', date('Y'));
        $month_finish = \request()->get('month_finish', date('m'));
        $day_finish = \request()->get('day_finish', date('d'));

        $between = [$year_start . '-' . $month_start . '-' . $day_start . ' 00:00:00', $year_finish . '-' . $month_finish . '-' . $day_finish . ' 23:59:59'];
        
        $appearance = request('appearance');

        \Excel::create('user', function ($excel) use ($user, $between, $appearance ) {
            $orders = Order::with('operator', 'tour', 'client')
                ->where('created_user_id', $user->id)
                ->whereStatus('active')
                ->whereBetween('from_date_time', $between);

            if($appearance === '0' || $appearance === '1'){
                $orders = $orders->where('appearance', $appearance)->get()->transform(function ($order) {
                    if($noAppearanceCount = $order->orderPlaces->where('appearance', '===', 0)->count()) {
                        $noAppearanceCount = trans('admin.tours.absence') . ':' . $noAppearanceCount;
                    } else {
                        $noAppearanceCount = ' ';
                    }
                    if($AppearanceCount = $order->orderPlaces->where('appearance', '===', 1)->count()){
                        $AppearanceCount = trans('admin.tours.presence') . ':' . $AppearanceCount;
                    } else {
                        $AppearanceCount = ' ';
                    }
                    return [$order->slug, $order->created_at, $order->tour ? $order->tour->date_start : '',
                        $order->tour ? $order->tour->route->name : '',
                        $order->price, $order->client->last_name . ' ' . $order->client->first_name,
                        $order->client->phone, $order->orderPlaces->count(),
                        $order->operator->last_name . ' ' . $order->operator->first_name,
                        $noAppearanceCount . ' ' . $AppearanceCount];
                });
            } else {
                $orders = $orders->get()->transform(function ($order) {
                    if($noAppearanceCount = $order->orderPlaces->where('appearance', '===', 0)->count()) {
                        $noAppearanceCount = trans('admin.tours.absence') . ':' . $noAppearanceCount;
                    } else {
                        $noAppearanceCount = ' ';
                    }
                    if($AppearanceCount = $order->orderPlaces->where('appearance', '===', 1)->count()){
                        $AppearanceCount = trans('admin.tours.presence') . ':' . $AppearanceCount;
                    } else {
                        $AppearanceCount = ' ';
                    }
                    return [$order->slug, $order->created_at, $order->tour ? $order->tour->date_start : '',
                        $order->tour ? $order->tour->route->name : '',
                        $order->price, $order->client->last_name . ' ' . $order->client->first_name,
                        $order->client->phone, $order->orderPlaces->count(),
                        $order->operator->last_name . ' ' . $order->operator->first_name,
                        $noAppearanceCount . ' ' . $AppearanceCount];
                });
            }

            if ($orders->count()) {
                $sheetName = $user->last_name . ' ' . $user->first_name;
                $excel->sheet(mb_substr($sheetName, 0, 31), function ($sheet) use ($orders) {
                    $orders = $orders->toArray();
                    array_unshift($orders, ['#', 'Дата создания', 'Дата рейса', 'Направление', 'Цена',
                        'Имя клиента', 'Телефон клиента', 'Кол-во мест', 'Имя оператора', 'Явка']);
                    $sheet->fromArray($orders);

                });
            }
        })->export('xls');
    }


    public function store(UserRequest $request)
    {
        if (!filter_var(request('email'), FILTER_VALIDATE_EMAIL)) {
            return $this->responseError(['message' => trans('validation.email')]);
        }
        
        if ($id = request('id')) {
            $user = User::find($id);
            $data = request()->all();

            if ($user->client) abort(404);
            $user->update($data);
        } else {
            $user = User::create(request()->all());
            $defaultTheme = \App\Models\InterfaceSetting::where('theme_name', 'black')
                ->first()->id;
            $user->interface_setting_id = $defaultTheme;
            $user->save();
        }

        $user->detachAllRoles();
        $user->attachRole(request('role_id'));

        $companies = request('companies', []);
        $routes = request('routes', []);

        foreach ($companies as $key => $company) {
            if (isset($company['check'])) unset($companies[$key]['check']);
            else unset($companies[$key]);
        }

        foreach ($routes as $key => $route) {
            if (isset($route['check'])) unset($routes[$key]['check']);
            else unset($routes[$key]);
        }

        $user->companies()->sync($companies);
        $user->routes()->sync($routes);

        return $this->responseSuccess();
    }

    public function delete(User $user)
    {
        $user->delete();
        return $this->responseSuccess();
    }

    protected function ajaxView($users)
    {
        return response([
            'view' => view('admin.' . $this->entity . '.index.table', compact('users') + ['entity' => $this->entity])->render(),
            'pagination' => view('admin.partials.pagination', ['paginator' => $users])->render(),
        ])->header('Cache-Control', 'no-cache, no-store');
    }

    public function statistic()
    {
        $dateFrom = \request()->get('date_from', date('Y-m-d', strtotime('-1 month')));
        $dateTo = \request()->get('date_to', date('Y-m-d'));
        $between = [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'];
        $betweenDate = [$dateFrom, $dateTo];

        $users = User::whereNull('client_id')->get();
        $operators = Order::with(['operator', 'tour'])
            ->select(\DB::raw('count(id) as cnt,created_user_id'))
            ->whereStatus('active')
            ->whereBetween('from_date_time', $between)
            ->groupBy(['created_user_id'])
            ->get()
            ->groupBy(['created_user_id']);

        $operatorOrderCompleted = Order::with(['operator', 'tour'])
            ->select(
                \DB::raw('count(id) as cnt, sum(count_places_appearance) as appearance, sum(count_places_no_appearance) as no_appearance,created_user_id'))
            ->whereHas('tour', function ($query) use ($betweenDate) {
                $query->whereBetween('date_start', $betweenDate);
            })
            ->whereStatus('active')
            ->groupBy(['created_user_id'])
            ->get()
            ->groupBy(['created_user_id']);

        $online = Order::whereStatus('active')
            ->whereBetween('from_date_time', $between)
            ->where('source', 'site')
            ->where('type', 'completed')
            ->get();

        $onlineOrders = ['cnt' => 0, 'cntCompleted' => 0, 'appearance' => 0, 'no_appearance' => 0];
        $onlineOrders['cnt'] = $online->count();
                

        $onlineOrders['cntCompleted'] = $online->count();
        $onlineOrders['appearance'] = $online->where('appearance', '1')->count();
        $onlineOrders['no_appearance'] = $online->where('appearance', '!=', '1')->count();
    

        $mobile = Order::whereStatus('active')
            ->whereBetween('from_date_time', $between)
            ->where('source', 'client_app')
            ->where('type', 'completed')
            ->get();

        $mobileOrders = ['cnt' => 0, 'cntCompleted' => 0, 'appearance' => 0, 'no_appearance' => 0];
        $mobileOrders['cnt'] = $mobile->count();
                
        $mobileOrders['cntCompleted'] = $mobile->count();
        $mobileOrders['appearance'] = $mobile->where('appearance', '1')->count();
        $mobileOrders['no_appearance'] = $mobile->where('appearance', '!=', '1')->count();

        foreach ($users as $key => $user) {
            if (!empty($operators[$user->id])) {
                $users[$key]->cnt = $operators[$user->id]->first()->cnt;
            } else {
                $users[$key]->cnt = 0;
            }
            if (!empty($operatorOrderCompleted[$user->id])) {
                $users[$key]->cntCompleted = $operatorOrderCompleted[$user->id]->first()->cnt;
                $users[$key]->appearance = $operatorOrderCompleted[$user->id]->first()->appearance;
                $users[$key]->no_appearance = $operatorOrderCompleted[$user->id]->first()->no_appearance;
            } else {
                $users[$key]->cntCompleted = 0;
                $users[$key]->appearance = 0;
                $users[$key]->no_appearance = 0;
            }
        }
        $users = $users->sortByDesc('cnt');

        $urlExcel = \route('admin.users.statistic.excel') . '?' . http_build_query(compact('dateFrom', 'dateTo', 'appearance'));
        if (request()->ajax() && !request('_pjax')) return response([
            'view' => view('admin.' . $this->entity . '.statistic.table', ['entity' => $this->entity] + compact('users', 'urlExcel', 'onlineOrders', 'mobileOrders'))->render(),
        ])->header('Cache-Control', 'no-cache, no-store');

        return view('admin.' . $this->entity . '.statistic', compact('users', 'urlExcel', 'onlineOrders', 'mobileOrders') + ['entity' => $this->entity]);
    }

    public function statisticExcel(User $user)
    {
        $dateFrom = \request()->get('dateFrom', date('Y-m-d', strtotime('-1 month')));
        $dateTo = \request()->get('dateTo', date('Y-m-d'));
        $between = [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'];
        $appearance = \request()->get('appearance');

        $users = User::get();
        \Excel::create('users', function ($excel) use ($users, $between, $appearance) {
            foreach ($users as $user) {
                $orders = Order::with('operator', 'tour', 'client')
                    ->whereHas('client')
                    ->whereNotNull('created_user_id')
                    ->where('created_user_id', $user->id)
                    ->whereBetween('created_at', $between)
                    ->whereStatus('active');
                if($appearance === '0' || $appearance === '1'){
                    $orders = $orders->where('appearance', $appearance)->get()->transform(function ($order) {
                        if($noAppearanceCount = $order->orderPlaces->where('appearance', '===', 0)->count()) {
                            $noAppearanceCount = trans('admin.tours.absence') . ':' . $noAppearanceCount;
                        } else {
                            $noAppearanceCount = ' ';
                        }
                        if($AppearanceCount = $order->orderPlaces->where('appearance', '===', 1)->count()){
                            $AppearanceCount = trans('admin.tours.presence') . ':' . $AppearanceCount;
                        } else {
                            $AppearanceCount = ' ';
                        }
                        return [$order->slug, $order->created_at, $order->tour ? $order->tour->date_start : '',
                            $order->tour ? $order->tour->route->name : '',
                            $order->price, $order->client->last_name . ' ' . $order->client->first_name,
                            $order->client->phone, $order->orderPlaces->count(),
                            $order->operator->last_name . ' ' . $order->operator->first_name,
                            $noAppearanceCount . ' ' . $AppearanceCount];
                    });
                } else {
                    $orders = $orders->get()->transform(function ($order) {
                        if($noAppearanceCount = $order->orderPlaces->where('appearance', '===', 0)->count()) {
                            $noAppearanceCount = trans('admin.tours.absence') . ':' . $noAppearanceCount;
                        } else {
                            $noAppearanceCount = ' ';
                        }
                        if($AppearanceCount = $order->orderPlaces->where('appearance', '===', 1)->count()){
                            $AppearanceCount = trans('admin.tours.presence') . ':' . $AppearanceCount;
                        } else {
                            $AppearanceCount = ' ';
                        }
                        return [$order->slug, $order->created_at, $order->tour ? $order->tour->date_start : '',
                            $order->tour ? $order->tour->route->name : '',
                            $order->price, $order->client->last_name . ' ' . $order->client->first_name,
                            $order->client->phone, $order->orderPlaces->count(),
                            $order->operator->last_name . ' ' . $order->operator->first_name,
                            $noAppearanceCount . ' ' . $AppearanceCount];
                    });
                }
                if ($orders->count()) {
                    $sheetName = $user->last_name . ' ' . $user->first_name;
                    $excel->sheet(mb_substr($sheetName, 0, 31), function ($sheet) use ($orders) {
                        $orders = $orders->toArray();
                        array_unshift($orders, ['#', 'Дата создания', 'Дата рейса', 'Направление', 'Цена',
                            'Имя клиента', 'Телефон клиента', 'Кол-во мест', 'Имя оператора', 'Явка']);
                        $sheet->fromArray($orders);

                    });
                }
            }
        })->export('xls');
    }

    public function isDriverExceededSpeed() {
        if(MonitoringSetting::first() != null) {
            $highSpeed = MonitoringSetting::pluck('high_speed')->first();
        } else {
            $highSpeed = 100;
        }

        $role =\Auth::user()->roles->first()->slug;

        $bus = MonitoringBus::orderBy('updated_at', 'desc')->first();

        if(!empty($bus) && (($bus->speed * 3.6) >= $highSpeed)) {
            $now = Carbon::now(Setting::pluck('default_timezone')->first());

            $dateTimeTours = Tour::where('status', 'active')->where('date_time_start', '<', $now)
            ->where('date_time_finish', '>', $now)->where('bus_id', $bus->id)->first();

            if(($role == 'superadmin' || $role == 'admin') && !empty($dateTimeTours)) {
                $busNumber = !empty($bus) ? $bus->bus->number : "номера нет";
                $message = "Автобус " . $busNumber . " привысил скорость " . $bus->speed . " км/ч с координатами: широта - "
                . $bus->latitude . ", долгота - " . $bus->longitude;

                return response()->json([
                    'isExceeded' => !empty($bus),
                    'message' => $message,
                    'busId' => $bus->id,
                    'speed' => $bus->speed * 3.6,
                ]);
            } else {
                return response()->json([
                    'isExceeded' => false,
                ]);
            }
        } else {
            return response()->json([
                'isExceeded' => 'Пустой автобус',
            ]);
        }
        
    }

    public function print_page_template_excel()
    {
        \Excel::create("[шаблон для импорта пользователей] ", function ($excel) {
            $excel->sheet('Пользователи', function ($sheet) {
                $places[] = [
                    '#' => '',
                    'Имя' => '',
                    'Роль' => '',
                    'Пароль' => '',
                    'Email' => '',
                    'Телефон' => '',
                ];
                $sheet->fromArray($places);
            });
        })->export('xlsx');
    }

    public function getDepartments()
    {
        $departments = [];
        if (!empty(request('val')) && $id = request('val')) {
            $departments = $this->select->departments($id);
            if (count($departments) > 0) {
                $departments->prepend('- ' . trans('admin_labels.not_selected') . ' -', 0);
            }
            return $this->responseSuccess([
                'val' => $departments,
                'view' => view('admin.' . $this->entity . '.index.template', compact('departments'))->render(),
            ]);
        } else {
            return $this->responseSuccess([
                'val' => $departments,
                'view' => view('admin.' . $this->entity . '.index.template', compact('departments'))->render(),
            ]);
        }
    }

    /*
    Список возможных должностей для пользователя
    */
    public function getPositions()
    {
        $positions = [];
        
        if (!empty(request('company')) 
                && $company_id = request('company') ) {
            $positions = $this->select->positions($company_id);
            if (count($positions) > 0) {
                $positions->prepend('- ' . trans('admin_labels.not_selected') . ' -', 0);
            }
        } 
        return $this->responseSuccess([
            'val' => $positions,
            'view' => view('admin.' . $this->entity . '.index.template-positions', compact('positions'))->render(),
        ]);
    }

    /*
    Список возможных руководителей пользователя
    */
    public function getSuperiors()
    {
        $superiors = [];
        
        // id юзера, если не передан, то 0
        $user_id = 0;
        if (!empty(request('user'))) {
            $user_id = request('user');
        }
        
        if (!empty(request('company')) 
                && $company_id = request('company') ) {
            $superiors = $this->select->superiors($user_id, $company_id);
            if (count($superiors)>0) {
                $superiors->prepend('- ' . trans('admin_labels.not_selected') . ' -', 0);
            }
        } 
        return $this->responseSuccess([
            'val' => $superiors,
            'view' => view('admin.' . $this->entity . '.index.template-superiors', compact('superiors'))->render(),
        ]);
    }

    public function setBusesPopup(User $user)
    {
        $buses = $user->company->buses ?? collect([]);
        $checked = $user->buses ? $user->buses->pluck('id', 'id') : [];
        return ['html' => view('admin.users.popups.buses',
            compact('buses', 'checked', 'user') + ['entity' => $this->entity])->render()];
    }

    public function setUserBuses(User $user)
    {
        $buses = request('buses');
        $user->buses()->sync($buses);

        return $this->responseSuccess();
    }
}

