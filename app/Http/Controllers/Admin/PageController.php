<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PageRequest;
use App\Models\Page;

class PageController extends Controller
{
    protected $entity = 'pages';

    public function index()
    {
        $pages = Page::filter(request()->all())->latest()->paginate();
        if(request()->ajax() && !request('_pjax')) return $this->ajaxView($pages);
        return view('admin.' . $this->entity . '.index', compact('pages') + ['entity' => $this->entity]);
    }

    public function create()
    {
        $page = new Page();
        return view('admin.' . $this->entity . '.edit', compact('page') + ['entity' => $this->entity]);
    }

    public function edit(Page $page)
    {
        return view('admin.' . $this->entity . '.edit', compact('page') + ['entity' => $this->entity]);
    }

    public function store(PageRequest $request)
    {
        if ($id = request('id')) {
            $page = Page::find($id);
            $page->update(request()->all());
        } else {
            Page::create(request()->all());
        }

        return $this->responseSuccess();
    }

    public function delete(Page $page)
    {
        $page->delete();
        return $this->responseSuccess();
    }

    protected function ajaxView($pages)
    {
        return response([
            'view' => view('admin.' . $this->entity . '.index.table', compact('pages') + ['entity' => $this->entity])->render(),
            'pagination' => view('admin.partials.pagination', ['paginator' => $pages])->render(),
        ])->header('Cache-Control', 'no-cache, no-store');
    }
}