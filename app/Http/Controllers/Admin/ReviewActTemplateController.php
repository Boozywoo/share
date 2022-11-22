<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ReviewActTemplateRequest;
use App\Models\DiagnosticCardTemplate;
use App\Models\ReviewActTemplate;
use App\Repositories\SelectRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewActTemplateController extends Controller
{

    protected $entity = 'settings.exploitation.review';

    public function __construct(SelectRepository $selectRepository, ReviewActTemplate $actTemplate)
    {
        $this->select = $selectRepository;
        $this->template = $actTemplate;
    }

    public function index(){
        $this->entity= 'settings.exploitation';
        $act_templates = ReviewActTemplate::select('*')
            ->latest()
            ->get();

        return view('admin.' . $this->entity . '.review.index', compact('act_templates') + ['entity' => $this->entity]);

    }

    public function create()
    {
        $review_act_template = new ReviewActTemplate();
        return view('admin.' . $this->entity . '.edit', compact('review_act_template') + ['entity' => $this->entity]);
    }

    public function edit(ReviewActTemplate $reviewActTemplate)
    {
        return view('admin.' . $this->entity . '.edit', compact('reviewActTemplate') + ['entity' => $this->entity]);
    }

    public function store(ReviewActTemplateRequest $request)
    {
        if ($id = request('id')) {
            $review_act_template = ReviewActTemplate::find($id);
            $review_act_template->update(request()->all());
        } else {
            $user = auth()->user();
            $user->company->review_act_templates()->create(request()->all());
        }

        return $this->responseSuccess();

    }
}
