<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarColor;
use App\Models\CustomerCompany;
use App\Models\CustomerDepartment;
use App\Models\CustomerPersonality;

class CarSettingsController extends Controller
{
    protected $entity = 'settings.car_settings';

    public function index()
    {

        $colors = CarColor::all();
        $customerPersonalities = CustomerPersonality::all();
        $customerCompanies = CustomerCompany::all();
        $customerDepartments = CustomerDepartment::all();

        return view('admin.' . $this->entity . '.index', compact('colors','customerPersonalities','customerCompanies','customerDepartments')+['entity' => $this->entity]);
    }
}
