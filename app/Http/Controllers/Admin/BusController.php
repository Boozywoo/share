<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BusRequest;
use App\Jobs\Bus\BusImportJob;
use App\Jobs\Bus\BusImportNorilskJob;
use App\Models\Amenity;
use App\Models\Bus;
use App\Models\CarColor;
use App\Models\Company;
use App\Models\CustomerCompany;
use App\Models\CustomerDepartment;
use App\Models\CustomerPersonality;
use App\Models\Department;
use App\Models\DiagnosticCardTemplate;
use App\Models\RepairCardType;
use App\Models\Route;
use App\Models\Template;
use App\Models\Tour;
use App\Repositories\SelectRepository;
use App\Services\Bus\BusService;
use Carbon\Carbon;

class BusController extends Controller
{
    protected $entity = 'buses';
    protected $select;


    public function __construct(SelectRepository $selectRepository)
    {
        $this->select = $selectRepository;
    }

    public function index()
    {
        $companies = $this->select->companies(auth()->id());

        $buses = Bus::filter(request()->except('companies') + ['companies' => auth()->user()->companyIds])
            //->latest()
            ->orderBy('id')
            ->with('mainImage', 'company', 'upcomingRepairs', 'template')
            ->paginate();

        if (request()->ajax() && !request('_pjax')) return $this->ajaxView($buses);

        return view('admin.' . $this->entity . '.index', compact('buses', 'companies') + ['entity' => $this->entity]);
    }

    public function create()
    {
        $bus = new Bus();
        $companies = $this->select->companies(auth()->id());
        $templates = $this->select->templates($bus->places);
        //dd($templates);

        $types = $this->select->busTypes();
        $cities = $this->select->cities(true);
        $busCities = [];
        $busAmenities = [];
        $companiesForAmenityFilter = auth()->user()->companies->pluck('id')->prepend(0);
        $amenities = Amenity::isActive()->whereIn('company_id', $companiesForAmenityFilter)->get();
        $repairCardTemplates = RepairCardType::all()->pluck('name', 'id')->prepend(trans('admin.buses.sel_temp'), '');
        $diagnosticCardTemplates = DiagnosticCardTemplate::all()->pluck('name', 'id')->prepend(trans('admin.buses.sel_temp'), '');
        $colors = CarColor::all()->pluck('name','slug');
        $customerPersons = CustomerPersonality::all()->pluck('name', 'slug');
        $customerCompanies = CustomerCompany::all()->pluck('name', 'slug');
        $customerDepartments = CustomerDepartment::all()->pluck('name', 'slug');

        return view('admin.' . $this->entity . '.edit', compact('bus','colors', 'busAmenities',
                'amenities', 'companies', 'templates', 'types', 'cities', 'busCities','diagnosticCardTemplates','repairCardTemplates',
                'customerCompanies', 'customerDepartments', 'customerPersons') + ['entity' => $this->entity]);

    }

    public function edit(Bus $bus)
    {
        $busService = new BusService();
        $this->authorize('company-id', $bus->company_id);
        $companies = $this->select->companies(auth()->id());
        $templates = $this->select->templates($bus->places);
        $statuses = $this->select->busStatuses($bus->status);
        $types = $this->select->busTypes();
        $cities = $this->select->cities(true);
        $busCities = $bus->cities->count() ? $bus->cities->pluck('id')->toArray() : [];
        $busAmenities = $bus->amenities->count() ? $bus->amenities->pluck('id')->toArray() : [];
        $companiesForAmenityFilter = auth()->user()->companies->pluck('id')->prepend(0);
        $amenities = Amenity::isActive()->whereIn('company_id', $companiesForAmenityFilter)->get();
        $repairCardTemplates = RepairCardType::all()->pluck('name', 'id')->prepend(trans('admin.buses.sel_temp'), '');
        $diagnosticCardTemplates = DiagnosticCardTemplate::all()->pluck('name', 'id')->prepend(trans('admin.buses.sel_temp'), '');
        $colors = CarColor::all()->pluck('name', 'slug');
        $variables = $busService->getVariablesForGraph($bus->variables()->where('created_at', '>=', Carbon::now()->subMonth())->get());
        $customerPersons = CustomerPersonality::all()->pluck('name', 'slug');
        $customerCompanies = CustomerCompany::all()->pluck('name', 'slug');
        $customerDepartments = CustomerDepartment::all()->pluck('name', 'slug');

        return view('admin.' . $this->entity . '.edit', compact('bus', 'colors', 'amenities', 'busAmenities', 'companies',
                'templates', 'variables', 'statuses', 'types', 'cities', 'busCities', 'diagnosticCardTemplates', 'repairCardTemplates',
                'customerCompanies', 'customerDepartments', 'customerPersons') + ['entity' => $this->entity]);

    }

