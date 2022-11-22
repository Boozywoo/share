<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DriverRequest;
use App\Jobs\Bus\BusImportJob;
use App\Jobs\Driver\DriverImportJob;
use App\Models\Company;
use App\Models\Driver;
use App\Models\DriverFines;
use App\Models\Order;
use App\Models\Route;
use App\Models\Tour;
use App\Models\User;
use App\Repositories\SelectRepository;
use Barryvdh\Debugbar\Middleware\Debugbar;
use Carbon\Carbon;

class DriverController extends Controller
{
    protected $entity = 'drivers';
    protected $select;

    public function __construct(SelectRepository $selectRepository)
    {
        $this->select = $selectRepository;
    }

    public function import()
    {
        if (!Company::all()->count()) {
            return $this->responseError(['message' => 'Отсуствуют компании']);
        }

        $results = \Excel::load(request()->file('file'))->get();
        dispatch(new DriverImportJob($results));
        return $this->responseSuccess(['message' => 'Водители загружаются']);
    }

    public function index()
    {
        $companies = auth()->user()->companyIds;

        $drivers = Driver::filter(request()->all())
            ->filter(['companies' => $companies])
            ->paginate();

        if (request()->ajax() && !request('_pjax')) return $this->ajaxView($drivers);
        return view('admin.' . $this->entity . '.index', compact('drivers') + ['entity' => $this->entity]);
    }

    public function create()
    {
        $driver = new Driver();
        $companies = $this->select->companies(auth()->user()->id);
        $routes = $this->select->routes(null, false);
        $driverRoutes = $driver->routes->keyBy('id');

        return view('admin.' . $this->entity . '.edit', compact('driver', 'companies', 'routes', 'driverRoutes') + ['entity' => $this->entity]);
    }

    public function edit(Driver $driver)
    {
        //$this->authorize('driver-id', $driver->id);
        $companies = $this->select->companies(auth()->user()->id);
        $routes = $this->select->routes(null, false);
        $driverRoutes = $driver->routes->keyBy('id');

        return view('admin.' . $this->entity . '.edit', compact('driver', 'companies', 'routes', 'driverRoutes') + ['entity' => $this->entity]);
    }

    public function fines(Driver $driver)
    {
        //$this->authorize('driver-id', $driver->id);
        return view('admin.' . $this->entity . '.fines', compact('driver') + ['entity' => $this->entity]);
    }

    public function add_fine(Driver $driver)
    {
        $fine = new DriverFines();
        $types = $this->select->driverFineTypes();
        return view('admin.' . $this->entity . '.fine', compact('driver', 'types', 'fine') + ['entity' => $this->entity]);
    }

    public function store_fine(Driver $driver)
    {
        $data = request()->except(['_token']);
        $data['date'] = isset($data['date']) ? date('Y-m-d', strtotime($data['date'])) : null;
        if (request('id')) DriverFines::whereId(request('id'))->update($data);
        else DriverFines::create($data);
        return $this->responseSuccess();
    }

    public function edit_fine(Driver $driver, DriverFines $fine)
    {
        $types = $this->select->driverFineTypes();
        return view('admin.' . $this->entity . '.fine', compact('driver', 'types', 'fine') + ['entity' => $this->entity]);
    }

    public function store(DriverRequest $request)
    {
        //$this->authorize('driver-id', request('id'));

        if ($id = request('id')) {
            $driver = Driver::find($id);
            if (request('status') == Driver::STATUS_DISABLE) {
                $toursCount = Tour::filter(['driver_id' => $driver->id])->future()->count();
                if ($toursCount) return $this->responseError(['message' => trans('messages.admin.drivers.statuses.disabled')]);
            }
            $driver->update(request()->all());

        } else {
            $driver = Driver::create(request()->all());
        }
        $driver->syncImages(request()->all());

        $routes = request('routes', []);

        foreach ($routes as $key => $route) {
            if (isset($route['check'])) {
                unset($routes[$key]['check']);
            } else {
                unset($routes[$key]);
            }
        }

        $driver->routes()->sync($routes);
        return $this->responseSuccess();
    }

    public function delete(Driver $driver)
    {
        $driver->delete();
        return $this->responseSuccess();
    }

    public function statics()
    {
        $buses = $this->select->buses(auth()->user()->companyIds);
        $busesFilter = $buses->count() ? $buses->keys()->toArray() : [-1];

        $drivers = Driver::filter(request()->except('buses') + ['buses' => $busesFilter])
            ->with(['reviewsPositive', 'reviewsNegative'])
            ->latest()
            ->get();

        if (!$dateFrom = request('date_from')) $dateFrom = Carbon::now()->subMonths(1)->format('Y-m-d');
        if (!$dateTo = request('date_to')) $dateTo = Carbon::now()->addDay()->format('Y-m-d');

        foreach ($drivers as $driver) {
            $driver->routes = Route::with(['tours' => function ($q) use ($driver, $dateFrom, $dateTo) {
                $q->filter([
                    'driver_id' => $driver->id,
                    'between' => ['dateFrom' => $dateFrom, 'dateTo' => $dateTo]
                ]);
            }])->get();
        }

        if (request()->ajax() && !request('_pjax')) {
            return response([
                'view' => view('admin.' . $this->entity . '.statics.table', compact('drivers') + ['entity' => $this->entity])->render(),
            ])->header('Cache-Control', 'no-cache, no-store');
        }

        return view('admin.' . $this->entity . '.statics', compact('drivers') + ['entity' => $this->entity]);
    }

