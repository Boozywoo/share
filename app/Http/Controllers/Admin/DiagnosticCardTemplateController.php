<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DiagnosticCardTemplateRequest;
use App\Models\DiagnosticCardTemplate;
use App\Repositories\SelectRepository;

class DiagnosticCardTemplateController extends Controller
{
    protected $entity = 'settings.exploitation.diagnostic';

    public function __construct(SelectRepository $selectRepository, DiagnosticCardTemplate $cardTemplate)
    {
        $this->select = $selectRepository;
        $this->template = $cardTemplate;
    }

    public function index()
    {
        $this->entity= 'settings.exploitation';
        $card_templates = DiagnosticCardTemplate::select('*')
            ->latest()
            ->get();

        return view('admin.' . $this->entity . '.diagnostic.index', compact('card_templates') + ['entity' => $this->entity]);

    }

    public function create()
    {
        $diagnostic_card_template = new DiagnosticCardTemplate();
        return view('admin.' . $this->entity . '.edit', compact('diagnostic_card_template') + ['entity' => $this->entity]);
    }

    public function edit(DiagnosticCardTemplate $diagnosticCardTemplate)
    {
        return view('admin.' . $this->entity . '.edit', compact('diagnosticCardTemplate') + ['entity' => $this->entity]);
    }

    public function store(DiagnosticCardTemplateRequest $request)
    {
        if ($id = request('id')) {
            $diagnostic_card_template = DiagnosticCardTemplate::find($id);
            $diagnostic_card_template->update(request()->all());
        } else {
            $user = auth()->user();
            $user->company->diagnostic_card_templates()->create(request()->all());
        }
        return $this->responseSuccess();
    }

}
