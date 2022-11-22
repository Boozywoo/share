<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RepairCardTypeItemRequest;
use App\Models\RepairCardItem;
use App\Models\RepairCardTemplate;
use App\Models\RepairCardType;
use Illuminate\Http\Request;

class RepairCardTypeController extends Controller
{

    private $entity = 'settings.exploitation.repair_card_types';

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
    public function create()
    {
        $repairCardType = new RepairCardType();

        return view('admin.settings.exploitation.repair_cards.types.edit', compact('repairCardType') + ['entity' => $this->entity]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        RepairCardType::create($request->all());

        return $this->responseSuccess();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(RepairCardType $repairCardType)
    {
        return view('admin.settings.exploitation.repair_cards.types.items.index', compact('repairCardType') + ['entity' => $this->entity]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(RepairCardType $repairCardType)
    {
        return view('admin.settings.exploitation.repair_cards.types.edit', compact('repairCardType') + ['entity' => $this->entity]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RepairCardType $repairCardType)
    {
        $repairCardType->update($request->all());

        return $this->responseSuccess();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(RepairCardType $repairCardType)
    {
        if($repairCardType->repair_cards()->count() > 0 || $repairCardType->items()->count() > 0){
            return $this->responseError(['message' => __('messages.admin.repair_orders.type_is_used_or_not_empty')]);
        }
        else{
            $repairCardType->delete();
            return $this->responseSuccess();
        }
    }


    public function createItem(RepairCardType $repairCardType)
    {
        $items = $repairCardType->items;
        $all = RepairCardTemplate::whereNull('parent_id')->get();
        $freeCardTemplates = $all->filter(function ($value) use ($items) {
            return !($items->where('id', $value->id)->first());
        })->pluck('name', 'id');
        $freeCardTemplates->prepend('- ' . trans('admin_labels.not_selected') . ' -', '');


        return view('admin.settings.exploitation.repair_cards.types.items.edit', compact('repairCardType', 'freeCardTemplates') + ['entity' => $this->entity]);
    }

    public function storeItem(RepairCardType $repairCardType, RepairCardTypeItemRequest $request)
    {

        $repairCardType->items()->attach($request->get('template_id'));

        return $this->responseSuccess();
    }

    public function deleteItem(RepairCardType $repairCardType, RepairCardTemplate $repairCardTemplate)
    {
        $childsID = $repairCardTemplate->childs->pluck('id')->toArray();
        $repairCardsID = $repairCardType->repair_cards->pluck('id')->toArray();
        $checkInCards = RepairCardItem::whereIn('repair_card_id', $repairCardsID)->whereIn('repair_card_template_id', $childsID)->first();
        if($checkInCards){
            return $this->responseError(['result' => 'warning','message' => __('messages.admin.repair_orders.type_is_used')]);
        }
        else{
            $repairCardType->items()->detach($repairCardTemplate);
            return $this->responseSuccess();
        }

    }
}
