<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomerCompanyController extends Controller
{
    protected $entity = "settings.car_settings.customer_companies";

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
        $customerCompany = new CustomerCompany();

        return view('admin.' . $this->entity . '.edit', compact('customerCompany') + ['entity' => $this->entity]);
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
        CustomerCompany::create($data);
        return $this->responseSuccess();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerCompany $customerCompany)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerCompany $customerCompany)
    {
        return view('admin.' . $this->entity . '.edit', compact('customerCompany') + ['entity' => $this->entity]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerCompany $customerCompany)
    {
        $customerCompany->update($request->only(['name','slug']));

        return $this->responseSuccess();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerCompany $customerCompany)
    {
        $customerCompany->delete();
        return $this->responseSuccess();
    }
}
