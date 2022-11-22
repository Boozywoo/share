<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\SelectRepository;
use App\Models\Street;
use App\Models\Route;

class StreetController extends Controller
{
    protected $entity = 'routes.streets';
    protected $select;

    public function __construct(SelectRepository $selectRepository)
    {
        $this->select = $selectRepository;
    }

    public function index()
    {
        $streets = Street::filter(request()->all())
            ->orderBy('name')
            ->paginate();
        if (request()->ajax() && !request('_pjax')) return $this->ajaxView($streets);
        $cities = $this->select->cities(true);
        return view('admin.' . $this->entity . '.index', compact('streets', 'cities') + ['entity' => $this->entity]);
    }

    public function create()
    {
        $street = new Street();
        $cities = $this->select->cities(true);
        return view('admin.' . $this->entity . '.edit', compact('street', 'cities') + ['entity' => $this->entity]);
    }

    public function edit(Street $street)
    {
        $cities = $this->select->cities(true);
        return view('admin.' . $this->entity . '.edit', compact('street', 'cities') + ['entity' => $this->entity]);
    }

    public function store(Request $request)
    {
        if ($id = request('id')) {
            $street = Street::find($id);
            $street->update(request()->all());
        } else {
            $street = Street::create(request()->all());
        }

        return $this->responseSuccess();
    }

    protected function ajaxView($streets)
    {
        return response([
            'view' => view('admin.' . $this->entity . '.index.table', compact('streets') + ['entity' => $this->entity])->render(),
            'pagination' => view('admin.partials.pagination', ['paginator' => $streets])->render(),
        ])->header('Cache-Control', 'no-cache, no-store');
    }

    protected function jsonData()
    {
        if (request('route_id'))
        {
            $route = Route::find(request('route_id'));
            $street_id = $route->stations()->where('city_id', request('city_id'))
                                                    ->get()->pluck('street_id')->toArray();
            return Street::whereIn('id',$street_id)->get();
        }

        return Street::filter(request()->all())->orderBy('name')->get();
    }
}
