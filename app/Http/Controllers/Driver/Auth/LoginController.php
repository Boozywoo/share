<?php

namespace App\Http\Controllers\Driver\Auth;

use App\Http\Controllers\Driver\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Driver;
use Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function logout()
    {
        auth()->logout();
        return view('driver.auth.login');
    }

    public function showLoginForm()
    {
        return view('driver.auth.login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required|string|max:15',
        ]);

        $driver = Driver::where('phone', $request->get('phone'))->first();

        if (!empty($driver)) {
            if(($request->get('phone') != $driver->phone) || (!password_verify($request->get('password'), $driver->password))) {
                echo "<script>alert('Неверный пароль!');</script>";
                return view('driver.auth.login');
            }      

            Auth::guard('driver')->login($driver);
            return redirect()->route('driver.tourToday');
        }

        echo "<script>alert('Неверный логин!');</script>";
        return view('driver.auth.login');

        
    }
    
    protected function guard()
    {
        return Auth::guard('driver');
    }
}
