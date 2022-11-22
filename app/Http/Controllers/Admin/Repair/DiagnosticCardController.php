<?php

namespace App\Http\Controllers\Admin\Repair;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RepairCardRequest;
use App\Models\Repair;
use App\Models\RepairCard;
use App\Models\RepairCardItem;
use App\Models\RepairCardType;
use Illuminate\Http\Request;

class DiagnosticCardController extends Controller
{
    private $entity = 'repair_orders.diagnostic_cards';

    public function create(Repair $repairOrder)
    {
        if ($repairOrder->diagnostic_card) {
            return redirect()->route('admin.home');
        }
        $repairCardTypes = RepairCardType::all()->pluck('name', 'id');
        $diagnosticCard = new RepairCard();
        $diagnosticCard->childs = [];
        $repairTemplate = $repairOrder->bus->repair_card_template;
        return view('admin.' . $this->entity . '.edit', compact('repairOrder', 'diagnosticCard','repairTemplate', 'repairCardTypes') + ['entity' => $this->entity]);
    }

    public function store(RepairCardRequest $request, Repair $repairOrder)
    {
        try {
            $diagnosticCard = $repairOrder->diagnostic_card()->create($request->only('repair_card_type_id', 'comment'));
            $this->saveItems($request, $diagnosticCard);
            return $this->responseSuccess(['redirect' => route('admin.repair_orders.show', $repairOrder)]);
        } catch (\Exception $exception) {
            return $this->responseError(['message' => $exception->getMessage()]);
        }

    }


    public function getCardContent(Repair $repairOrder, Request $request)
    {
        if ($request->has('type_id')) {
            $type = RepairCardType::findOrFail($request->get('type_id'));
            $items = $type->items()->with('childs')->get();
            $selectedItems = collect();
            $image = new RepairCardItem();

            if ($request->has('card_id')) {
                $card = RepairCard::find($request->get('card_id'));
                if ($card) {
                    $selectedItems = $card->pivot_items;
                }
            }
            else{
                $card = $repairOrder->diagnostic_card;
                if ($card) {
                    $selectedItems = $card->pivot_items;
                }
            }
            $repairOrder->load('bus');
            $cardTemplates = $repairOrder->card_templates->pluck('id')->toArray();

            return response()->json(['items' => [$items, $selectedItems], 'view' => view('admin.' . $this->entity . '.templates.card-template', compact('items','cardTemplates', 'repairOrder', 'selectedItems', 'image'))->render()]);
        } else {
            return $this->responseError();
        }
    }

    public function edit(Repair $repairOrder, RepairCard $diagnosticCard)
    {
        $repairCardTypes = RepairCardType::all()->pluck('name', 'id');

        $repairTemplate = $diagnosticCard->template;

        return view('admin.' . $this->entity . '.edit', compact('repairOrder', 'repairTemplate','diagnosticCard', 'repairCardTypes') + ['entity' => $this->entity]);

    }

    public function update(RepairCardRequest $request, Repair $repairOrder, RepairCard $diagnosticCard)
    {
        try {
            $diagnosticCard->update($request->only('repair_card_type_id', 'comment'));
            $this->saveItems($request, $diagnosticCard);

            return $this->responseSuccess(['redirect' => route('admin.repair_orders.show', $repairOrder)]);
        } catch (\Exception $exception) {
            return $this->responseError(['message' => $exception->getMessage()]);
        }

    }

    private function saveItems($request, $diagnosticCard)
    {
        $childs_arr = $request->get('childs');
        $childs = $request->only($childs_arr);
        $diagnosticCard->items()->sync($childs);
        $diagnosticCard->items()->get()->map(function ($item) use ($request) {
            RepairCardItem::find($item->pivot->id)->syncImages($request->get('image_' . $item->id));
            return $item;
        });

    }
}
