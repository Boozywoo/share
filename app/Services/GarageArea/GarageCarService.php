<?php

namespace App\Services\GarageArea;

use App\Models\Bus;
use App\Models\DiagnosticCard;
use App\Models\User;
use App\Models\UserTakenBus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class GarageCarService
{
    /**
     * @param $request
     * @return mixed
     */
    public function listCars($request)
    {

        if (array_key_exists('all_cars', $request)) {
            if (!empty($request['department_id'])) {
                return Bus::whereHas('departments', function ($q) use ($request) {
                    $q->where('departments.id', $request['department_id']);
                })->filter(request()->all());
            }
            $cars = Bus::filter(request()->all());
        } else {
            if (!empty($request['department_id'])) {
                return auth()->user()->buses()->whereHas('departments', function ($q) use ($request) {
                    $q->where('departments.id', $request['department_id']);
                })->filter(request()->all());
            }
            $cars = auth()->user()->buses()->filter(request()->all());
        }

        return $cars;
    }

    public function createWithDiagnosticCart($request)
    {
        $data = [
            'bus_id' => $request['bus_id'],
            'condition' => '1',
            'is_card' => '1',
            'type' => $request['type']
        ];
        $user = auth()->user();
        try {

            if ($userTakenBus = $user->taken_buses()->create($data)) {

                $bus = Bus::find($request['bus_id']);
                if (empty($bus) || empty($bus->diagnostic_card_template_id)) {
                    return false;
                }
                $cardData = [
                    'bus_id' => $request['bus_id'],
                    'user_id' => \Auth::id(),
                    'diagnostic_card_template_id' => $bus->diagnostic_card_template_id,
                ];
                $variableData = [
                    'odometer' => $request['odometer'],
                    'fuel' => $request['fuel'],
                    'bus_id' => $request['bus_id'],
                ];
                $diagnosticCard = DiagnosticCard::create($cardData);
                $diagnosticCard->bus_variable()->create($variableData);

                $userTakenBus->update(['diagnostic_card_id' => $diagnosticCard->id]);
                $bus->setLocationStatusByType($request['type']);

                return $userTakenBus;
            }

            return null;
        } catch (\Exception $exception) {
            return null;
        }
    }

    public function create($request)
    {
        $data = [
            'bus_id' => $request['bus_id'],
            'condition' => '0',
            'is_card' => '0',
            'type' => $request['type']
        ];
        $user = auth()->user();
//        try {
        $bus = Bus::find($request['bus_id']);
        $userTakenBus = $user->taken_buses()->create($data);
        $bus->setLocationStatusByType($request['type']);

        return $userTakenBus;
//        } catch (\Exception $exception) {
//            return null;
//        }
    }

    public function takeCar($request, $bus)
    {
        $user = Auth::user();
        $data = $request->only(['bus_id', 'condition']);
        $data['status'] = UserTakenBus::STATUS_CREATED;

        $userTakenBus = $user->taken_buses()->create($data);
        $cardData = [
            'bus_id' => $bus->id,
            'user_id' => \Auth::id(),
            'diagnostic_card_template_id' => $bus->diagnostic_card_template_id,
            'type' => DiagnosticCard::TYPE_TAKE
        ];
        $variableData = [
            'odometer' => $request->get('odometer'),
            'fuel' => $request->get('fuel'),
            'bus_id' => $bus->id
        ];

        try {
            $diagnosticCard = $userTakenBus->diagnostic_cards()->create($cardData); //Создаем пустую диагностическую карту
            $diagnosticCard->bus_variable()->create($variableData); // Записиваем пробег и топливо

            if ($request->has('condition') && $request->get('condition') == 1) {
                $this->moveCarToTaken($userTakenBus, $bus);
            }

            return ['user_taken_bus' => $userTakenBus->load(['imageable','bus']), 'diagnostic_card' => $diagnosticCard];
        } catch (\Exception $exception) {
            return $exception->getTrace();
        }

    }

    public function putCar($userTakenBus, $bus, $request)
    {
        $user = Auth::user();

        $cardData = [
            'bus_id' => $bus->id,
            'user_id' => \Auth::id(),
            'diagnostic_card_template_id' => $bus->diagnostic_card_template_id,
            'type' => DiagnosticCard::TYPE_PUT
        ];
        $variableData = [
            'odometer' => $request->get('odometer'),
            'fuel' => $request->get('fuel'),
            'bus_id' => $bus->id
        ];

        try {

            $diagnosticCard = $userTakenBus->diagnostic_cards()->create($cardData);
//            $diagnosticCard = DiagnosticCard::create($cardData); //Создаем пустую диагностическую карту
            $userTakenBus->diagnostic_cards()->attach($diagnosticCard);
            $diagnosticCard->bus_variable()->create($variableData); // Записиваем пробег и топливо

            if ($request->has('condition') && $request->get('condition') == 1) {
                $this->moveCarToReturned($userTakenBus, $bus);
            }

            return ['user_taken_bus' => $userTakenBus->load(['imageable','bus']), 'diagnostic_card' => $diagnosticCard];
        } catch (\Exception $exception) {
            return false;
        }

    }

    public function moveCarToTaken($userTakenBus, $bus)
    {
        $userTakenBus->status = UserTakenBus::STATUS_TAKEN; // Меняем статус на Машина Взята
        $userTakenBus->started_at = Carbon::now();
        $userTakenBus->save();

        $bus->setLocationStatusByType(DiagnosticCard::TYPE_TAKE); // Обновляем статус местоположения авто

        // Все незаконченные заявки отменяем
        $bus->user_taken()->whereStatus(UserTakenBus::STATUS_CREATED)->update(['status' => UserTakenBus::STATUS_CANCELED]);
        return $userTakenBus;
    }

    public function moveCarToReturned($userTakenBus, $bus)
    {
        $userTakenBus->status = UserTakenBus::STATUS_RETURNED; // Меняем статус на Машина Возвращена
        $userTakenBus->ended_at = Carbon::now();
        $userTakenBus->save();

        $bus->setLocationStatusByType(DiagnosticCard::TYPE_PUT); // Обновляем статус местоположения авто
        return $userTakenBus;
    }

    public function checkCanBeTakenCar($car_id)
    {
        if ($bus = Bus::find($car_id)) {
            $busCount = $bus->user_taken()->whereStatus(UserTakenBus::STATUS_TAKEN)->count();
            if ($busCount > 0) {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    public function checkCanBeUserTakenCar($user_id)
    {
        if ($user = User::find($user_id)) {
            $userCount = $user->taken_buses()->whereStatus(UserTakenBus::STATUS_TAKEN)->count();
            if ($userCount > 0) {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }
}