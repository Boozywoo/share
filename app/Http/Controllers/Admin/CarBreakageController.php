<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarBreakages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class CarBreakageController extends Controller
{

    private $entity = 'settings.exploitation.breakages';

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $breakages = CarBreakages::whereNull('parent_id')->latest()->get();

        return view('admin.' . $this->entity . '.index', compact('breakages') + ['entity' => $this->entity]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        $breakage = new CarBreakages();
        return view('admin.' . $this->entity . '.edit', compact('breakage') + ['entity' => $this->entity]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $user->company->car_breakages()->create($request->all());

        return $this->responseSuccess();

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return View
     */
    public function show(CarBreakages $breakage)
    {
        $breakages = $breakage->childs()->get();
        $parent = $breakage;

        return view('admin.' . $this->entity . '.items', compact('breakages','parent') + ['entity' => $this->entity]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param CarBreakages $breakage
     * @return View
     */
    public function edit(CarBreakages $breakage)
    {
        return view('admin.' . $this->entity . '.edit', compact('breakage') + ['entity' => $this->entity]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param CarBreakages $breakage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CarBreakages $breakage)
    {
        $breakage->update($request->all());

        return $this->responseSuccess();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CarBreakages $breakage
     * @return \Illuminate\Http\Response
     */
    public function destroy(CarBreakages $breakage)
    {
        $breakage->delete();
        return $this->responseSuccess();
    }
}
