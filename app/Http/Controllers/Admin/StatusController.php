<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StatusRequest;
use App\Models\Status;
use App\Repositories\SelectRepository;

class StatusController extends Controller
{
    protected $entity = 'settings.statuses';
    protected $select;

    public function __construct(SelectRepository $selectRepository)
    {
        $this->select = $selectRepository;
    }

    public function index()
    {
        $statuses = Status::filter(request()->all())->latest()->paginate();
        if (request()->ajax() && !request('_pjax')) return $this->ajaxView($statuses);
        return view('admin.' . $this->entity . '.index', compact('statuses') + ['entity' => $this->entity]);
    }

    public function create()
    {
        $status = new Status();
        $statusRoutes = [];
        $routes = $this->select->routes(auth()->id());
        return view('admin.' . $this->entity . '.edit', compact('status', 'routes', 'statusRoutes') + ['entity' => $this->entity]);
    }

    public function edit(Status $status)
    {
        $routes = $this->select->routes(auth()->id());
        $statusRoutes = $status->routes->count() ? $status->routes->pluck('id')->toArray() : [];
        return view('admin.' . $this->entity . '.edit', compact('status', 'routes', 'statusRoutes') + ['entity' => $this->entity]);
    }

    public function store(StatusRequest $request)
    {
        if ($id = request('id')) {
            $status = Status::find($id);
            $status->update(request()->all());
        } else {
            $status = Status::create(request()->all());
        }

        if ($request->has('routes')) {
            $status->routes()->sync($request->get('routes'));
        }
        $status->syncImages(request()->all());

        return $this->responseSuccess();
    }

    protected function ajaxView($statuses)
    {
        return response([
            'view' => view('admin.' . $this->entity . '.index.table', compact('statuses') + ['entity' => $this->entity])->render(),
            'pagination' => view('admin.partials.pagination', ['paginator' => $statuses])->render(),
        ])->header('Cache-Control', 'no-cache, no-store');
    }
}