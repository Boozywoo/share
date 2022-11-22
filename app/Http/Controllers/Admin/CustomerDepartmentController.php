<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerCompany;
use App\Models\CustomerDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomerDepartmentController extends Controller
{
    protected $entity = "settings.car_settings.customer_departments";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customerDepartment = new CustomerDepartment();

        return view('admin.' . $this->entity . '.edit', compact('customerDepartment') + ['entity' => $this->entity]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only(['name', 'slug']);
        CustomerDepartment::create($data);

        return $this->responseSuccess();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerDepartment $customerDepartment)
    {

        return view('admin.' . $this->entity . '.edit', compact('customerDepartment') + ['entity' => $this->entity]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerDepartment $customerDepartment)
    {
        $customerDepartment->update($request->only(['name','slug']));

        return $this->responseSuccess();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerDepartment $customerDepartment)
    {
        $customerDepartment->delete();

        return $this->responseSuccess();
    }
}
