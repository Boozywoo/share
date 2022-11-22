<?php

namespace App\Services\Client;

use App\Models\Client;
use App\Models\Order;
use App\Notifications\Client\RegisterNotification;
use App\Services\Log\TelegramLog;
use App\Services\Prettifier;


class StoreClientService
{
    public static function index($data, $register = false, $update = true, $sendPass = true)
    {
        $clientId = array_get($data, 'client_id');
        if ($clientId !== null) {
            $phone = Prettifier::prettifyPhoneClear(array_get($data, 'phone'));
            if (!empty($data['phone-code'])) {
                $phone = $data['phone-code'] == 'by' ? '375' . $phone : $phone;
                $phone = $data['phone-code'] == 'ru' ? '7' . $phone : $phone;
                $phone = $data['phone-code'] == 'ua' ? '380' . $phone : $phone;
            }

            $dataClient = [];
            if ($card = array_get($data, 'card')) $dataClient['card'] = $card;
            if ($firstName = array_get($data, 'first_name')) $dataClient['first_name'] = $firstName;
            if ($MiddleName = array_get($data, 'middle_name')) $dataClient['middle_name'] = $MiddleName;
            if ($LastName = array_get($data, 'last_name')) $dataClient['last_name'] = $LastName;
            if ($Passport = array_get($data, 'passport')) $dataClient['passport'] = $Passport;
            if ($statusId = array_get($data, 'status_id')) $dataClient['status_id'] = $statusId;
            if ($dateSocial = array_get($data, 'date_social')) $dataClient['date_social'] = date('Y-m-d', strtotime($dateSocial));
            if ($email = array_get($data, 'email')) $dataClient['email'] = $email;
            if ($password = array_get($data, 'password')) $dataClient['password'] = $password;
            if ($phone) $dataClient['phone'] = $phone;
            if ($birth_day = array_get($data, 'birth_day')) $dataClient['birth_day'] = date('Y-m-d', strtotime($birth_day));
            $doc_type = array_get($data, 'doc_type', null);
            if ($doc_type !== null && $doc_type !== '') {
                $dataClient['doc_type'] = $doc_type;
            }
            if ($doc_number = array_get($data, 'doc_number')) $dataClient['doc_number'] = $doc_number;
            if ($country_id = array_get($data, 'country_id')) $dataClient['country_id'] = $country_id;
            if ($gender = array_get($data, 'gender')) $dataClient['gender'] = $gender;
            if ($register) $dataClient['register'] = 1;
            $client = Client::wherePhone($phone)->first();

            if (!$client) {
                if (!$password) {
                    $password = rand(111111, 999999);
                    $dataClient['password'] = $password;
                }

                $client = Client::create($dataClient);
                if (isset($data['source']) && $data['source'] == Order::SOURCE_OPERATOR) ; // не отсылать если клиент зарегистрирован через админку
                elseif ($sendPass) $client->notify(new RegisterNotification($password, $phone));
            } elseif ($update) {
                $client->update($dataClient);
            }
            $clientId = $client->id;
        }
        return $clientId;
    }
}
