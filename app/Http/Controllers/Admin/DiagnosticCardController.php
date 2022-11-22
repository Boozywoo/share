<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DiagnosticCardRequest;
use App\Models\Bus;
use App\Models\DiagnosticCard;
use App\Models\DiagnosticCardReviewActTemplateItem;
use App\Models\DiagnosticCardTemplate;
use App\Models\UserTakenBus;
use App\Repositories\SelectRepository;
use App\Services\DiagnosticCard\DiagnosticCardService;
use App\Services\GarageArea\GarageCarService;

// управление диагностическими картами
// каждая диагностическая карта содержит набор актов осмотра, который
// задается шаблоном диагностической карты

class DiagnosticCardController extends Controller
{
    protected $entity = 'buses.diagnostic_cards';

    public function __construct(SelectRepository $selectRepository)
    {
        $this->select = $selectRepository;
    }

    public function index(Bus $bus)
    {

        $diagnosticCards = DiagnosticCard::filter(request()->all())
            ->where('bus_id', $bus->id)
            ->latest()
            ->paginate();
        if (request()->ajax() && !request('_pjax')) return $this->ajaxView($diagnosticCards, $bus);

        return view('admin.' . $this->entity . '.index', compact('diagnosticCards', 'bus') + ['entity' => $this->entity]);
    }

    public function create(Bus $bus)
    {

        $diagnosticCard = new DiagnosticCard();
        $mechanics = $this->select->mechanics(auth()->user()->companyIds);

        $templates = DiagnosticCardTemplate::whereStatus('active')->with('items')->pluck('name', 'id');

        $busTemplate = $bus->diagnostic_card_template;

        return view('admin.' . $this->entity . '.edit', compact('diagnosticCard', 'templates', 'mechanics', 'busTemplate', 'bus') + ['entity' => $this->entity]);
    }


    public function store(DiagnosticCardRequest $request, Bus $bus)
    {
        $service = new DiagnosticCardService();
        try {
            $data = $request->only(['diagnostic_card_template_id', 'notes']);
            $data['user_id'] = \Auth::id();
            $diagnosticCard = $bus->diagnostic_cards()->create($data);

            $service->save($request, $bus, $diagnosticCard);

            if ($userTakenBusId = request()->get('user_taken_bus_id')) {
                $userTakenBus = UserTakenBus::find($userTakenBusId);
                $userTakenBus->is_card = 1;
                $userTakenBus->diagnostic_card_id = $diagnosticCard->id;
                $userTakenBus->save();
                return $this->responseSuccess(['link' => route('admin.garage.cars.taken', ['user_taken_bus_id' => $userTakenBus->id])]);
            }

            return $this->responseSuccess();

        } catch (\Exception $exception) {
            return $this->responseError(['message' => $exception->getMessage()]);
        }

    }

    public function edit(Bus $bus,DiagnosticCard $diagnosticCard)
    {

        $templates = DiagnosticCardTemplate::whereStatus('active')->with('items')->pluck('name', 'id');

        $busTemplate = null;


        return view('admin.' . $this->entity . '.edit', compact('diagnosticCard', 'templates', 'busTemplate', 'bus','selectedItems') + ['entity' => $this->entity]);
    }

    public function update(DiagnosticCardRequest $request, Bus $bus, DiagnosticCard $diagnosticCard)
    {
        $service = new DiagnosticCardService();
        try {
            $data = $request->only(['notes']);
            $diagnosticCard->update($data);

            $service->save($request, $bus, $diagnosticCard);
            if ($userTakenBusId = request()->get('user_taken_bus_id')) {
                $garageCarService = new GarageCarService();
                $userTakenBus = UserTakenBus::find($userTakenBusId);
                if ($userTakenBus->status == UserTakenBus::STATUS_CREATED) {
                    $garageCarService->moveCarToTaken($userTakenBus, $bus);
                }
                if ($userTakenBus->status == UserTakenBus::STATUS_TAKEN) {
                    $garageCarService->moveCarToReturned($userTakenBus, $bus);
                }
                return $this->responseSuccess(['link' => route('admin.garage.cars.taken',
                    [
                        'user_taken_bus_id' => $userTakenBus->id,
                        'diagnostic_card_id' => $diagnosticCard->id,
                    ])]);
            }

            return $this->responseSuccess();

        } catch (\Exception $exception) {
            return $this->responseError(['message' => $exception->getMessage()]);
        }
    }

    public function destroy(Bus $bus, DiagnosticCard $diagnosticCard)
    {
        $service = new DiagnosticCardService();

        $service->delete($diagnosticCard);

        return $this->responseSuccess();
    }


    protected function ajaxView($diagnosticCards, $bus)
    {
        return response([
            'view' => view('admin.' . $this->entity . '.index.table', compact('diagnosticCards', 'bus') + ['entity' => $this->entity])->render(),
            'pagination' => view('admin.partials.pagination', ['paginator' => $diagnosticCards])->render(),
        ])->header('Cache-Control', 'no-cache, no-store');
    }

    public function getItemsOfTemplate(Bus $bus)
    {
        if (\request()->has('template_id')) {
            $reviewActTemplates = DiagnosticCardTemplate::find(\request()->get('template_id'))->items()->with('items')->get();
            $image = new DiagnosticCardReviewActTemplateItem();

            $selectedItems = collect();
            $diagnosticCard = new DiagnosticCard();
            if (\request()->has('card_id')) {
                $diagnosticCard = DiagnosticCard::find(\request()->get('card_id'))->load('bus_variable');
                $selectedItems = $diagnosticCard->items;
            }

            return response()->json(['items' => [$selectedItems], 'view' => view('admin.' . $this->entity . '.edit.card-template', compact('reviewActTemplates', 'selectedItems', 'image', 'bus', 'diagnosticCard'))->render()]);
        }
        return $this->responseError();
    }
}
