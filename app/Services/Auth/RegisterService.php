<?php

namespace App\Services\Auth;

use App\Channels\SmsChannel;
use App\Http\Requests\Admin\Auth\RegisterRequest;
use App\Http\Requests\Api\Admin\Register\RegisterRequest as MobileRegisterRequest;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;

class RegisterService
{
    public function saveToken($user)
    {
        $token = $user->createToken(config('app.nor_trans_app_name'));
        $token->token->expires_at = Carbon::now()->addMonth();
        $token->token->save();

        $tokenData = [
            'token_type' => 'Bearer',
            'token' => $token->accessToken,
            'expires_at' => Carbon::parse($token->token->expires_at)->toDateTimeString()
        ];
        return $tokenData;

    }


    public function register(RegisterRequest $request)
    {
        $data = $request->all();
        $data['confirm'] = 0;
        $data['status'] = User::STATUS_DISABLE;

        if ($data['department_id']) {
            $department = Department::find($data['department_id']);
            $superior = $department->director()->first();
            $data['superior_id'] = $superior->id;
        }
        $user = User::create($data);

        $user->attachRole(Role::where('slug', 'employee')->first()->id);

        return $user;
    }

    public function mobileRegister(MobileRegisterRequest $request){
        $tmpData = $request->all();
        $data['first_name'] = $tmpData['first_name'].' '.$tmpData['last_name'].' '. $tmpData['middle_name'];
        $data['phone'] = $tmpData['phone'];
        $data['email'] = $tmpData['email'];
        $data['password'] = $tmpData['password'];
        $data['confirm'] = 0;
        $data['status'] = User::STATUS_DISABLE;

        $user = User::create($data);
        $user->attachRole(Role::where('slug', 'employee')->first()->id);

        return $user;
    }

    public function sendConfirmationCode(User $user){
        $key = mt_rand(100000, 999999);
        if ($user->phone) {
            $user->code_confirm = $key;
            $user->save();
            $message = trans('admin.auth.confirm_code') . $key;
            $sms = new SmsChannel();
            try {
                $sms->send(null, null, $message, $user->phone);
            } catch (\Exception $exception) {

            }
        }
    }

    public function confirmRegister($code, User $user){
        if ($user->code_confirm == $code) {
            $user->confirm = 1;
            $user->save();
            return true;
        }

        return false;
    }
}