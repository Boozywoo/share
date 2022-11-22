<?php

namespace App\Http\Controllers\Admin;

//use App\Http\Requests\Admin\DiagnosticCardTemplateItemRequest;
use App\Http\Controllers\Controller;
use App\Models\DiagnosticCardTemplate;
use App\Models\ReviewActTemplate;
use App\Repositories\SelectRepository;
use Illuminate\Http\Request;

class DiagnosticCardTemplateItemController extends Controller
{
    protected $entity = 'settings.exploitation.diagnostic.items';

    public function __construct(SelectRepository $selectRepository)
    {
        $this->select = $selectRepository;
    }

    public function index(DiagnosticCardTemplate $diagnosticCardTemplate)
    {
        $items = $diagnosticCardTemplate->items()
            //->latest()
            ->orderBy('updated_at')
            ->paginate();
        if (request()->ajax() && !request('_pjax')) return $this->ajaxView($items);
        return view('admin.' . $this->entity . '.index', compact('items', 'diagnosticCardTemplate') + ['entity' => $this->entity]);
    }

    public function create(DiagnosticCardTemplate $diagnosticCardTemplate)
    {

        // все акты осмотра, не связанные ни с какой диагностической картой
        $freeActTemplates = ReviewActTemplate::where('company_id', $diagnosticCardTemplate->company_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get()
            ->pluck('name', 'id');
        $freeActTemplates->prepend('- ' . trans('admin_labels.not_selected') . ' -', '');
            

        return view('admin.' . $this->entity . '.edit', compact('diagnosticCardTemplate', 'freeActTemplates') + ['entity' => $this->entity]);
    }
    
    public function free(DiagnosticCardTemplate $diagnosticCardTemplate, ReviewActTemplate $reviewActTemplate)
    {
        $diagnosticCardTemplate->items()->detach($reviewActTemplate->id);
        return $this->responseSuccess();
    }

    public function store(Request $request, DiagnosticCardTemplate $diagnosticCardTemplate)
    {
        // выбранный шаблон акта осмотра приписываем к шаблону диагностической карты
        $diagnosticCardTemplate->items()->attach([request('review_act_template_id')]);

        return $this->responseSuccess();
    }



}
