<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\AgreementRequest;
use App\Models\Agreement;
use App\Models\BusType;
use App\Models\Tariff;
use App\Repositories\SelectRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AgreementController extends Controller
{
    protected $entity = 'agreements';

    protected $select;

    public function __construct(SelectRepository $selectRepository)
    {
        $this->select = $selectRepository;
    }

    public function index()
    {
        $agreements = Agreement::filter(request()->all())->orderBy('date_end')->paginate();
        if (request()->ajax() && !request('_pjax')) return $this->ajaxView($agreements);
        return view('admin.' . $this->entity . '.index', compact('agreements') + ['entity' => $this->entity]);
    }

    public function create()
    {
        $agreement = new Agreement();
        $tariffs = $this->select->tariffs();
        $agreementTariffs = [];
        $companyServices = $this->select->companyCarriers();
        $companyCustomers = $this->select->companyCustomers();
        return view('admin.' . $this->entity . '.edit', compact('agreement', 'agreementTariffs', 'companyCustomers', 'companyServices', 'tariffs') + ['entity' => $this->entity]);
    }

    public function edit(Agreement $agreement)
    {
        $tariffs = $this->select->tariffs();
        $agreementTariffs = $agreement->tariffs->count() ? $agreement->tariffs->pluck('id')->toArray() : [];
        $companyServices = $this->select->companyCarriers();
        $companyCustomers = $this->select->companyCustomers();
        return view('admin.' . $this->entity . '.edit', compact('agreement', 'companyServices', 'companyCustomers', 'tariffs', 'agreementTariffs') + ['entity' => $this->entity]);
    }

    public function tariffs(Agreement $agreement)
    {
        $filter = request()->all();
        $filter['agreement_id'] = $agreement->id;
        $tariffs = Tariff::filter($filter)->orderBy('type', 'bus_type_id')->paginate();
        $busTypes = BusType::whereIn('id', $tariffs->pluck('bus_type_id', 'bus_type_id')->toArray())->get();
        $busTypes = $busTypes->count() ? $busTypes->pluck('name', 'id')->toArray() : [];
        $addTariffUrl = route('admin.agreements.create.tariff', $agreement);
        if (request()->ajax() && !request('_pjax')) return $this->ajaxViewTariffs($tariffs);
        return view('admin.tariffs.index', compact('tariffs', 'addTariffUrl', 'busTypes') + ['entity' => 'tariffs']);
    }

    public function createTariff(Agreement $agreement)
    {
        $tariff = new Tariff();
        $busTypes = $this->select->busTypes();
        $types = trans('admin.tariffs.types');

        $filter = ['agreement_id' => $agreement->id];
        $filter = empty($busTypes) ? $filter : $filter + ['bus_type_id' => key($busTypes)];
        $filter = empty($types) ? $filter : $filter + ['type' => key($types)];

        $tariffMax = Tariff::filter($filter)->orderBy('max', 'desc')->first();
        $minValue = $tariffMax ? $tariffMax->max : 0;
        return view('admin.tariffs.edit', compact('tariff', 'busTypes', 'agreement', 'types', 'minValue')
            + ['entity' => 'tariffs', 'maxReadonly' => false]);
    }

    public function store(AgreementRequest $request)
    {
        $data = request()->all();
        $data['date'] = $data['date'] ? Carbon::createFromFormat('d.m.Y', $data['date']) : null;
        $data['date_start'] = $data['date_start'] ? Carbon::createFromFormat('d.m.Y', $data['date_start']) : null;
        $data['date_end'] = $data['date_end'] ? Carbon::createFromFormat('d.m.Y', $data['date_end']) : null;
        unset($data['tariffs']);

        if ($id = request('id')) {
            $agreement = Agreement::find($id);
            $agreement->update($data);
        } else {
            $agreement = Agreement::create($data);
        }
        if ($request->has('tariffs')) {
            $agreement->tariffs()->sync($request->get('tariffs'));
        }

        return $this->responseSuccess();
    }

    protected function ajaxView($agreements)
    {
        return response([
            'view' => view('admin.' . $this->entity . '.index.table', compact('agreements') + ['entity' => $this->entity])->render(),
            'pagination' => view('admin.partials.pagination', ['paginator' => $agreements])->render(),
        ])->header('Cache-Control', 'no-cache, no-store');
    }

    protected function ajaxViewTariffs($tariffs)
    {
        return response([
            'view' => view('admin.tariffs.index.table', compact('tariffs') + ['entity' => 'tariffs'])->render(),
            'pagination' => view('admin.partials.pagination', ['paginator' => $tariffs])->render(),
        ])->header('Cache-Control', 'no-cache, no-store');
    }

    public function showPopup(Agreement $agreement)
    {
        $tariffs = $this->select->tariffs();
        $agreementTariffs = $tariffs->keys()->toArray();
        $companyServices = $this->select->companyCarriers();
        $companyCustomers = $this->select->companyCustomers();
        return ['html' => view('admin.agreements.popups.edit.content', compact('agreement', 'tariffs', 'agreementTariffs', 'companyCustomers', 'companyServices') + ['entity' => $this->entity])->render()];
    }

    public function delete(Agreement $agreement)
    {
        $agreement->delete();
        return $this->responseSuccess();
    }
}
