<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Driver\LoginRequest;
use App\Models\Driver;
use App\Traits\ClearPhone;

class AuthController extends ApiController
{
	use ClearPhone;

	public function login(LoginRequest $request)
	{
		$phone = $this->clearPhone($request->input('phone'));
		$driver = Driver::wherePhone($phone)->first();

		if (!\Hash::check($request->input('password'), $driver->password)) {
			return $this->responseError(['errors' => ['phone' => [trans('validation.index.custom.login_error')]]]);
		}
		return $this->responseSuccess(['api_token' => $driver->token->api_token]);
	}
}