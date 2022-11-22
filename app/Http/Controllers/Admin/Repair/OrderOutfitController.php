<?php

namespace App\Http\Controllers\Admin\Repair;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderOutfitRequest;
use App\Http\Requests\Admin\VariablesRequest;
use App\Models\CarBreakages;
use App\Models\Repair;
use App\Models\RepairOrderOutfit;

class OrderOutfitController extends Controller
{
    private $entity = 'repair_orders.order_outfits';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Repair $repairOrder)
    {
        if ($repairOrder->order_outfit) {
            return back();
        }

        $orderOutfit = new RepairOrderOutfit();
        $carBreakages = CarBreakages::whereNull('parent_id')->with('childs')->get();
        return view('admin.' . $this->entity . '.create', compact('repairOrder', 'orderOutfit', 'carBreakages') + ['entity' => $this->entity]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderOutfitRequest $request, VariablesRequest $variablesRequest, Repair $repairOrder)
    {
        $data = request()->all();
        $data['creator_id'] = auth()->id();
//        dd($data);
        try {
            $orderOutfit = $repairOrder->order_outfit()->create($data);
            $orderOutfit->bus_variable()->create($variablesRequest->only(['fuel', 'odometer', 'bus_id']));
            $orderOutfit->breakages()->sync(json_decode(request()->get('breakages')));
            $repairOrder->update(['status' => Repair::STATUS_REPAIR]);

            return $this->responseSuccess(['redirect' => route('admin.repair_orders.show', $repairOrder)]);

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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Repair $repairOrder, RepairOrderOutfit $orderOutfit)
    {
        $carBreakages = CarBreakages::whereNull('parent_id')->with('childs')->get();

        return view('admin.' . $this->entity . '.create', compact('repairOrder', 'orderOutfit', 'carBreakages') + ['entity' => $this->entity]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(OrderOutfitRequest $request, Repair $repairOrder, RepairOrderOutfit $orderOutfit)
    {
        $data = request()->all();
        unset($data['odometer']);
        unset($data['fuel']);
        try {
            $orderOutfit->update($data);
            $breakages = json_decode(request()->get('breakages'));
            $breakages = array_map(function ($val) {
                return (int)$val;
            }, $breakages);
            $orderOutfit->breakages()->sync($breakages);

            return $this->responseSuccess(['redirect' => route('admin.repair_orders.show', $repairOrder)]);
        } catch (\Exception $exception) {
            return $this->responseError(['message' => $exception->getMessage()]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
