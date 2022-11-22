<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SparePartRequest;
use App\Models\SparePart;

class SparePartItemController extends Controller
{
    private $entity = 'settings.exploitation.spare_parts.items';

    public function index(SparePart $sparePart)
    {
        return view('admin.' . $this->entity . '.index', ['entity' => $this->entity, 'sparePart' => $sparePart]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(SparePart $sparePart)
    {
        $item = new SparePart();
        return view('admin.' . $this->entity . '.edit', compact('item') + ['entity' => $this->entity, 'sparePart' => $sparePart]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(SparePart $sparePart, SparePartRequest $request)
    {
        $sparePart->childs()->create($request->all());
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
    public function edit(SparePart $sparePart, SparePart $item)
    {
        return view('admin.' . $this->entity . '.edit', compact('item') + ['entity' => $this->entity, 'sparePart' => $sparePart]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(SparePartRequest $request, SparePart $sparePart, SparePart $item)
    {
        $item->update($request->all());

        return $this->responseSuccess();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SparePart $sparePart, SparePart $item)
    {
        //
    }
}
