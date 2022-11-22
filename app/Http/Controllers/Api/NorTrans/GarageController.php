<?php

namespace App\Http\Controllers\Api\NorTrans;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCardReviewRequest;
use App\Http\Requests\Admin\VariablesRequest;
use App\Http\Requests\Api\NorTrans\TakeCarFinishRequest;
use App\Models\Bus;
use App\Models\DiagnosticCard;
use App\Models\UserTakenBus;
use App\Services\GarageArea\GarageCarService;
use Illuminate\Http\Request;

class GarageController extends Controller
{
    public function __construct(GarageCarService $service)
    {
        $this->service = $service;
    }

    public function diagnostic_cards($bus, Request $request)
    {
        if ($bus = Bus::find($bus)) {
            $diagnosticCards = $bus->diagnostic_cards()->with(['user_taken_bus','bus_variable','items'])->paginate(10);
            // TODO возврат статуса и количества ошибок
            return $this->responseMobile('success', '', ['diagnostic_cards' => $diagnosticCards]);
        } else {
            return $this->responseMobile('error', __("messages.admin.buses.no_bus"));
        }

    }

    public function take(VariablesRequest $request)
    {

        // Проверяем можно ли взять это авто
        if ($request->has('bus_id') && !$this->service->checkCanBeTakenCar($request->get('bus_id'))) {
            return $this->responseMobile('error', "Car can't taken.");
        }

        // Проверяем может ли взять это авто это пользователь
        if (!$this->service->checkCanBeUserTakenCar(\Auth::id())) {
            return $this->responseMobile('error', "User can't take a car.");
        }

        // Проверяем наличие дефолтного шалона диагностической карты в авто если с ним все хорошо
        $bus = Bus::find($request->get('bus_id'));
        if (empty($bus->diagnostic_card_template_id)) {
            return $this->responseMobile('error', __('messages.admin.garage_area.select_the_default_diagnostic_card_template_for_this_car'));
        }

        // Берем авто
        $result = $this->service->takeCar($request, $bus);

        if (!$result) {
            return $this->responseMobile('error', "Error");
        }
        $diagnosticCard = !empty($result['diagnostic_card']) ? $result['diagnostic_card'] : null;
        $userTakenBus = !empty($result['user_taken_bus']) ? $result['user_taken_bus'] : null;


        if ($request->get('condition') == 1) {
            return $this->responseMobile('success', ['user_taken_bus' => $userTakenBus]);
        } else {
            return $this->responseMobile('success', [
                'bus_id' => $request->get('bus_id'),
                'diagnostic_card' => $diagnosticCard,
                'user_taken_bus' => $userTakenBus,
                'result' => $result

            ]);
        }
    }

    public function put(VariablesRequest $request)
    {
        $bus = Bus::find($request->get('bus_id'));
        if (empty($bus->diagnostic_card_template_id)) {
            return $this->responseMobile('error', __('messages.admin.garage_area.select_the_default_diagnostic_card_template_for_this_car'));
        }

        $userTakenBus = $bus->user_taken()->whereStatus(UserTakenBus::STATUS_TAKEN)->latest()->first();

        $result = $this->service->putCar($userTakenBus, $bus, $request);

        if (!$result) {
            return $this->responseMobile('error', "Error");
        }
        $diagnosticCard = !empty($result['diagnostic_card']) ? $result['diagnostic_card'] : null;
        $userTakenBus = !empty($result['user_taken_bus']) ? $result['user_taken_bus'] : null;


        if ($request->get('condition') == 1) {
            return $this->responseMobile('success', ['user_taken_bus' => $userTakenBus]);
        } else {
            return $this->responseMobile('success', [
                'bus_id' => $request->get('bus_id'),
                'diagnostic_card' => $diagnosticCard,
                'user_taken_bus' => $userTakenBus,
            ]);
        }
    }

    public function takeFinish(TakeCarFinishRequest $request, $bus, $userTakenBus)
    {
        $userTakenBus = UserTakenBus::find($userTakenBus);
        if ($userTakenBus && $userTakenBus->status == UserTakenBus::STATUS_CREATED) {
            if ($request->condition == 1) {
                $result = $this->service->moveCarToTaken($userTakenBus, $userTakenBus->bus);
            } else {
                $userTakenBus->status = UserTakenBus::STATUS_CANCELED;
                $result = $userTakenBus->save();
            }
            return $this->responseMobile('success', '', ['user_taken_bus' => $userTakenBus->load(['imageable','bus'])]);
        } else {
            return $this->responseMobile('error', '');
        }
    }

    public function putFinish($bus, $userTakenBus)
    {
        $userTakenBus = UserTakenBus::find($userTakenBus);
        if ($userTakenBus && $userTakenBus->status == UserTakenBus::STATUS_TAKEN) {
            $result = $this->service->moveCarToReturned($userTakenBus, $userTakenBus->bus);
            return $this->responseMobile('success', '', ['user_taken_bus' => $userTakenBus->load(['imageable', 'bus'])]);
        } else {
            return $this->responseMobile('error', '');
        }
    }

    public function review($bus, StoreCardReviewRequest $request)
    {
        // Проверяем наличие дефолтного шалона диагностической карты в авто если с ним все хорошо
        $bus = Bus::find($bus);
        if (empty($bus->diagnostic_card_template_id)) {
            return $this->responseMobile('error', __('messages.admin.garage_area.select_the_default_diagnostic_card_template_for_this_car'));
        }
        $cardData = [
            'bus_id' => $bus->id,
            'user_id' => \Auth::id(),
            'diagnostic_card_template_id' => $bus->diagnostic_card_template_id,
            'type' => DiagnosticCard::TYPE_REVIEW
        ];
        $variableData = [
            'odometer' => $request->get('odometer'),
            'fuel' => $request->get('fuel'),
            'bus_id' => $bus->id
        ];
        try {
            $diagnosticCard = DiagnosticCard::create($cardData); //Создаем пустую диагностическую карту
            $diagnosticCard->bus_variable()->create($variableData); // Записиваем пробег и топливо
            return $this->responseMobile('success', '', ['diagnostic_card' => $diagnosticCard]);
        } catch (\Exception $exception) {
            return $this->responseMobile('error', $exception->getMessage());
        }
    }
}
