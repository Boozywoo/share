<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\CarColor;
use App\Models\CustomerCompany;
use App\Models\CustomerDepartment;
use App\Models\CustomerPersonality;
use App\Models\Driver;
use App\Repositories\SelectRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BusController extends Controller
{
    protected $entity = 'dashboards.buses';
    protected $select;


    public function __construct(SelectRepository $selectRepository)
    {
        $this->select = $selectRepository;
    }

    public function index()
    {

        if (request()->ajax() && !request('_pjax')) return $this->renderView('pjax');

        return $this->renderView('index');
    }

    public function filter(Request $request)
    {

        return $this->renderView('filter', null, $request);
    }

    public function updateOne(Bus $bus, Request $request)
    {
        $fields = (new Bus())->getFilterFields();
        $field = $request->has('field') ? $request->get('field') : '';
        $data = $request->only($fields['all']);
        $data = array_filter($data, function ($value) {
            return !empty($value) && $value !== '';
        });
        if (!empty($data['departments']) && is_array($data['departments'])) {
            $bus->departments()->sync($data['departments']);
        }
        if (!empty($data['bus_drivers']) && is_array($data['bus_drivers'])) {
            $bus->bus_drivers()->sync($data['bus_drivers']);
        }
        if (!empty($data['type'])) {
            $data['bus_type_id'] = $data['type'];
            unset($data['type']);
        }
        if (!empty($data['company'])) {
            $data['company_id'] = $data['company'];
            unset($data['company']);
        }

        $result = $bus->update($data);
        $bus->load(['departments', 'company', 'type', 'bus_drivers']);
        $bus = collect($bus);

        return $this->renderView('row', $bus, null, $field);

    }

    protected function ajaxView($buses, $busDrivers, $companies, $types, $allBuses, $selectedFields, $fields, $departments, $colors, $customerDepartments, $customerCompanies, $customerPersonalities, $fieldData)
    {
//        dd(request()->all());
        return response([
            'view' => view('admin.' . $this->entity . '.index.table', compact('buses', 'busDrivers', 'types', 'companies', 'allBuses', 'selectedFields', 'fields', 'departments', 'colors', 'customerCompanies', 'customerPersonalities', 'customerDepartments', 'fieldData') + ['entity' => $this->entity])->render(),
            'filter' => view('admin.' . $this->entity . '.index.filter', compact('buses', 'busDrivers', 'allBuses', 'selectedFields', 'fields', 'departments', 'colors', 'customerCompanies', 'customerPersonalities', 'customerDepartments') + ['entity' => $this->entity])->render(),
            'pagination' => view('admin.partials.pagination', ['paginator' => $buses])->render(),
        ])->header('Cache-Control', 'no-cache, no-store');
    }

    protected function renderView($type = 'index', $bus = null, $request = null, $field = null)
    {
        $user = \Auth::user();
        $company = $user->company;
        $buses = $company->buses()->with(['departments', 'company', 'type', 'bus_drivers'])->filter(request()->get('filter') ?? [])->get();//->paginate(10);//->get();
        $allBuses = $company->buses()->with(['type', 'company'])->get();//$user->company->buses()->with('type', 'company')->get();
        $colors = CarColor::all()->pluck('name', 'slug');
        $customerCompanies = CustomerCompany::all()->pluck('name', 'slug');
        $customerDepartments = CustomerDepartment::all()->pluck('name', 'slug');
        $customerPersonalities = CustomerPersonality::all()->pluck('name', 'slug');
        $departments = $user->company->departments->pluck('name', 'id');
        $companies = $this->select->companies(auth()->id());
        $types = $this->select->busTypes();
        $busDrivers = Driver::where(['company_id' => $user->company_id])->get()->pluck('name', 'id');

        $fields = (new Bus())->getFilterFields();
        if (\request()->has('hide_filter') && \request()->get('hide_filter') == 1) {
            $selectedFields = $fields['all'];
        } else {
            if (\request()->has('fields')) {
                $requestFields = is_array(\request()->get('fields')) ? array_values(\request()->get('fields')) : \request()->get('fields');
                Cache::forever('dashboards_buses_fields_user_' . $user->id, $requestFields);
            }
            $selectedFields = Cache::has('dashboards_buses_fields_user_' . $user->id) ? Cache::get('dashboards_buses_fields_user_' . $user->id, $fields['all']) : $fields['all'];
            if (!is_array($selectedFields) || count($selectedFields) < 1) {
                $selectedFields = $fields['all'];
            }
        }
        $selectedFields = array_unique($selectedFields);
        $fieldData = [];
        $fieldData['busDrivers'] = $busDrivers;
        $fieldData['companies'] = $companies;
        $fieldData['types'] = $types;
        $fieldData['departments'] = $departments;
        $fieldData['colors'] = $colors;
        $fieldData['customerCompanies'] = $customerCompanies;
        $fieldData['customerPersonalities'] = $customerPersonalities;
        $fieldData['customerDepartments'] = $customerDepartments;
        $fieldData['statuses'] = __("admin.buses.statuses");
        $fieldData['location_statuses'] = __("admin.buses.location_statuses");
        $fieldData['tires'] = __("admin.buses.tires");
        if ($type == 'index') {
            return view('admin.' . $this->entity . '.index', compact('buses', 'busDrivers', 'companies', 'types', 'allBuses', 'selectedFields', 'fields', 'departments',
                    'colors', 'customerCompanies', 'customerPersonalities', 'customerDepartments', 'fieldData') + ['entity' => $this->entity]);
        } elseif ($type == 'pjax') {
            return $this->ajaxView($buses, $busDrivers, $companies, $types, $allBuses, $selectedFields, $fields, $departments, $colors, $customerDepartments, $customerCompanies, $customerPersonalities, $fieldData);
        } elseif ($type == 'row') {
            return $this->responseSuccess(['view' => view('admin.dashboards.buses.index.row', compact('buses', 'busDrivers', 'companies', 'types', 'allBuses', 'selectedFields', 'fields', 'departments', 'colors', 'customerCompanies', 'customerPersonalities', 'customerDepartments', 'bus', 'field'))->render()]);
        } elseif ($type == 'filter') {
            return response(['view' => view('admin.' . $this->entity . '.index.filter', compact('request', 'selectedFields', 'departments', 'busDrivers', 'fields', 'allBuses', 'colors', 'customerCompanies', 'customerPersonalities', 'customerDepartments') + ['entity' => $this->entity])->render(),
                'pagination' => view('admin.partials.pagination', ['paginator' => $buses])->render(),
            ])->header('Cache-Control', 'no-cache, no-store');
        } else {
            return view('admin.' . $this->entity . '.index', compact('buses', 'busDrivers', 'companies', 'types', 'allBuses', 'selectedFields', 'fields', 'departments', 'colors', 'customerCompanies', 'customerPersonalities', 'customerDepartments') + ['entity' => $this->entity]);
        }
    }
}
