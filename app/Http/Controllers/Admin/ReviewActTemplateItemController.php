<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ReviewActTemplateItemRequest;
use App\Http\Requests\Admin\ReviewActTemplateRequest;
use App\Models\ReviewActTemplate;
use App\Models\ReviewActTemplateItem;
use App\Repositories\SelectRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewActTemplateItemController extends Controller
{
    protected $entity = 'settings.exploitation.review.items';

    public function __construct(SelectRepository $selectRepository)
    {
        $this->select = $selectRepository;
    }

    public function index(ReviewActTemplate $reviewActTemplate)
    {
        $template = $reviewActTemplate;
        $items = $template->items()
            ->latest()
            ->paginate();
        if (request()->ajax() && !request('_pjax')) return $this->ajaxView($items);
        return view('admin.' . $this->entity . '.index', compact('items', 'template') + ['entity' => $this->entity]);
    }

    public function create(ReviewActTemplate $reviewActTemplate)
    {
        $reviewActTemplateItem = new ReviewActTemplateItem();
        return view('admin.' . $this->entity . '.edit', compact('reviewActTemplateItem', 'reviewActTemplate') + ['entity' => $this->entity]);
    }

    public function edit(ReviewActTemplate $reviewActTemplate, ReviewActTemplateItem $reviewActTemplateItem)
    {
        return view('admin.' . $this->entity . '.edit', compact('reviewActTemplateItem','reviewActTemplate') + ['entity' => $this->entity]);
    }

    public function store(ReviewActTemplateItemRequest $request,ReviewActTemplate $reviewActTemplate)
    {
        if ($id = request('id')) {
            $reviewActTemplateItem = ReviewActTemplateItem::find($id);
            $reviewActTemplateItem->update(request()->all());
        } else {
            $reviewActTemplateItem = ReviewActTemplateItem::create(request()->all());
        }

        return $this->responseSuccess();

    }

}
