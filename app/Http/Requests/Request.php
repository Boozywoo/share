<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class Request extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->response($this->formatErrors($validator)));
    }

    public function responseSuccess()
    {
        return new JsonResponse([
            'result' => 'success',
            'redirect' => $this->getRedirectUrl(),
        ]);
    }

    public function responseError($errors)
    {
        $message = [];
        if(isset(head($errors)[0])) $message = ['message' => head($errors)[0]];
        return new JsonResponse([
            'result' => 'error',
            'errors' => $errors,
        ] + $message);
    }

    public function response(array $errors = [], $success = false)
    {
        return $success == true ? $this->responseSuccess() : $this->responseError($errors);
    }
}
