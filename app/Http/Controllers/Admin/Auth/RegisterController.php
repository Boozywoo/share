<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Channels\SmsChannel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\AuthConfirmRequest;
use App\Http\Requests\Admin\Auth\RegisterRequest;
use App\Models\Company;
use App\Models\Department;
use App\Models\User;
use App\Repositories\SelectRepository;
use App\Services\Auth\RegisterService;
use App\Services\Notification\NotificationService;
use Illuminate\Http\Request;


class RegisterController extends Controller
{
    protected $select;


    public function __construct(SelectRepository $selectRepository)
    {
        $this->select = $selectRepository;
    }

    public function popupRegister(){
        $companies = $this->select->companies();

        return ['html' => view('vendor.panel.auth.register', compact('companies'))->render()];
    }

    public function popupSuccessRegister(){
        return ['html' => view('vendor.panel.auth.success_register')->render()];
    }

    public function popupCodeRegister(){
        $id = request()->get('user');
        return ['html' => view('vendor.panel.auth.code_register', compact('id'))->render()];
    }

    public function registrationCode(AuthConfirmRequest $request, RegisterService $registerService){
        if($registerService->confirmRegister($request->get('register_code'), User::find($request->get('user')))){
            return $this->responseSuccess(['popup_url' => route('admin.auth.success_register')]);
        }
        return $this->responseError(['message' => trans('admin.auth.error_code')]);
    }

    public function registration(
        RegisterRequest $request,
        NotificationService $notificationService,
        RegisterService $registerService
    ){
        if($user = $registerService->register($request)) {
            $notificationService->newRegistration($user);
            $registerService->sendConfirmationCode($user);
            return $this->responseSuccess(['popup_url' => route('admin.auth.register_code', ['user' => $user->id])]);
        } else {
            return $this->responseError(['message' => trans('admin.auth.register_error')]);
        }
    }

    public function searchCompany(Request $request){
        $company_id = $request->get('company_id');
        $departments = $this->select->departments($company_id);

        return json_encode($departments->toArray());
    }

    public function searchDirector(Request $request){
        $department_id = $request->get('department_id');
        $department = Department::find($department_id);
        $director = $department->director()->first();

        return json_encode([$director->id => $director->first_name]);
    }

    public function companyRole(Request $request){
        return $this->select->rolesCompanies($request->get('company_id'));
    }

    public function companyPosition(Request $request){
        return $this->select->positions($request->get('company_id'));
    }
}