    public function store(BusRequest $request)
    {
        $this->authorize('company-id', request('company_id'));
        if ($id = request('id')) {
            $bus = Bus::find($id);
            $this->authorize('company-id', $bus->company_id);

            if ($bus->template_id != request('template_id')) {
                $tours = Tour::filter(['bus_id' => $bus->id])->future()->count();
                if ($tours) return $this->responseError(
                    [
                        'message' => 'Нельзя изменить шаблон. Есть будущие брони.',
                    ]);
            }
            $bus->update($request->all());

        } else {
            $bus = Bus::create($request->all());
        }

        //$bus->syncImages(request()->all());
        if ($request->has('cities')) {
            $bus->cities()->sync($request->get('cities'));
        }

        if ($request->has('amenities')) {
            $amenities = array_keys(array_filter($request->get('amenities')));
            $bus->amenities()->sync($amenities);
        }

        return $this->responseSuccess();
    }

    public function delete(Bus $bus)
    {
        $bus->delete();
        return $this->responseSuccess();
    }


    public function statics()
    {
        $companies = $this->select->companies(auth()->id());
        $routes = $this->select->routes(auth()->id());
        if (!$dateFrom = request('date_from')) $dateFrom = Carbon::now()->subMonths(1)->format('Y-m-d');
        if (!$dateTo = request('date_to')) $dateTo = Carbon::now()->addDay()->format('Y-m-d');

        $buses = Bus::filter(request()->except('companies') + ['companies' => auth()->user()->companyIds])
            ->with(['company', 'repairs' => function ($q) use ($dateFrom, $dateTo) {
                $q->filter(['between_date_from' => ['dateFrom' => $dateFrom, 'dateTo' => $dateTo]]);
            }])
            ->latest()
            ->get();


        if (request('route_id') > 0) {
            foreach ($buses as $bus) {
                $bus->routes = Route::with(['tours' => function ($q) use ($bus, $dateFrom, $dateTo) {
                    $q->filter([
                        'bus_id' => $bus->id,
                        'between' => ['dateFrom' => $dateFrom, 'dateTo' => $dateTo]
                    ]);
                }])->where('id', '=', request('route_id'))->get();
            }

        } else {
            foreach ($buses as $bus) {
                $bus->routes = Route::with(['tours' => function ($q) use ($bus, $dateFrom, $dateTo) {
                    $q->filter([
                        'bus_id' => $bus->id,
                        'between' => ['dateFrom' => $dateFrom, 'dateTo' => $dateTo]
                    ]);
                }])->get();
            }
        }

        if (request()->ajax() && !request('_pjax')) {
            return response([
                'view' => view('admin.' . $this->entity . '.statics.table', compact('buses', 'routes') + ['entity' => $this->entity])->render(),
            ])->header('Cache-Control', 'no-cache, no-store');
        }
        return view('admin.buses.statics', compact('buses', 'companies', 'routes') + ['entity' => $this->entity]);
    }

    public function import()
    {
        if (!Template::all()->count()) {
            return $this->responseError(['message' => trans('messages.admin.buses.no_patterns')]);
        } elseif (!Company::all()->count()) {
            return $this->responseError(['message' => trans('messages.admin.buses.no_bus')]);
        }

        $results = \Excel::load(request()->file('file'))->get();
        dispatch(new BusImportJob($results));
        return $this->responseSuccess(['message' => trans('messages.admin.buses.load_bus')]);
    }

    public function importNorT()
    {
        if (!Template::all()->count()) {
            return $this->responseError(['message' => trans('messages.admin.buses.no_patterns')]);
        } elseif (!Company::all()->count()) {
            return $this->responseError(['message' => trans('messages.admin.buses.no_bus')]);
        }

        $results = \Excel::load(request()->file('file'))->get();
        dispatch(new BusImportNorilskJob($results));
        return $this->responseSuccess(['message' => trans('messages.admin.buses.load_bus')]);
    }

