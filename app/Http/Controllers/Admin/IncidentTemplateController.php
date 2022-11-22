<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\IncidentTemplateRequest;
use App\Models\IncidentTemplate;
use App\Repositories\SelectRepository;

class IncidentTemplateController extends Controller
{
    protected $entity = 'settings.exploitation.incident';

    public function __construct(SelectRepository $selectRepository)
    {
        $this->select = $selectRepository;
    }

    public function index()
    {
        $incident_templates = IncidentTemplate::select('*')
            ->latest()
            ->get();
        return view('admin.' . $this->entity . '.index', compact('incident_templates') + ['entity' => $this->entity]);

    }

    public function create()
    {
        $incident_template = new IncidentTemplate();
        $company_id = auth()->user()->company_id ? auth()->user()->company_id : 0;
        return view('admin.' . $this->entity . '.edit', compact('incident_template', 'company_id') + ['entity' => $this->entity]);
    }

    public function edit(IncidentTemplate $incident_template)
    {
        return view('admin.' . $this->entity . '.edit', compact('incident_template') + ['entity' => $this->entity]);
    }

    public function store(IncidentTemplateRequest $request)
    {
        if ($id = request('id')) {
            $incident_template = IncidentTemplate::find($id);
            $incident_template->update(request()->all());
        } else {
            $incident_template = IncidentTemplate::create(request()->all());
        }

        return $this->responseSuccess();
    }

}
