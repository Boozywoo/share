<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SparePartRequest;
use App\Models\SparePart;

class SparePartController extends Controller
{
    private $entity = 'settings.exploitation.spare_parts';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $spareParts = SparePart::whereNull('parent_id')->get();

        return view('admin.' . $this->entity . '.index', compact('spareParts') + ['entity' => $this->entity]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sparePart = new SparePart();
        return view('admin.' . $this->entity . '.edit', compact('sparePart') + ['entity' => $this->entity]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(SparePartRequest $request)
    {
        $spare_part = SparePart::create($request->all());
        return $this->responseSuccess();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(SparePart $sparePart)
    {
        return view('admin.' . $this->entity . '.items.index', compact('sparePart') + ['entity' => $this->entity]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SparePart $sparePart)
    {
        return view('admin.' . $this->entity . '.edit', compact('sparePart') + ['entity' => $this->entity]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(SparePartRequest $request, SparePart $sparePart)
    {
        $sparePart->update($request->all());

        return $this->responseSuccess();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
