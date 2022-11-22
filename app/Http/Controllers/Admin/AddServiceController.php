<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AddServiceRequest;
use App\Models\AddService;
use App\Repositories\SelectRepository;

class AddServiceController extends Controller
{
    protected $entity = 'settings.add_services';
    protected $select;

    public function __construct(SelectRepository $selectRepository)
    {
        $this->select = $selectRepository;
    }

    public function index()
    {
        $dop_services = AddService::filter(request()->all())->latest()->paginate();
        if (request()->ajax() && !request('_pjax')) return $this->ajaxView($dop_services);
        return view('admin.settings.statuses.index', ['statuses' => $dop_services, 'entity' => $this->entity, 'view' => 'settings.statuses']);
    }

    public function create()
    {
        $service = new AddService();
        $statusRoutes = [];
        $routes = $this->select->routes(auth()->id());
        return view('admin.settings.add_services.edit', compact('routes', 'statusRoutes', 'service') + ['entity' => $this->entity]);
    }

    public function edit(AddService $service)
    {
        $routes = $this->select->routes(auth()->id());
        $statusRoutes = $service->routes->count() ? $service->routes->pluck('id')->toArray() : [];
        return view('admin.settings.add_services.edit', compact('routes', 'statusRoutes', 'service') + ['entity' => $this->entity]);
    }

    public function store(AddServiceRequest $request)
    {
        if ($id = request('id')) {
            $service = AddService::find($id);
            $service->update(request()->all());
        } else {
            $service = AddService::create(request()->all());
            $service->update(['status' => AddService::STATUS_ACTIVE]);
        }

        if ($request->has('routes')) {
            $service->routes()->sync($request->get('routes'));
        }

        return $this->responseSuccess();
    }

    protected function ajaxView($statuses)
    {
        return response([
            'view' => view('admin.settings.statuses.index.table', compact('statuses') + ['entity' => $this->entity, 'view' => 'settings.statuses'])->render(),
            'pagination' => view('admin.partials.pagination', ['paginator' => $statuses])->render(),
        ])->header('Cache-Control', 'no-cache, no-store');
    }
}