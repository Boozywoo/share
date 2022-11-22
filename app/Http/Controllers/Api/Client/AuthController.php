<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Requests\Api\Client\ConfirmSmsCodeRequest;
use App\Http\Requests\Api\Client\LoginRequest;
use App\Http\Requests\Api\Client\RegisterRequest;
use App\Http\Requests\Api\Client\SendSmsCodeResetRequest;
use App\Models\Client;
use App\Models\Code;
use App\Models\Page;
use App\Models\Setting;
use App\Models\Config;
use App\Notifications\Client\SendCodeNotification;
use App\Notifications\Client\SendPasswordNotification;
use App\Services\Client\StoreClientService;
use App\Traits\ClearPhone;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    use ClearPhone;

    public function login(LoginRequest $request)
    {
        $phone = $this->clearPhone($request->input('phone'));
        $client = Client::wherePhone($phone)->first();

        if (!$client)   {
            return $this->responseJsonError(['errors' => ['code' => [trans('validation.index.custom.login_forget')]]], 401);
        }
        if ($client->status != Client::STATUS_ACTIVE) {
            return $this->responseJsonError(['errors' => ['password' => [trans('validation.index.custom.black_list')]]], 403);
        }
        if (!\Hash::check($request->input('password'), $client->password)) {
            return $this->responseJsonError(['errors' => ['password' => [trans('validation.index.custom.login_error')]]], 401);
        }
        $clientArr = $client->toArray();
        $data = collect($clientArr);

        $settings = Setting::with('currency')->first();
        $data->put('currency', $settings->currency->alfa ?? 'BYN');
        $data->put('pay_online', $settings->is_pay_on);
        $data->put('pay_cash', $settings->is_pay_cash);
        $data->put('show_place_numbers', (int)Config::getValue('mobile_app', 'show_place_numbers'));
        $data->put('calendar_days', (int)Config::getValue('mobile_app', 'calendar_days'));


        return $this->responseSuccess(['client' => $data, 'api_token' => $client->token->api_token]);
//        return $this->responseSuccess(['client' => $client, 'api_token' => $client->token->api_token]);
    }

    public function loginSendCode(Request $request)
    {
        $phone = $this->clearPhone($request->input('phone'));
        $client = Client::query()->where('phone', $phone)->first();

        $code = env('APP_ENV') === 'production' ? str_pad(rand(0, 9999), 4, '0') : '0000'; // : '0000';

        if ($phone == '71521515151') {
            $code = '0000';
        }

        Code::updateOrCreate(['phone' => $phone], ['phone' => $phone, 'code' => $code]);

        if (!$client) {
            $clientId = StoreClientService::index(['phone' => $phone, 'client_id' => ''], false, true, false);
            $client = Client::query()->find($clientId);
        }

        // TODO: включить потом
        $client->notify(new SendCodeNotification($code));

        return $this->responseSuccess(['message' => trans('index.send_code')]);
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->all();
        $phone = $this->clearPhone($request->input('phone'));
        $code = env('APP_ENV') == 'production' ? str_pad(rand(0, 9999), 4, '0') : '0000';
        Code::updateOrCreate(['phone' => $phone], ['phone' => $phone, 'code' => $code]);
        $clientId = StoreClientService::index($data + ['client_id' => '']);
        Client::find($clientId)->notify(new SendCodeNotification($code));
        return compact('clientId', 'code');
    }

    public function confirmSmsCode(ConfirmSmsCodeRequest $request)
    {
        $phone = $this->clearPhone($request->input('phone'));
        $code = $request->input('code');
        if (Code::whereCode($code)->wherePhone($phone)->first()) {
            StoreClientService::index($request->all() + ['client_id' => '']);
            $client = Client::wherePhone($phone)->first();
            return $this->responseSuccess(['client' => $client, 'api_token' => $client->token->api_token]);
        }
        return $this->responseJsonError(['errors' => ['code' => [trans('validation.index.custom.code')]]], 400);
    }

    public function confirmSmsCodeReset(ConfirmSmsCodeRequest $request)
    {
        $phone = $this->clearPhone($request->input('phone'));
        $code = $request->input('code');
        $client = Client::wherePhone($phone)->first();

        if (Code::whereCode($code)->wherePhone($phone)->first()) {
            $password = rand(111111, 999999);
            $client->password = $password;
            $client->update();
            Client::find($client->id)->notify(new SendPasswordNotification($password));
            return $this->responseSuccess(['client' => $client, 'api_token' => $client->token->api_token]);
        }

        return $this->responseJsonError(['errors' => ['code' => [trans('validation.index.custom.code')]]], 400);
    }

    public function sendSmsCodeReset(SendSmsCodeResetRequest $request)
    {
        $phone = $this->clearPhone($request->input('phone'));
        $phone = preg_replace('/[^0-9.]+/', '', $phone);
        $client = Client::wherePhone($phone)->first();
        $code = env('APP_ENV') == 'production' ? str_pad(rand(0, 9999), 4, '0') : '0000';
        Code::updateOrCreate(['phone' => $phone], ['phone' => $phone, 'code' => $code]);
        if ($client) {
            Client::find($client->id)->notify(new SendCodeNotification($code));
            return $this->responseSuccess(['message' => trans('index.send_code')]);
        } else {
            return $this->responseJsonError(['errors' => ['phone' => [trans('validation.no_exist')]]], 400);
        }
    }
}
