<?php

namespace App\Services\Bus;

class BusService
{

    public function getVariablesForGraph($variables){
        $allVariables = $variables->groupBy(function ($variable){
            return $variable->created_at->format('d.m.Y H');
        })->map(function ($variable){
            return $variable->sortBy('created_at')->last();
        });
        $result['odometer'] = $allVariables->map(function ($variable){
            return $variable->odometer;
        });
        $result['fuel'] = $allVariables->map(function ($variable){
            return $variable->fuel;
        });
        return collect($result);
    }
}