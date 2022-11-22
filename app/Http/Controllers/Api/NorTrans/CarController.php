<?php

namespace App\Http\Controllers\Api\NorTrans;

use App\Http\Controllers\Controller;
use App\Services\GarageArea\GarageCarService;
use Illuminate\Http\Request;

class CarController extends Controller
{


    public function __construct(GarageCarService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $cars = $user->buses()->with(['departments'])->get();

        return $this->responseMobile('success', '', ['cars' => $cars]);
    }


}
