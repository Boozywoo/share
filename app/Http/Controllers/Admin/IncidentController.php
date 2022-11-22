<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\IncidentRequest;
use App\Models\Department;
use App\Models\Incident;
use App\Models\IncidentTemplate;
use App\Repositories\SelectRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IncidentController extends Controller
{
    protected $entity = 'incidents';
    protected $select;

    public function __construct(SelectRepository $selectRepository)
    {
        $this->select = $selectRepository;
    }

    public function index()
    {

        $incidents = Incident::select('*')->latest()->paginate(20);
        return view('admin.' . $this->entity . '.index', compact('incidents') + ['entity' => $this->entity]);
    }

    public function create()
    {
        $incident = new Incident();
        $user = auth()->user();
        $company = $user->company->id;
        $templates = IncidentTemplate::isStatus(IncidentTemplate::STATUS_TRUE)->get()->pluck('name', 'id')->prepend(trans('admin_labels.choose_template'), 0);
        $departments = $this->select->departments($user->company_id)->prepend('- '.trans('admin_labels.departments').' -', 0);

        return view('admin.' . $this->entity . '.edit', compact('incident', 'company', 'templates', 'departments')
            + ['entity' => $this->entity, 'user_id' => $user->id]);
    }

    public function edit(Incident $incident)
    {
        $user = auth()->user();
        $company = $user->company->id;
        $templates = IncidentTemplate::isStatus(IncidentTemplate::STATUS_TRUE)->get()->pluck('name', 'id')->prepend(trans('admin_labels.choose_template'), 0);
        $departments = $this->select->departments($user->company_id)->prepend(trans('admin_labels.departments'), 0);

        return view('admin.' . $this->entity . '.edit', compact('incident', 'company', 'templates', 'departments')
            + ['entity' => $this->entity, 'user_id' => $user->id]);
    }

    public function store(IncidentRequest $request){

        if ($id = request('id')) {
            $incident = Incident::find($id);
            $incident->update(request()->all());
            $incident->syncImages(\request('images'));
        } else {
            $incident = Incident::create(request()->all());
            $incident->syncImages(\request('images'));
        }

        return $this->responseSuccess();


    }
}
