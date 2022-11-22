<?php

namespace App\Http\Controllers\Admin\Repair;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FinishRepairRequest;
use App\Http\Requests\Admin\RepairRequest;
use App\Models\Bus;
use App\Models\Department;
use App\Models\Repair;
use App\Repositories\SelectRepository;
use App\Services\Repair\RepairService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RepairController extends Controller
{
    protected $entity = 'repair_orders';
    protected $select,$repairService;

    public function __construct(SelectRepository $select, RepairService $repairService)
    {
        $this->select = $select;
        $this->repairService = $repairService;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $repairs = Repair::filter(request()->all())->latest()->with('bus', 'order_outfit')->paginate(10);
        $departments = Department::whereCompanyId($user->company_id)->has('buses')->with('buses')->get()->pluck('name', 'id');
        $filterStatuses = Repair::FILTER_STATUSES;
        if (request()->ajax() && !request('_pjax')) return $this->ajaxView($repairs);

        return view('admin.' . $this->entity . '.index', compact('repairs', 'departments', 'filterStatuses') + ['entity' => $this->entity]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = auth()->user();
        $repair = new Repair();
        $departments = Department::whereCompanyId($user->company_id)->has('buses')->with('buses')->get()->pluck('name', 'id');
//        $departments->prepend('- ' . trans('admin_labels.department_id') . ' -', 0);
        $busStatuses = collect(trans('admin.buses.statuses'))->only(Bus::STATUSES_FOR_REPAIR_AREA);

        return view('admin.' . $this->entity . '.create', compact('repair', 'departments', 'busStatuses') + ['entity' => $this->entity]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(RepairRequest $request)
    {
        $data = $request->all();

        $data['status'] = Repair::STATUS_ORDER;
        $data['creator_id'] = auth()->id();
        try {
            $busData = [];
            if ($request->has('bus_status')) {
                $busData['status'] = $request->get('bus_status');
                unset($data['bus_status']);
            }
            $repair = Repair::create($data);

            $bus = Bus::find($repair->bus_id);
//            if ($data['bus_status'] == Bus::STATUS_REPAIR) {
            $bus->update($busData);
//            } elseif ($data['bus_status'] == Bus::STATUS_OF_REPAIR && $bus->status == Bus::STATUS_ACTIVE) {
//                $bus->update($busData);
//            }

            if ($request->has('cards')) {
                $repair->card_templates()->sync(json_decode($request->get('cards')));
            }

            return $this->responseSuccess(['redirect' => route('admin.repair_orders.show', $repair)]);

        } catch (\Exception $exception) {
            return $this->responseError(['message' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Repair $repairOrder)
    {

        $repairSpareParts = $this->repairService->getSpareList($repairOrder);
        $finishedStatuses = $this->repairService->getFinishedStatus($repairSpareParts);

        return view('admin.' . $this->entity . '.show', compact('repairOrder', 'finishedStatuses') + ['entity' => $this->entity]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Repair $repairOrder)
    {
        $user = auth()->user();
        $repair = $repairOrder;
        $departments = Department::whereCompanyId($user->company_id)->has('buses')->with('buses')->get()->pluck('name', 'id');
//        $departments->prepend('- ' . trans('admin_labels.department_id') . ' -', 0);
        $department = $repair->bus->departments()->first();
        $repair->department_id = $department->id ? $department->id : null;
        $buses = $this->select->busesInDepartment($repair->department_id);
        if ($repair->bus->repair_card_template) {
            $repairCardList = $repair->bus->repair_card_template->items;
        } else {
            $repairCardList = [];
        }
//        $buses->prepend('- ' . trans('admin_labels.car_id') . ' -', 0);
        $busStatuses = collect(trans('admin.buses.statuses'))->only(Bus::STATUSES_FOR_REPAIR_AREA);


        return view('admin.' . $this->entity . '.create', compact('repair', 'repairCardList', 'buses', 'departments', 'busStatuses') + ['entity' => $this->entity]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(RepairRequest $request, Repair $repairOrder)
    {
        $data = $request->all();

        try {
            $busData = [];
            if ($request->has('bus_status')) {
                $busData['status'] = $request->get('bus_status');
                unset($data['bus_status']);
            }
            if ($request->has('cards')) {
                $repairOrder->card_templates()->sync(json_decode($request->get('cards')));
            }
            $repairOrder->update($data);
            $bus = Bus::find($repairOrder->bus_id);
//            if ($data['bus_status'] == Bus::STATUS_REPAIR) {
            $bus->update($busData);
//            } elseif ($data['bus_status'] == Bus::STATUS_OF_REPAIR && $bus->status == Bus::STATUS_ACTIVE) {
//                $bus->update($busData);
//            }
            return $this->responseSuccess(['redirect' => route('admin.repair_orders.show', $repairOrder)]);

        } catch (\Exception $exception) {
            return $this->responseError(['message' => $exception->getMessage()]);
        }



    }

    public function complete(Repair $repairOrder)
    {
        $user = auth()->user();
        $repair = $repairOrder;
        $departments = Department::whereCompanyId($user->company_id)->has('buses')->with('buses')->get()->pluck('name', 'id');
        $departments->prepend('- ' . trans('admin_labels.not_selected') . ' -', 0);
        $department = $repair->bus->departments()->first();
        $repair->department_id = $department->id ? $department->id : null;
        $buses = $this->select->busesInDepartment($repair->department_id);
        $buses->prepend('- ' . trans('admin_labels.not_selected') . ' -', 0);

        return view('admin.' . $this->entity . '.complete', compact('repair', 'buses', 'departments') + ['entity' => $this->entity]);
    }

    public function finish(Repair $repairOrder, FinishRepairRequest $request)
    {

        if ($request->has('status')) {
            $status = $request->get('status');
            $data['status'] = $status;
            try {
                if (in_array($status, [Repair::STATUS_OF_REPAIR, Repair::STATUS_WITHOUT_REPAIR])) {
                    $data['date_end'] = Carbon::now();
                }
                $repairOrder->update($data);
                if ($request->has('bus_status') && !empty($request->get('bus_status'))) {
                    $repairOrder->bus->update(['status' => $request->get('bus_status')]);
                } else {
                    $repairOrder->bus->update(['status' => Bus::STATUS_OF_REPAIR]);
                }

                return $this->responseSuccess(['message' => __('messages.admin.repair_orders.successfully')]);

            } catch (\Exception $exception) {

                return $this->responseError(['message' => $exception->getMessage()]);
            }
        }


        return $this->responseError();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Repair $repairOrder)
    {
        $repairOrder->update(['status' => Repair::STATUS_WITHOUT_REPAIR]);

        return $this->responseSuccess();
    }

    // Возвращает view и автомобили в выбранном отделе, для селектов.
    public function getDepartmentsCarsView(Request $request)
    {
        $buses = [];
        if ($request->has('val') && $id = $request->get('val')) {
            $buses = $this->select->busesInDepartment($id);
//            $buses->prepend('- ' . trans('admin_labels.car_id') . ' -', 0);

            return $this->responseSuccess([
                'val' => $buses,
                'view' => view('admin.' . $this->entity . '.index.cars-select', compact('buses'))->render(),
            ]);
        } else {
            return $this->responseSuccess([
                'val' => $buses,
                'view' => view('admin.' . $this->entity . '.index.cars-select', compact('buses'))->render(),
            ]);
        }

    }

    public function getCarCardsView(Request $request)
    {
        $repairCardList = [];

        if ($request->has('val') && $id = $request->get('val')) {
            $bus = Bus::find($id);
            $repairCardList = $bus->repair_card_template ? $bus->repair_card_template->items : [];

            return $this->responseSuccess([
                'val' => 'success',
                'view' => view('admin.' . $this->entity . '.index.select-cards', compact('repairCardList'))->render(),
            ]);
        } else {
            return $this->responseSuccess([
                'val' => 'success',
                'view' => view('admin.' . $this->entity . '.index.select-cards', compact('repairCardList'))->render(),
            ]);
        }

    }


    protected function ajaxView($repairs)
    {
        return response([
            'view' => view('admin.' . $this->entity . '.index.table', compact('repairs') + ['entity' => $this->entity])->render(),
            'pagination' => view('admin.partials.pagination', ['paginator' => $repairs])->render(),
        ])->header('Cache-Control', 'no-cache, no-store');
    }

}
