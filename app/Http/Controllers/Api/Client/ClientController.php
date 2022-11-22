<?php

namespace App\Http\Controllers\Api\CLient;

use App\Http\Requests\Api\Client\InfoClientRequest;
use App\Http\Requests\Api\Client\UpdateClientRequest;
use App\Models\Client;
use App\Models\Token;
use App\Models\Setting;
use App\Models\Config;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientController extends Controller
{
    public function info(InfoClientRequest $request)
    {
        $clientId = $this->userId($request);
        $clientArr = Client::where('id', $clientId)->first()->toArray();
        $data = collect($clientArr);

        $settings = Setting::with('currency')->first();
        $data->put('currency', $settings->currency->alfa ?? 'RUB');
        $data->put('pay_online', $settings->is_pay_on);
        $data->put('pay_cash', $settings->is_pay_cash);
        $data->put('show_place_numbers', (int)Config::getValue('mobile_app', 'show_place_numbers'));
        $data->put('calendar_days', (int)Config::getValue('mobile_app', 'calendar_days'));

        return $data;
    }

    public function update(UpdateClientRequest $request)
    {
        $data = [];
        $clientId = $this->userId($request);


        if ($request->first_name) {
            $data["first_name"] = $request->first_name;
        }

        if ($request->middle_name) {
            $data["middle_name"] = $request->middle_name;
        }

        if ($request->last_name) {
            $data["last_name"] = $request->last_name;
        }

        if ($request->password) {
            $data["password"] = bcrypt($request->password);
        }

        if ($request->gender) {
            $data['gender'] = $request->gender;
        }

        if ($request->address) {
            $data['address'] = $request->address;
        }

        if ($request->passport) {
            $data['passport'] = $request->passport;
        }

        if (!empty($data)) {
            Client::whereId($clientId)
              ->update($data);
        }


        $client = Client::find($clientId);

        return $this->responseSuccess(['client' => $client, 'api_token' => $client->token->api_token]);
    }

    public function userId($request)
    {
        return Token::where('api_token', $request->api_token)->first()->client_id;
    }

}