    public function pays(Driver $driver)
    {
        $targetCurrencyId = (int)request('currency_id');

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

        $between = [$dateStart->startOfDay()->format('Y-m-d H:i:s'), $dateFinish->endOfDay()->format('Y-m-d H:i:s')];
        $betweenDate = [$dateStart->startOfDay()->format('Y-m-d'), $dateFinish->endOfDay()->format('Y-m-d')];

        $appearance = request('appearance');
        $orders = Order::query()
            ->where('type', Order::TYPE_COMPLETED)
            ->whereStatus('active')
            ->whereHas('tour', static function ($query) use ($betweenDate, $driver) {
                $query->whereBetween('date_start', $betweenDate);
                $query->where('driver_id', $driver->id);
            })
            ->with('stationFrom.city', 'stationTo.city', 'tour.route.currency')->get();
  
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
        }

        if (isset($targetCurrencyId) && $targetCurrencyId > 0) {
            $orders = $orders->where('tour.route.currency_id', $targetCurrencyId);
        }

        $totalSum = [];
        $orderSum = [];
        $totalPrice = [];
        $filteredOrders = collect([]);

        foreach ($orders as $order) {
            $sum = 0;

            $route = $order->tour->route;
            $currency = $route->currency;

            if ($order->price) {
                if ($route->bonus_driver !== 0) {
                    if ($route->bonus_driver_type) {
                        $sum += $order->price * $route->bonus_driver * 0.01;
                        // валюта остается как есть, от заказа
                    } else {
                        $sum = $route->bonus_driver;
                        // валюта остается как есть, от направления
                    }
                }

                $pivot = $route->driverPivot()->where('driver_id', $driver->id)->first();
                if ($pivot) {
                    if ($pivot->pay_order_fix > 0.0) {
                        $sum += $pivot->pay_order_fix;
                    } elseif ($pivot->pay_order_percent > 0) {
                        $sum += $order->price * $pivot->pay_order_percent * 0.01;
                        // валюта остается как есть, от заказа
                    }
                }

                $filteredOrders->push($order);

                $orderSum[$order->id] = [
                    'sum' => $sum,
                    'currency' => $currency,
                ];

                $totalSum[$currency->alfa] = ($totalSum[$currency->alfa] ?? 0) + $sum;
                $totalPrice[$currency->alfa] = ($totalPrice[$currency->alfa] ?? 0) + $order->price;
            }

        }

        $compact = compact('driver', 'filteredOrders', 'totalSum', 'orderSum', 'totalPrice');

        if (request()->ajax() && !request('_pjax')) {
            return response([
                'view' => view('admin.' . $this->entity . '.statics.pays_table', $compact + ['entity' => $this->entity])->render(),
            ])->header('Cache-Control', 'no-cache, no-store');
        }

        return view('admin.' . $this->entity . '.pays', $compact + ['entity' => $this->entity]);
    }

    protected function ajaxView($drivers)
    {
        return response([
            'view' => view('admin.' . $this->entity . '.index.table', compact('drivers') + ['entity' => $this->entity])->render(),
            'pagination' => view('admin.partials.pagination', ['paginator' => $drivers])->render(),
        ])->header('Cache-Control', 'no-cache, no-store');
    }

    public function print_page_template_excel()
    {
        \Excel::create("[шаблон для импорта водителей] ", function ($excel) {
            $excel->sheet('Водители', function ($sheet) {

                $places[] = [
                    '#' => '',
                    'ФИО' => '',
                    'Компания' => '',
                    'Телефон (для приложения)' => '',
                    'Рабочий телефон (для клиентов в смс)' => '',
                    'пароль' => '',
                ];
                $sheet->fromArray($places);
            });
        })->export('xlsx');
    }

    public function setBusesPopup(Driver $driver)
    {
        $buses = $driver->company->buses;
        $checked = $driver->buses()->get()->pluck('id', 'id');
        return ['html' => view('admin.drivers.popups.buses',
            compact('buses', 'checked', 'driver') + ['entity' => $this->entity])->render()];
    }

    public function setUserBuses(Driver $driver)
    {
        $buses = request('buses');
        $driver->buses()->sync($buses);

        return $this->responseSuccess();
    }

}
