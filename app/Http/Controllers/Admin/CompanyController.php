<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CompanyRequest;
use App\Models\Company;
use App\Models\Order;
use App\Models\Route;
use App\Models\Tour;
use App\Repositories\SelectRepository;
use Carbon\Carbon;

class CompanyController extends Controller
{
    protected $entity = 'companies';

    protected $select;

    public function __construct(SelectRepository $selectRepository)
    {
        $this->select = $selectRepository;
    }

    public function index()
    {

        $companies = Company::filter(request()->all())->latest()->paginate();
        if (request()->ajax() && !request('_pjax')) return $this->ajaxView($companies);
        return view('admin.' . $this->entity . '.index', compact('companies') + ['entity' => $this->entity]);
    }

    public function create()
    {
        $company = new Company();
        return view('admin.' . $this->entity . '.edit', compact('company') + ['entity' => $this->entity]);
    }


    public function edit(Company $company)
    {
        return view('admin.' . $this->entity . '.edit', compact('company') + ['entity' => $this->entity]);
    }

    public function store(CompanyRequest $request)
    {
        if ($id = request('id')) {
            $company = Company::find($id);
            if ($company->buses->count() && request('status') == Company::STATUS_DISABLE) {
                $ordersCount = Tour::filter(['buses' => $company->buses->pluck('id')])->future()->count();
                if ($ordersCount) return $this->responseError(['message' => trans('messages.admin.companies.statuses.disabled')]);
            }
            $company->update(request()->all());
        } else {
            Company::create(request()->all());
        }

        return $this->responseSuccess();
    }


    public function companyStaticsExcel(Company $company)
    {
        $dataFilter = [];
        $busId = request()->get('bus_id');

        $routes = $this->select->routes(\auth()->id());
        $buses = $this->select->buses([$company->id]);

        $dataFilter['bus_id'] = $busId ? [$busId] : $buses->keys()->forget(0)->toArray();
        if ($routeId = request()->get('route_id')) {
            $dataFilter['route_id'] = $routeId;
        }
        if ($payType = request()->get('pay_type')) {
            $dataFilter['type_pay'] = $payType;
        }

        $dateFrom = request()->has('date_from') ? Carbon::createFromTimeString(request()->get('date_from') . ' 00:00:00') : Carbon::now()->subMonth();
        $dateTo = request()->has('date_to') ? Carbon::createFromTimeString(request()->get('date_to') . ' 23:59:59') : Carbon::now();
        $dataFilter['between'] = ['dateFrom' => $dateFrom, 'dateTo' => $dateTo];

        $orders = Order::filter($dataFilter + ['routes' => auth()->user()->routeIds])
            ->with('tour.route', 'client')
            ->latest()
            ->get();

        $data = [];
        foreach ($orders as $order) {
            $data[] = [
                '№' => $order->id,
                trans('admin.orders.date_of_travel') => $order->tour->date_start->format('Y-m-d'),
                trans('admin_labels.type_pay') => trans('admin.orders.pay_types.' . $order->type_pay),
                trans('admin.routes.single') => $order->tour->route->name,
                trans('admin.orders.cost') => $order->price,
                trans('admin.orders.seats_quantity') => $order->count_places,
                trans('admin.orders.clients_name') => $order->client ? $order->client->fullName : '',
                trans('admin.orders.clients_phone') => $order->client ? $order->client->phone : '',
            ];
        }

        \Excel::create(trans('admin.users.statistic'), function ($excel) use ($data) {
            if (count($data)) {
                $excel->sheet(trans('admin.users.statistic'), function ($sheet) use ($data) {
                    $sheet->fromArray($data);
                });
            }
        })->export('xls');
        return redirect()->back();

    }

    public function companyStatics(Company $company)
    {
        $urlExcel = \route('admin.companies.companyStaticsExcel', $company) . '?' .
            http_build_query(request()->all());

        $dataFilter = [];
        $busId = request()->get('bus_id');

        $routes = $this->select->routes(\auth()->id());
        $buses = $this->select->buses([$company->id]);

        $dataFilter['bus_id'] = $busId ? [$busId] : $buses->keys()->forget(0)->toArray();
        if ($routeId = request()->get('route_id')) {
            $dataFilter['route_id'] = $routeId;
        }
        if ($payType = request()->get('pay_type')) {
            $dataFilter['type_pay'] = $payType;
        }
        $dateFrom = request()->has('date_from') ? Carbon::createFromTimeString(request()->get('date_from') . ' 00:00:00') : Carbon::now()->subMonth();
        $dateTo = request()->has('date_to') ? Carbon::createFromTimeString(request()->get('date_to') . ' 23:59:59') : Carbon::now();
        $dataFilter['between'] = ['dateFrom' => $dateFrom, 'dateTo' => $dateTo];

        $orders = Order::filter($dataFilter + ['routes' => auth()->user()->routeIds])
            ->with('tour.route', 'client', 'stationFrom.city', 'coupon', 'smsLog')
            ->latest()
            ->paginate();
        if (request()->ajax() && !request('_pjax')) return $this->ajaxViewStaticsOrders($orders, $urlExcel);
        return view('admin.companies.companyStatics', compact('company', 'routes', 'buses', 'orders', 'urlExcel') + ['entity' => $this->entity]);
    }

    public function statics()
    {
        $companies = Company::filter(request()->all())
            ->with(['reviewsPositive', 'reviewsNegative'])
            ->latest()
            ->get();

        foreach ($companies as $company) {
            $company->routes = Route::with(['tours' => function ($q) use ($company) {
                $q->filter(['buses' => $company->buses->pluck('id')]);
            }])->get();
        }
        
        if (request()->ajax() && !request('_pjax')) {
            return response([
                'view' => view('admin.' . $this->entity . '.statics.table', compact('companies') + ['entity' => $this->entity])->render(),
            ])->header('Cache-Control', 'no-cache, no-store');
        }

        return view('admin.companies.statics', compact('companies') + ['entity' => $this->entity]);
    }

    protected function ajaxView($companies)
    {
        return response([
            'view' => view('admin.' . $this->entity . '.index.table', compact('companies') + ['entity' => $this->entity])->render(),
            'pagination' => view('admin.partials.pagination', ['paginator' => $companies])->render(),
        ])->header('Cache-Control', 'no-cache, no-store');
    }

    protected function ajaxViewStaticsOrders($orders, $urlExcel)
    {
        return response([
            'view' => view('admin.companies.companyStatics.table', compact('orders', 'urlExcel') + ['entity' => $this->entity])->render(),
            'pagination' => view('admin.partials.pagination', ['paginator' => $orders])->render(),
        ])->header('Cache-Control', 'no-cache, no-store');
    }

    public function showPopup(Company $company)
    {
        return ['html' => view('admin.companies.popups.edit.content', compact('company') + ['entity' => $this->entity])->render()];
    }
}