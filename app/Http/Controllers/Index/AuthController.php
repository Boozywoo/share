<?php

namespace App\Http\Controllers\Index;

use App\Http\Requests\Index\Auth\LoginRequest;
use App\Http\Requests\Index\Auth\RegisterRequest;
use App\Http\Requests\Index\Auth\AuthConfirmRequest;
use App\Models\Client;
use App\Notifications\Client\SendPasswordNotification;
use App\Http\Controllers\Controller;
use App\Services\Client\StoreClientService;
use App\Services\Code\CheckCodeService;
use App\Services\Code\SendCodeService;
use App\Traits\ClearPhone;
use Barryvdh\Debugbar\Middleware\Debugbar;
use Barryvdh\Debugbar\Twig\Extension\Debug;


class AuthController extends Controller
{
    use ClearPhone;

    public function doLogin(LoginRequest $request)
    {
        $phone = $this->clearPhone(request('phone'));
        $client = Client::wherePhone($phone)->first();

        if (! \Hash::check(request('password'), $client->password)) {
            return $this->responseError([
                'errors' => ['phone' => [trans('validation.index.custom.login_error')]],
                'message' => trans('validation.index.custom.login_error')
            ]);
        }
        if ($client->status == Client::STATUS_DISABLE) {
            return $this->responseError([
                'message' => trans('validation.index.custom.black_list')
            ]);
        }
        \Auth::login($client->user, request('remember') ? true : false);

        return $this->responseSuccess([
            'message' => trans('index.messages.auth.login'),
            'redirect' => url()->previous(),
        ]);
    }

    public function doRegister(RegisterRequest $request)
    {
        $data = [
            'client_id' => '',
            'phone' => request('phone'),
            'email' => request('email'),
            'first_name' => request('first_name'),
            'password' => request('password'),
        ];

        session(['client' => $data]);

        SendCodeService::index(session('client.phone'));

        return $this->responseSuccess(['redirect' => route('index.auth.confirm')]);
    }

    public function confirm()
    {

        if (session('client')) return view('index.auth.confirm');

        return redirect(route('index.home'));
    }

    public function doConfirm(AuthConfirmRequest $request)
    {
        $result = CheckCodeService::index(session('client.phone'), request('code'));

        if (!$result) return $this->responseError(['message' => trans('index.order.not_correct')]);

        $clientId = StoreClientService::index(session('client'), true);

        $client = Client::find($clientId);

        if ($client->status == Client::STATUS_DISABLE) {
            return $this->responseError([
                'message' => trans('validation.index.custom.black_list')
            ]);
        }

        \Auth::login($client->user, true);

        session()->forget('client');

        return $this->responseSuccess([
            'message' => trans('index.order.thanks_for_registration'),
            'redirect' => route('index.home'),
        ]);
    }

    public function logout()
    {
        \Auth::logout();
        return redirect(route('index.home'));
    }

    public function forget()
    {
        $phone = $this->clearPhone(request('phone'));

        if(empty(trim($phone))){
            return $this->responseError([
                'errors' => ['phone' => [trans('validation.index.custom.login_error')]],
                'message' => trans('validation.index.custom.login_forget')
            ]);
        }

        $client = Client::wherePhone($phone)->first();
        if(empty($client)) {
            return $this->responseError([
                'errors' => ['phone' => [trans('validation.index.custom.login_error')]],
                'message' => trans('validation.index.custom.login_forget')
            ]);
        }
        if ($client->status == Client::STATUS_DISABLE) {
            return $this->responseError([
                'message' => trans('validation.index.custom.black_list')
            ]);
        }
        $password = rand(111111, 999999);
        $client->password = $password;
        $client->update();
        Client::find($client->id)->notify(new SendPasswordNotification($password));
        return $this->responseSuccess([
            'message' => trans('index.messages.auth.forget'),
            'redirect' => url()->previous(),
        ]);
    }
}
