<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\TariffRequest;
use App\Models\Tariff;
use App\Models\TariffRate;
use App\Repositories\SelectRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TariffController extends Controller
{
    protected $entity = 'tariffs';
    protected $select;
    protected $selectIndex;

    public function __construct(SelectRepository $selectRepository)
    {
        $this->select = $selectRepository;
    }

    public function index()
    {
        $tariffs = Tariff::filter(request()->all())->latest()->paginate();
        $busTypes = $this->select->busTypes();
        if (request()->ajax() && !request('_pjax')) return $this->ajaxView($tariffs);
        return view('admin.' . $this->entity . '.index', compact('tariffs', 'busTypes') + ['entity' => $this->entity]);
    }

    public function create()
    {
        $routes = NULL;
        $tariff = new Tariff();
        $types = trans('admin.tariffs.types');
        $tariff_directions = trans('admin.tariffs.tariff_directions');
        $busTypes = $this->select->busTypes();
        return view('admin.' . $this->entity . '.edit', compact('tariff', 'busTypes', 'types', 'tariff_directions', 'routes')
            + ['entity' => $this->entity, 'agreement' => null, 'maxReadonly' => false]);
    }

    public function edit(Tariff $tariff)
    {
        $routes = NULL;
        $busTypes = $this->select->busTypes();
        $tariff_directions = trans('admin.tariffs.tariff_directions');
        $types = trans('admin.tariffs.types');
        if($tariff->type == 'route') {
            $routes = $this->select->routes();
        }
        return view('admin.'.$this->entity.'.edit', compact('tariff', 'busTypes', 'types', 'tariff_directions', 'routes')
            + ['entity' => $this->entity]);
    }

    public function delete(Tariff $tariff)
    {
        $tariff->delete();
        return $this->responseSuccess();
    }

    public function store(TariffRequest $request)
    {
        if ($id = request('id')) {
            $tariff = Tariff::find($id);
            $tariff->update(request()->all());
        } else {
            Tariff::create(request()->all());
        }

        return $this->responseSuccess();
    }

    protected function ajaxView($tariffs)
    {
        return response([
            'view' => view('admin.' . $this->entity . '.index.table', compact('tariffs') + ['entity' => $this->entity])->render(),
            'pagination' => view('admin.partials.pagination', ['paginator' => $tariffs])->render(),
        ])->header('Cache-Control', 'no-cache, no-store');
    }

    protected function getMinValue(Request $request)
    {
        $tariff = Tariff::filter(request()->all())->orderBy('max', 'desc')->first();
        return $tariff ? $tariff->max : 0;
    }

    public function rates(Tariff $tariff)
    {
        $filter = request()->all();
        $filter['tariff_id'] = $tariff->id;
        $rates = TariffRate::filter($filter)->orderBy('max')->paginate();
        $addTariffUrl = route('admin.tariffs.create.rate', $tariff);
        if (request()->ajax() && !request('_pjax')) return $this->ajaxViewTariffs($rates);
        return view('admin.tariff_rates.index', compact('rates', 'addTariffUrl', 'tariff') + ['entity' => 'tariff_rates']);
    }

    public function createRate(Tariff $tariff)
    {
        $rate = new TariffRate();

        $filter = ['tariff_id' => $tariff->id];

        $rateMax = TariffRate::filter($filter)->orderBy('max', 'desc')->first();
        $minValue = $rateMax ? $rateMax->max : 0;
        return view('admin.tariff_rates.edit', compact('rate', 'busTypes', 'tariff', 'types', 'minValue')
            + ['entity' => 'tariff_rates', 'maxReadonly' => false]);
    }

    public function getRoutes()
    {
        $routes = $this->select->routes();
        return ['routes' => $routes];
    }
}
