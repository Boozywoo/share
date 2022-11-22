<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function response($result = 'success', $data = [])
    {
        return ['result' => $result] + $data;
    }

    public function responseSuccess($data = [])
    {
        return $this->response('success', $data);
    }

    public function responseError($data = [])
    {
        return $this->response('error', $data);
    }

    public function responseJsonSuccess($data = [])
    {
        return response()->json($data, 200);
    }

    public function responseJsonError($data = [], $code = 404)
    {
        return response()->json($data,404);
    }

    public function responseMobile($status, $message = '', $data = [], $errors = []){
        $status == 'error' ? $code = 404 : $code = 200;
        return response()->json([
            'result' => $status,
            'errors' => $errors,
            'data' => $data,
            'message' => $message
        ], $code);
    }
}
