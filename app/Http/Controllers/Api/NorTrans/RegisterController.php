<?php

namespace App\Http\Controllers\Api\NorTrans;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\AuthConfirmRequest;
use App\Http\Requests\Api\Admin\Register\RegisterRequest;
use App\Repositories\SelectRepository;
use App\Services\Auth\RegisterService;
use App\Services\Notification\NotificationService;
use App\Traits\ClearPhone;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    use ClearPhone;

    protected $select;

    public function __construct(SelectRepository $select, LoginController $loginController)
    {
        $this->select = $select;
        $this->loginController = $loginController;
    }

    public function registration(RegisterRequest $request, RegisterService $registerService)
    {
        if ($user = $registerService->mobileRegister($request)) {
            $registerService->sendConfirmationCode($user);
            $tokenData = $registerService->saveToken($user);

            return $this->loginController->checkRegisterStep($user, $tokenData, $registerService);

//            return $this->responseMobile('success', '', ['user_id' => $user->id]);
        } else {
            return $this->responseMobile('error');
        }
    }

    public function registrationCode(AuthConfirmRequest $request, RegisterService $registerService)
    {
        $user = $request->user();
        if ($registerService->confirmRegister($request->get('register_code'), $user)) {

            $tokenData = $registerService->saveToken($user);

            return $this->loginController->checkRegisterStep($user, $tokenData, $registerService);

//            return $this->responseMobile('success');
        }
        return $this->responseMobile('error', trans('admin.auth.error_code'));
    }

    public function registrationCompanyStep(Request $request, RegisterService $registerService)
    {
        $user = $request->user();
        $user->company_id = $request->get('company_id');
        if ($user->save()) {

            $tokenData = $registerService->saveToken($user);

            return $this->loginController->checkRegisterStep($user, $tokenData, $registerService);

//            return $this->responseMobile('success');
        } else {
            return $this->responseMobile('error');
        }
    }

    public function registrationDepartmentStep(Request $request, NotificationService $notificationService, RegisterService $registerService)
    {
        $user = $request->user();
        $user->department_id = $request->get('department_id');
        if ($user->save()) {
            try{
                $notificationService->newRegistration($user);
            }catch (\Exception $exception){}

            $tokenData = $registerService->saveToken($user);

            return $this->loginController->checkRegisterStep($user, $tokenData, $registerService);

//            return $this->responseMobile('success', trans('index.messages.auth.register'));
        } else {
            return $this->responseMobile('error');
        }
    }

    public function companies(){
        return $this->responseMobile('success','', ['companies' => $this->select->companies()]);
    }

    public function departments(Request $request){
        return $this->responseMobile('success','', ['departments' => $this->select->departments($request->get('company_id'))]);
    }

}
