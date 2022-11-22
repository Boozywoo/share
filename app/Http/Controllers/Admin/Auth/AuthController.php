<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\LoginRequest;
use App\Http\Requests\Admin\Auth\ChangePasswordRequest;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Setting;


class AuthController extends Controller
{
    public function login()
    {

        $cip_registration = Setting::all()->pluck('sip_registration')->first();
        $sip_value = !empty($_COOKIE['sip_value']) ? $_COOKIE['sip_value'] :'';
        return view('panel::auth.login', ['sip_reg' => $cip_registration,'sip_value'=>$sip_value]);
    }

    public function doLogin(LoginRequest $request)
    {

        $user = User::whereEmail(request('email'))->first();


        if (!\Hash::check(request('password'), $user->password)) {

            return $this->responseError(['errors' => ['email' => [trans('validation.index.custom.login_error')]]]);
        }

        \Auth::login($user, true);


        $cip_registration = Setting::all()->pluck('sip_registration')->first();

        if ($cip_registration == 1) {
            setcookie("sip_value",request('sip'), time() + (86400 * 30), "/");
            $user->sip = request('sip');
            $user->save();
        }
        return $this->responseSuccess([
            'message' => trans('messages.admin.auth.login'),
            'redirect' => route('admin.home'),
        ]);
    }

    public function changePassword()
    {
        return view('admin.auth.change-password');
    }

    public function doChangePassword(ChangePasswordRequest $request)
    {
        if (!\Hash::check(request('old_password'), auth()->user()->password)) {
            return ['errors' => ['old_password' => [trans('validation.index.custom.old_password_wrong')]]];
        }

        auth()->user()->update([
            'password' => request('new_password'),
            'date_change_password' => Carbon::now()
        ]);

        return $this->responseSuccess(['redirect' => route('admin.home')]);
    }

    public function logout()
    {
        auth()->logout();
        return redirect(route('admin.auth.login'));
    }

}
