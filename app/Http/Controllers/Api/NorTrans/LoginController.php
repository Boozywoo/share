<?php

namespace App\Http\Controllers\Api\NorTrans;

use App\Channels\SmsChannel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\Register\LoginRequest;
use App\Http\Requests\Api\Admin\Register\PasswordResetRequest;
use App\Http\Requests\Api\Admin\Register\PhoneRequest;
use App\Models\User;
use App\Models\UserVerificationCode;
use App\Repositories\SelectRepository;
use App\Services\Auth\RegisterService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    protected $select;

    public function __construct(SelectRepository $select)
    {
        $this->select = $select;
    }

    public function login(RegisterService $registerService, LoginRequest $request)
    {
        if (!$user = User::whereEmail(request('email'))->first()) {
            return $this->responseMobile('error', trans('validation.index.custom.login_error'));
        }
        if (!\Hash::check(request('password'), $user->password)) {
            return $this->responseMobile('error', trans('validation.index.custom.login_error'));
        }


        if ($user) {
            if (\Hash::check($request->password, $user->password)) {

                $tokenData = $registerService->saveToken($user);

                // Сhecking that the user has passed all the registration steps

                return $this->checkRegisterStep($user, $tokenData, $registerService);

            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 422);
            }
        } else {
            $response = ["message" => 'User does not exist'];
            return response($response, 422);
        }
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->token()->revoke();
        }

        return response()->json([
            'message' => 'You are successfully logged out',
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function check(Request $request)
    {
        $user = $request->user();
        if (empty($user)) {
            return response()->json(["result" => false], 400);
        } else {
            return response()->json(["result" => true]);
        }
    }

    public function checkRegisterStep($user, $tokenData, $registerService)
    {
        if (!$user->confirm) {
            $registerService->sendConfirmationCode($user);
            return $this->responseMobile('confirm_code', '', ['user_id' => $user->id, 'token' => $tokenData, 'code' => $user->code_confirm]); // TODO: убрать код подтверждения с ответа
        } elseif (!$user->company_id) {
            return $this->responseMobile('step_companies', '', ['companies' => $this->select->companies(), 'user_id' => $user->id, 'token' => $tokenData,]);
        } elseif (!$user->department_id) {
            return $this->responseMobile('step_departments', '', ['departments' => $this->select->departments($user->company_id), 'user_id' => $user->id, 'token' => $tokenData,]);
        } else {
            return $this->responseMobile('success', '', ['token' => $tokenData]);
        }

    }

    public function passwordForgot(PhoneRequest $request)
    {
//        $this->validate($request, [
//            'phone' => 'required|exists:users,phone'
//        ]);

        $verificationCode = UserVerificationCode::create([
            'field' => $request->get('phone'),
            'field_type' => 'phone',
            'code' => $this->generateResetCode(6),
            'type' => UserVerificationCode::TYPE_PASSWORD_RESET,
            'status' => UserVerificationCode::STATUS_ACTIVE,
            'expired_at' => Carbon::now()->addHour()
        ]);
        try {

            $message = trans('admin.auth.reset_code') . $verificationCode->code;

//            $sms = new SmsChannel();
//            $sms->send(null, null, $message, $request->get('phone'));

        } catch (\Exception $exception) {
            return $this->responseMobile('error', __('messages.admin.auth.code_send_error'));
        }

        return $this->responseMobile('success', __('messages.admin.auth.forget'), ['code' => $verificationCode]); // TODO: убрать код с ответа
    }

    public function passwordRecoveryCode($code, RegisterService $registerService)
    {
        $verificationCode = UserVerificationCode::whereCode($code)
            ->whereStatus(UserVerificationCode::STATUS_ACTIVE)
            ->whereType(UserVerificationCode::TYPE_PASSWORD_RESET)
            ->where('expired_at', '>', Carbon::now())
            ->first();
        if ($verificationCode) {
            $user = User::where($verificationCode->field_type, $verificationCode->field)->first();
            if ($user) {
                $tokenData = $registerService->saveToken($user);

                $verificationCode->status = UserVerificationCode::STATUS_USED;
                $verificationCode->save();

                return $this->responseMobile('success', '', ['token' => $tokenData]);
            } else {
                return $this->responseMobile('error', __('messages.admin.auth.error_please_repeat'));
            }
        } else {
            return $this->responseMobile('error', __('messages.admin.auth.code_not_correct'));
        }
    }

    public function passwordReset(PasswordResetRequest $request)
    {
        $user = $request->user();
        try {
            $user->password = $request->get('password');
            $user->save();

            return $this->responseMobile('success', __("messages.admin.auth.password_change_success"));
        } catch (\Exception $exception) {
            return $this->responseMobile('error');
        }
    }

    private function generateResetCode($length)
    {
        $code = generateCode($length);

        if (UserVerificationCode::whereStatus(UserVerificationCode::STATUS_ACTIVE)->whereCode($code)->exists()) {
            $this->generateResetCode($length);
        }

        return $code;
    }
}