    public function export()
    {
        $companies = $this->select->companies(auth()->id());
        if (!$dateFrom = request('date_from')) $dateFrom = Carbon::now()->subMonths(1)->format('Y-m-d');
        if (!$dateTo = request('date_to')) $dateTo = Carbon::now()->addDay()->format('Y-m-d');

        if (request('bus_id') != '') {
            $buses = Bus::filter(request()->except('companies') + ['companies' => auth()->user()->companyIds])
                ->with(['company', 'repairs' => function ($q) use ($dateFrom, $dateTo) {
                    $q->filter(['between_date_from' => ['dateFrom' => $dateFrom, 'dateTo' => $dateTo]]);
                }])->where('id', request('bus_id'))
                ->latest()
                ->get();
        } else {
            $buses = Bus::filter(request()->except('companies') + ['companies' => auth()->user()->companyIds])
                ->with(['company', 'repairs' => function ($q) use ($dateFrom, $dateTo) {
                    $q->filter(['between_date_from' => ['dateFrom' => $dateFrom, 'dateTo' => $dateTo]]);
                }])
                ->latest()
                ->get();
        }

        foreach ($buses as $bus) {
            $bus->routes = Route::with(['tours' => function ($q) use ($bus, $dateFrom, $dateTo) {
                $q->filter([
                    'bus_id' => $bus->id,
                    'between' => ['dateFrom' => $dateFrom, 'dateTo' => $dateTo]
                ]);
            }])->get();
        }

        if ($buses->count()) {
            $response = array();
            $resultTour = 0;
            $resultTypePay = '';
            $resultWorkDay = 0;
            $resultRepairDay = 0;

            $resultcashpayment = 0;
            $resultcashlesspayment = 0;
            $resultcheckingaccount = 0;
            $resultsuccess = 0;
            $resultPrice = 0;
            $resultAppearance = 0;
            $resultNoAppearance = 0;
            foreach ($buses as $busName => $clients) {
                $trCountTour = 0;
                $trCountWorkDay = 0;
                $trPrice = 0;
                $trPrice_cashpayment = 0;
                $trPrice_cashlesspayment = 0;
                $trPrice_checkingaccount = 0;
                $trPrice_success = 0;
                $trTypePay = '';
                $trAppearance = 0;
                $trNoAppearance = 0;
                $trCountRepairDay = $bus->repairs->count();
                $busName = "mybus";  //= substr($busName, 0, 30);
                if (!isset($response[$busName])) {
                    $response[$busName][] = [
                        trans('admin_labels.status'),
                        trans('admin_labels.name'),
                        trans('admin_labels.number'),
                        trans('admin_labels.company_id'),
                        trans('admin_labels.routes'),
                        trans('admin_labels.appearance'),
                        trans('admin_labels.no_appearance'),
                        trans('admin_labels.sum_cash'),
                        trans('admin_labels.sum_cashless_payments'),
                        trans('admin_labels.sum_payment_to_ca'),
                        trans('admin_labels.sum_online_pay'),
                        trans('admin_labels.sum'),
                        trans('admin_labels.tours'),
                    ];
                }

                $response[$busName][] = [
                    $clients->status,
                    $clients->name,
                    $clients->number,
                    $clients->company->name,
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                ];

                foreach ($clients->routes as $route) {
                    $orders = $route->tours->where('status', \App\Models\Tour::STATUS_COMPLETED)->pluck('orders')
                    ->collapse()->where('status', \App\Models\Order::STATUS_ACTIVE) ?? 0;
                    $cashpayment = $orders->whereIn('type_pay', ['cash-payment', ''])->pluck('orderPlaces')->collapse()
                    ->where('appearance', true)->sum('price') ?? 0;
                    $cashlesspayment = $orders->where('type_pay', 'cashless_payment')->pluck('orderPlaces')
                    ->collapse()->where('appearance', true)->sum('price') ?? 0;
                    $checkingaccount = $orders->where('type_pay', 'checking_account')->pluck('orderPlaces')
                    ->collapse()->where('appearance', true)->sum('price') ?? 0;
                    $success = $orders->where('type_pay', 'success')->pluck('orderPlaces')->collapse()->where('appearance', true)->sum('price') ?? 0;

                    $priceTour = $orders->pluck('orderPlaces')->collapse()->where('appearance', true)->sum('price') ?? 0;
                    $countTour = $route->tours->count() ?? 0;
                    $countAppearance = $orders->sum('count_places_appearance') ?? 0;
                    $countNoAppearance = $orders->sum('count_places_no_appearance') ?? 0;

                    $countWorkDay = $route->tours->groupBy(function ($item) {
                        return $item->date_start->format('d-m-Y');
                    })->count() ?? 0;
                    $trCountWorkDay += $countWorkDay;

                    $response[$busName][] = [
                        '',
                        '',
                        '',
                        '',
                        $route->name,
                        intval($countAppearance),
                        intval($countNoAppearance),
                        intval($cashpayment),
                        intval($cashlesspayment),
                        intval($checkingaccount),
                        intval($success),
                        intval($priceTour),
                        $countTour,
                    ];

                    $trCountTour += $countTour;
                    $trPrice += $priceTour;
                    $trPrice_cashpayment += $cashpayment;
                    $trPrice_cashlesspayment += $cashlesspayment;
                    $trPrice_checkingaccount += $checkingaccount;
                    $trPrice_success += $success;
                    $trAppearance += $countAppearance;
                    $trNoAppearance += $countNoAppearance;
                }

                $response[$busName][] = [
                    '',
                    '',
                    '',
                    '',
                    trans('admin.buses.total'),
                    $trAppearance,
                    intval($trNoAppearance),
                    intval($trPrice_cashpayment),
                    intval($trPrice_cashlesspayment),
                    intval($trPrice_checkingaccount),
                    intval($trPrice_success),
                    intval($trPrice),
                    $trCountTour,
                ];

                $resultTour += $trCountTour;
                $resultWorkDay += $trCountWorkDay;
                $resultAppearance += $trAppearance;
                $resultNoAppearance += $trNoAppearance;
                $resultPrice += $trPrice;

                $resultcashpayment += $trPrice_cashpayment;
                $resultcashlesspayment += $trPrice_cashlesspayment;
                $resultcheckingaccount += $trPrice_checkingaccount;
                $resultsuccess += $trPrice_success;
            }


            $response[$busName][] = [
                '',
                '',
                '',
                '',
                trans('admin.buses.total'),
                intval($resultAppearance),
                intval($resultNoAppearance),
                intval($resultcashpayment),
                intval($resultcashlesspayment),
                intval($resultcheckingaccount),
                intval($resultsuccess),
                intval($resultPrice),
                $resultTour,
            ];

            \Excel::create('buses', function ($excel) use ($response) {
                foreach ($response as $key => $route) {

                    $key = substr($key, 0, 31);

                    $excel->sheet($key, function ($sheet) use ($route) {
                        $sheet->fromArray($route, null, 'A1', true, false);
                    });
                }
            })->download('xlsx');
        }

        if (request()->ajax() && !request('_pjax')) {
            return response([
                'view' => view('admin.' . $this->entity . '.statics.table', compact('buses') + ['entity' => $this->entity])->render(),
            ])->header('Cache-Control', 'no-cache, no-store');
        }
        return $this->responseSuccess(['message' => 'Автобусы выгружаются']);
    }

