<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RepairCardTemplateRequest;
use App\Models\RepairCardItem;
use App\Models\RepairCardTemplate;
use App\Models\RepairCardType;

class RepairCardTemplateController extends Controller
{

    private $entity = 'settings.exploitation.repair_cards';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $repairCards = RepairCardTemplate::whereNull('parent_id')->get();
        $repairCardTypes = RepairCardType::all();
        return view('admin.' . $this->entity . '.index', compact('repairCards','repairCardTypes') + ['entity' => $this->entity]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $repairCard = new RepairCardTemplate();

        return view('admin.' . $this->entity . '.edit', compact('repairCard') + ['entity' => $this->entity]);
    }
    public function createItem(RepairCardTemplate $repairCard)
    {
        $repairCardObject = new RepairCardTemplate();

        return view('admin.' . $this->entity . '.items.edit', compact('repairCard','repairCardObject') + ['entity' => $this->entity]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(RepairCardTemplateRequest $request)
    {
        $user = auth()->user();
        $user->company->repair_card_templates()->create($request->all());

        return $this->responseSuccess();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(RepairCardTemplate $repairCard)
    {
        $childs = $repairCard->childs;

        return view('admin.' . $this->entity . '.items.index', compact('repairCard','childs') + ['entity' => $this->entity]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(RepairCardTemplate $repairCard)
    {
        return view('admin.' . $this->entity . '.edit', compact('repairCard') + ['entity' => $this->entity]);
    }
    public function editItem(RepairCardTemplate $repairCard, RepairCardTemplate $item)
    {
        $repairCardObject = $item;
        return view('admin.' . $this->entity . '.items.edit', compact('repairCard','repairCardObject') + ['entity' => $this->entity]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(RepairCardTemplateRequest $request, RepairCardTemplate $repairCard)
    {
        $repairCard->update($request->all());

        return $this->responseSuccess();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(RepairCardTemplate $repairCard)
    {

        if (empty($repairCard->parent_id) && ($repairCard->childs()->count() > 0 || $repairCard->types()->count())) {
            $data['message'] = __('messages.admin.repair_orders.template_not_empty');
            $data['errors']['name'][] = __('messages.admin.repair_orders.template_not_empty');
            return $this->responseError($data);
        }
        if (!empty($repairCard->parent_id)) {
            $count = RepairCardItem::where('repair_card_template_id',$repairCard->id)->count();
            if($count > 0){
                $data['message'] = __('messages.admin.repair_orders.object_used');
                $data['errors']['name'][] = __('messages.admin.repair_orders.object_used');
                return $this->responseError($data);
            }
        }
        $repairCard->delete();

        return $this->responseSuccess();
    }
}
