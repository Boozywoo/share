<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerDepartment;
use App\Models\CustomerPersonality;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomerPersonalityController extends Controller
{
    protected $entity = "settings.car_settings.customer_persons";

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
        $customerPersonality = new CustomerPersonality();

        return view('admin.' . $this->entity . '.edit', compact('customerPersonality') + ['entity' => $this->entity]);
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
        CustomerPersonality::create($data);

        return $this->responseSuccess();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerPersonality $customerPerson)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerPersonality $customerPerson)
    {
        $customerPersonality = $customerPerson;
        return view('admin.' . $this->entity . '.edit', compact('customerPersonality') + ['entity' => $this->entity]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerPersonality $customerPerson)
    {
        $customerPerson->update($request->only(['name','slug']));

        return $this->responseSuccess();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerPersonality $customerPerson)
    {
        $customerPerson->delete();

        return $this->responseSuccess();
    }
}
