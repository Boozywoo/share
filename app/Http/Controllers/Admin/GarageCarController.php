<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VariablesRequest;
use App\Models\Bus;
use App\Models\Department;
use App\Models\UserTakenBus;
use App\Services\GarageArea\GarageCarService;
use Illuminate\Http\Request;

class GarageCarController extends Controller
{
    protected $entity = 'garage.cars';
    protected $service;


    public function __construct(GarageCarService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $departments = Department::with('buses')->whereHas('buses')->get()->pluck('name', 'id');

        $cars = $this->service->listCars(request()->all())->paginate(15);

        foreach ($cars as $k => $car) {
            $cars[$k]->km = 0;
            $cars[$k]->fuel = 0;
            if ($variables = $car->getLastVariables()) {
                $cars[$k]->odometer = $variables->odometer;
                $cars[$k]->fuel = $variables->fuel;
            }
        }
        $user = auth()->user();
        $myCars = $user->buses ? $user->buses->pluck('id') : [];
        if (request()->ajax() && !request('_pjax')) return $this->ajaxView($cars, $myCars);

        return view('admin.' . $this->entity . '.index', compact('cars', 'myCars','departments') + ['entity' => $this->entity]);
    }

    public function takeCar(Bus $bus)
    {
        $userTakenBus = new UserTakenBus();
        $conditions = [
            '1' => trans('admin.' . $this->entity . '.condition_ok'),
            '0' => trans('admin.' . $this->entity . '.condition_not_ok'),
        ];
        return view('admin.' . $this->entity . '.edit', compact('userTakenBus', 'bus', 'conditions') + ['entity' => $this->entity]);

    }

    public function store(VariablesRequest $request)
    {

        // Проверяем можно ли взять это авто
        if ($request->has('bus_id') && !$this->service->checkCanBeTakenCar($request->get('bus_id'))) {
            return $this->responseError(['message' => "Car can't taken."]);
        }

        // Проверяем может ли взять это авто это пользователь
        if (!$this->service->checkCanBeUserTakenCar(\Auth::id())) {
            return $this->responseError(['message' => "User can't take a car."]);
        }

        // Проверяем наличие дефолтного шалона диагностической карты в авто если с ним все хорошо
        $bus = Bus::find($request->get('bus_id'));
        if (($request->has('condition') && $request->get('condition') == 1) && empty($bus->diagnostic_card_template_id)) {
            return $this->responseError(['message' => __('messages.admin.garage_area.select_the_default_diagnostic_card_template_for_this_car')]);
        }

        // Берем авто
        $result = $this->service->takeCar($request);

        if (!$result) {
            return $this->responseError(['message' => "Error"]);
        }
        $diagnosticCard = !empty($result['diagnostic_card']) ? $result['diagnostic_card'] : null;
        $userTakenBus = !empty($result['user_taken_bus']) ? $result['user_taken_bus'] : null;


        if ($request->get('condition') == 1) {
            return $this->responseSuccess(['link' => route('admin.' . $this->entity . '.taken', ['user_taken_bus_id' => $userTakenBus->id])]);
        } else {
            return $this->responseSuccess(['link' => route('admin.buses.diagnostic_cards.edit',
                [
                    'bus_id' => $request->get('bus_id'),
                    'diagnostic_card_id' => $diagnosticCard->id,
                    'user_taken_bus_id' => $userTakenBus->id,
                    'fuel' => $request->fuel,
                    'odometer' => $request->odometer,
                ])]);
        }

    }

    public function put(VariablesRequest $request)
    {
        $bus = Bus::find($request->get('bus_id'));
        $userTakenBus = $bus->user_taken()->whereStatus(UserTakenBus::STATUS_TAKEN)->latest()->first();

        $result = $this->service->putCar($userTakenBus, $bus, $request);

        if (!$result) {
            return $this->responseError(['message' => "Error"]);
        }
        $diagnosticCard = !empty($result['diagnostic_card']) ? $result['diagnostic_card'] : null;
        $userTakenBus = !empty($result['user_taken_bus']) ? $result['user_taken_bus'] : null;


        if ($request->get('condition') == 1) {
            return $this->responseSuccess(['link' => route('admin.' . $this->entity . '.taken', ['user_taken_bus_id' => $userTakenBus->id])]);
        } else {
            return $this->responseSuccess(['link' => route('admin.buses.diagnostic_cards.edit',
                [
                    'bus_id' => $request->get('bus_id'),
                    'diagnostic_card_id' => $diagnosticCard->id,
                    'user_taken_bus_id' => $userTakenBus->id,
                    'fuel' => $request->fuel,
                    'odometer' => $request->odometer,
                ])]);
        }
    }

    public function carTaken(Request $request)
    {
        $userTakenBus = UserTakenBus::find($request->get('user_taken_bus_id'));
        return view('admin.' . $this->entity . '.taken', compact('userTakenBus') + ['entity' => $this->entity]);
    }

    protected function ajaxView($cars, $myCars)
    {

        return response([
            'view' => view('admin.' . $this->entity . '.index.table', compact('cars', 'myCars') + ['entity' => $this->entity])->render(),
            'pagination' => view('admin.partials.pagination', ['paginator' => $cars])->render(),

        ])->header('Cache-Control', 'no-cache, no-store');
    }

}
