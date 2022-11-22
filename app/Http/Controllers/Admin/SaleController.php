<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaleRequest;
use App\Models\Sale;
use App\Repositories\SelectRepository;
use Carbon\Carbon;

class SaleController extends Controller
{
	protected $entity = 'settings.sales';
	protected $select;

	public function index()
	{
		$sales = Sale::filter(request()->all())->latest()->paginate();
		if (request()->ajax() && !request('_pjax')) return $this->ajaxView($sales);
		return view('admin.' . $this->entity . '.index', compact('sales') + ['entity' => $this->entity]);
	}

	public function create()
	{
		$sale = new Sale();

        return view('admin.' . $this->entity . '.edit', compact('sale') + ['entity' => $this->entity]);
	}

	public function edit(Sale $sale)
	{
        return view('admin.' . $this->entity . '.edit', compact('sale') + ['entity' => $this->entity]);
	}

	public function store(SaleRequest $request)
	{
		$data = request()->all();
		$data['date_start'] = Carbon::createFromFormat('d.m.Y', $data['date_start']);
		$data['date_finish'] = Carbon::createFromFormat('d.m.Y', $data['date_finish']);
        $data['percent'] = 0;
		if ($id = request('id')) {
			$sale = Sale::find($id);
			$sale->update($data);
		} else {
			$sale = Sale::create($data);
		}

		return $this->responseSuccess();
	}

	protected function ajaxView($sales)
	{
		return response([
			'view' => view('admin.' . $this->entity . '.index.table', compact('sales') + ['entity' => $this->entity])->render(),
			'pagination' => view('admin.partials.pagination', ['paginator' => $sales])->render(),
		])->header('Cache-Control', 'no-cache, no-store');
	}
}