    public function selectTemplates()
    {
        if ($places = request('val')) {
            $templates = $this->select->templates($places);
            $template = null;
            if ($templates->count() > 1) {
                return $this->responseSuccess(['html' => view('admin.buses.edit.select-templates', compact('templates', 'template'))->render()]);
            } else {
                return $this->responseError(['message' => trans('validation.index.custom.no_template_places', ['places' => $places])]);
            }
        }
    }

    public function getTemplateCountPlaces()
    {
        if ($id = request('val')) {
            $template = Template::findOrFail($id);
            return $this->responseSuccess([
                'val' => $template->count_places,
                'view' => view('admin.buses.templates.partials.template', compact('template'))->render(),
            ]);
        }
    }

    protected function ajaxView($buses)
    {
        return response([
            'view' => view('admin.' . $this->entity . '.index.table', compact('buses') + ['entity' => $this->entity])->render(),
            'pagination' => view('admin.partials.pagination', ['paginator' => $buses])->render(),
        ])->header('Cache-Control', 'no-cache, no-store');
    }

    public function showPopup(Bus $bus)
    {
        return ['html' => view('admin.buses.showPopup.bus', compact('bus'))->render()];
    }

    public function print_page_template_excel()
    {
        \Excel::create("[шаблон для импорта автобусов] ", function ($excel) {
            $excel->sheet('Автобусы', function ($sheet) {

                $places[] = [
                    '№' => '',
                    'Название автобуса' => '',
                    'Название для смс' => '',
                    'гос. Номер' => '',
                    'Компания' => '',
                    'Название шаблона' => '',
                ];
                $sheet->fromArray($places);
            });
        })->export('xlsx');
    }

    public function setBusPopup(Bus $bus)
    {
        $departments = Department::all();
        $checked = $bus->departments()->get()->pluck('id', 'id');
        return ['html' => view('admin.buses.popups.content',
            compact('departments', 'checked', 'bus') + ['entity' => $this->entity])->render()];
    }

    public function setBusDepartment(Bus $bus)
    {
        $departments = request('departments');
        $bus->departments()->detach();
        if($departments) {
            foreach($departments as $department) {
                $model = Department::find($department);
                $model->buses()->syncWithoutDetaching($bus->id);
            }
        }

        return $this->responseSuccess();
    }

    public function setUsersPopup(Bus $bus)
    {
        $users = $bus->company->users;
        $drivers = $bus->company->drivers;

        $busUsers = $bus->bus_users;
        $busDrivers = $bus->bus_drivers;
        return ['html' => view('admin.buses.popups.users',
            compact('users', 'drivers', 'busUsers', 'busDrivers', 'bus') + ['entity' => $this->entity])->render()];
    }

    public function setBusUsers(Bus $bus)
    {
        $users = request()->get('users');
        $drivers = request()->get('drivers');
        $bus->bus_drivers()->sync($drivers);
        $bus->bus_users()->sync($users);

        return $this->responseSuccess();
    }

}
