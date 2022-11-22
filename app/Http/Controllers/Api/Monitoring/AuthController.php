<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 006 06.02.19
 * Time: 22:36
 */

namespace App\Http\Controllers\Api\Monitoring;


use App\Http\Controllers\Api\ApiController;
use App\Models\Bus;

class AuthController extends ApiController
{

    public function login()
    {
        if (empty(request('bus_number'))) return ['type' => 'error', 'error' => 'Номер автобуса ' . trans('validation.no_exist')];
        $busNumber = request('bus_number');
        $bus = Bus::whereNumber($busNumber)->first();

        if (!$bus) return ['type' => 'error', 'error' => 'Автобус ' . trans('validation.no_exist')];
        if ($bus && !\Hash::check(request('password'), $bus->password)) {
            return ['type' => 'error', 'error' => trans('validation.index.custom.login_error')];
        }
        return $this->responseSuccess(['type' => 'success', 'bus' => $bus]);
    }
}