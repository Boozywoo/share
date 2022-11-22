<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 026 26.06.19
 * Time: 10:17
 */

namespace App\Services\Call;


class CallClient implements ICallProvider
{
    protected $provider;

    public function __construct()
    {
        $classname = 'App\\Services\\Call\\Providers\\' . config('call.provider');
        try {
            $this->provider = new $classname();
        } catch (\Exception $e) {
        }
    }

    public function webHookAnswer($data)
    {
        try {
            $function = __FUNCTION__;
            $this->provider->$function($data);
        } catch (\Exception $e) {

        }
    }

    public function webHookHangUp($data)
    {

    }

    public function webHookIncoming($data)
    {

    }

    public function outCall($extNumber, $phoneClient)
    {
        try {
            $function = __FUNCTION__;
            return $this->provider->$function($extNumber, $phoneClient);
        } catch (\Exception $e) {

        }
    }
}