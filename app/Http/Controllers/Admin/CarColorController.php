<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CarColorRequest;
use App\Models\CarColor;

class CarColorController extends Controller
{
    protected $entity = 'settings.car_settings.car_colors';

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
        $carColor = new CarColor();

        return view('admin.' . $this->entity . '.edit', compact('carColor'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CarColorRequest $request)
    {
        $carColor = CarColor::create($request->all());

        return $this->responseSuccess();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(CarColor $carColor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(CarColor $carColor)
    {
        return view('admin.' . $this->entity . '.edit', compact('carColor')+['entity' => $this->entity]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(CarColorRequest $request, CarColor $carColor)
    {
        $carColor->update($request->only(['name']));

        return $this->responseSuccess();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CarColor $carColor)
    {
        $carColor->delete();

        return $this->responseSuccess();
    }
}
