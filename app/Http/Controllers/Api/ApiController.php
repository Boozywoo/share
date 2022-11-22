<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function responseError($data = [])
    {
        return response()->json(['result' => 'error'] +  $data, 400);
    }
}
