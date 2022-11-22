<?php

namespace App\Http\Controllers\Admin\Repair;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RepairRequest;
use App\Models\Bus;
use App\Models\Repair;

class CarRepairController extends Controller
{
    protected $entity = 'buses.repairs';

    public function index()
    {
        $this->authorize('bus-id', request('bus_id'));

        $bus = Bus::find(request('bus_id'));

        $repairs = Repair::filter(request()->all())
			->latest()
			->paginate();
		if (request()->ajax() && !request('_pjax')) return $this->ajaxView($repairs);
		return view('admin.' . $this->entity . '.index', compact('repairs', 'bus') + ['entity' => $this->entity]);
	}

	public function create()
    {
        $repair = new Repair();
        $repair_status = Repair::STATUS_REPAIR;
        return view('admin.' . $this->entity . '.edit', compact('repair', 'repair_status') + ['entity' => $this->entity]);
    }

	public function edit(Repair $repair)
	{
		$this->authorize('bus-id', $repair->bus_id);
		return view('admin.' . $this->entity . '.edit', compact('repair') + ['entity' => $this->entity]);
	}

	public function store(RepairRequest $request)
	{
		$this->authorize('bus-id', request('bus_id'));
		if ($id = request('id')) {
			$repair = Repair::find($id);
			$this->authorize('bus-id', $repair->bus_id);
			$repair->update(request()->all());
		} else {
			$repair = Repair::create(request()->all());
		}

		return $this->responseSuccess();
	}

	protected function ajaxView($repairs)
	{
		return response([
			'view' => view('admin.' . $this->entity . '.index.table', compact('repairs') + ['entity' => $this->entity])->render(),
			'pagination' => view('admin.partials.pagination', ['paginator' => $repairs])->render(),
		])->header('Cache-Control', 'no-cache, no-store');
	}
}
