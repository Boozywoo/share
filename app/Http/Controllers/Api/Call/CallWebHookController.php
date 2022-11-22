<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 026 26.06.19
 * Time: 12:34
 */

namespace App\Http\Controllers\Api\Call;


class CallWebHookController
{
    public function index()
    {
        \Log::info(print_r(request()->all(),1));
    }
}