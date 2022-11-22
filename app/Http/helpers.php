<?php

use App\Models\User;
use App\Models\UserTakenBus;

if (!function_exists('uniqueByKey')) {
    function uniqueByKey($collection, $key, $value, $type, $additional = null)
    {
        $collection = $collection->pluck($value, $key)->unique()->filter(function ($val) {
            return !empty($val);
        });

        $field = $collection
            ->mapWithKeys(function ($val, $key) use ($type, $additional, $collection) {
                if ($type == 'dates') {
                    if (empty($additional)) {
                        $val = \Carbon\Carbon::parse($val)->format('Y.m.d');
                    } else {
                        $val = \Carbon\Carbon::parse($val)->format($additional);
                    }
                } elseif ($type == 'constants') {
                    if (!empty($additional)) {
                        $val = $additional[$val];
                    }
                } elseif ($type == 'relations' && $val instanceof \Illuminate\Database\Eloquent\Model) {
                    $val = collect($val);
                    $key = $val->has('id') ? $val->get('id') : $key;
                    if (!empty($additional)) {
                        $val = $val->has($additional) ? $val->get($additional) : $val;
                    } else {
                        $val = $val->has('name') ? $val->get('name') : print_r($val);
                    }
//                    if(json_decode($key)){
//                    }
                }
                return [$key => (is_string($val) && strlen($val) > 60 ? \Illuminate\Support\Str::limit($val, 60) . '...' : $val)];
            })->sort();
        return $field;
    }
}
if (!function_exists('checkCanBeUserTakenCar')) {

    function checkCanBeUserTakenCar($user_id)
    {
        if ($user = User::find($user_id)) {
            $userCount = $user->taken_buses()->whereStatus(UserTakenBus::STATUS_TAKEN)->count();
            if ($userCount > 0) {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

}
if (!function_exists('generateCode')) {
    function generateCode($length, $type = 'number')
    {
        $charactersString = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersNumeric = '0123456789';

        $code = '';
        if ($type == 'string') {
            $characters = $charactersString;
        } else {
            $characters = $charactersNumeric;
        }

        while (strlen($code) < $length) {
            $position = rand(0, strlen($characters) - 1);
            $character = $characters[$position];
            $code = $code . $character;
        }

        return $code;

    }
}