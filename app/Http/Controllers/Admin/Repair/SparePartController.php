<?php

namespace App\Http\Controllers\Admin\Repair;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateMassRepairSparePartRequest;
use App\Http\Requests\Admin\CreateRepairSparePartRequest;
use App\Models\Repair;
use App\Models\RepairSparePart;
use App\Models\SparePart;
use App\Services\Repair\RepairService;

class SparePartController extends Controller
{
    private $entity = 'repair_orders.spare_parts';
    private $repairService;

    public function __construct(RepairService $repairService)
    {
        $this->repairService = $repairService;
    }

    public function index(Repair $repairOrder)
    {
        $spareParts = SparePart::whereNull('parent_id')->whereStatus('active')->with('active_childs')->get();
        $newSparePart = new RepairSparePart();
        $repairSpareParts = $this->repairService->getSpareList($repairOrder);
        if (request()->has('status')) {
            $repairSpareParts = $repairSpareParts->filter(function ($group) {
                return $group->first()->status == request()->get('status');
            });
        }

        $cardItems = $repairOrder->diagnostic_card->pivot_items;

        $statuses = RepairSparePart::STATUSES;
        $finishedStatuses = $this->repairService->getFinishedStatus($repairSpareParts);

        return view('admin.' . $this->entity . '.index', compact('spareParts', 'statuses', 'repairSpareParts', 'newSparePart', 'cardItems','repairOrder','finishedStatuses') + ['entity' => $this->entity]);
    }

    public function store(Repair $repairOrder, CreateRepairSparePartRequest $request)
    {
        $data = $request->all();
        unset($data['_token']);
        $data['user_id'] = \Auth::id();
        if (empty($data['status'])) {
            $data['status'] = RepairSparePart::STATUS_NOT_PROCESSED;
            $count = $repairOrder->spare_parts()->where('spare_part_id', $data['spare_part_id'])->count();
            if ($count > 0) {
                return $this->responseError(['message' => __('messages.admin.repair_orders.spare_part_added')]);
            }
        }
        $repairOrder->spare_parts()->create($data);

        return $this->responseSuccess();
    }

    public function storeMass(Repair $repairOrder, CreateMassRepairSparePartRequest $request)
    {
//        dd($request->get('all'));
        if(!$request->has('all')){
            return $this->responseError(['message' => __('messages.admin.repair_orders.choose_parts')]);
        }
        $all = $request->get('all');
        $all = array_map(function ($item) {
            $item['user_id'] = \Auth::id();
            return $item;
        }, $all);
        $repairOrder->spare_parts()->createMany($all);

        return $this->responseSuccess(['message' => __('messages.admin.repair_orders.successfully')]);
    }

    public function destroy(Repair $repairOrder, $sparePart)
    {
        $parts = $repairOrder->spare_parts()->where('spare_part_id', $sparePart)->delete();

        return $this->responseSuccess();
    }

    public function getContent(Repair $repairOrder)
    {
        $spareParts = SparePart::whereNull('parent_id')->whereStatus('active')->with('active_childs')->get();
        $newSparePart = new RepairSparePart();
        $repairSpareParts = $this->repairService->getSpareList($repairOrder);
        if (request()->has('status')) {
            $repairSpareParts = $repairSpareParts->filter(function ($group) {
                return $group->first()->status == request()->get('status');
            });
        }
        $statuses = RepairSparePart::STATUSES;
        $finishedStatuses = $this->repairService->getFinishedStatus($repairSpareParts);

        return view('admin.' . $this->entity . '.templates.parts-template', compact('spareParts', 'statuses', 'repairSpareParts', 'newSparePart', 'repairOrder','finishedStatuses') + ['entity' => $this->entity])->render();

    }

    protected function ajaxView($repairOrder)
    {
        return response([
            'view' => $this->getContent($repairOrder),
        ])->header('Cache-Control', 'no-cache, no-store');
    }



}
