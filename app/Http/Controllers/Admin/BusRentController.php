<?php

namespace App\Http\Controllers\Admin;

use App\Models\BusRent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Repair;

class BusRentController extends Controller
{

    protected $entity = 'buses.rent';

    public function index()
    {
        $this->authorize('bus-id', request('bus_id'));
        $bus = Bus::find(request('bus_id'));

        $rents = BusRent::filter(request()->all())
            ->latest()
            ->paginate();

        if (request()->ajax() && !request('_pjax')) return $this->ajaxView($rents);
        return view('admin.' . $this->entity . '.index', compact('rents', 'bus') + ['entity' => $this->entity]);
    }

    public function edit(BusRent $rent)
    {
        return view('admin.' . $this->entity . '.edit', compact('rent') + ['entity' => $this->entity]);
    }

    public function create()
    {
        $rent = new BusRent();
        if ($lastRent = BusRent::filter(request()->all())->get()->sortBy('to_hour')->last())
        {
            $rent->from_hour = $lastRent->to_hour;
            $rent->to_hour = ++$lastRent->to_hour;
            $rent->cost = --$lastRent->cost;
        }
        else $rent->from_hour = 0;
        return view('admin.' . $this->entity . '.edit', compact('rent') + ['entity' => $this->entity]);
    }

    public function store()
    {

        $this->authorize('bus-id', request('bus_id'));
        if ($id = request('id')) {
            $rent = BusRent::find($id);
            $rent->update(request()->all());
        } else {
            $rent = BusRent::create(request()->all());
        }

        return $this->responseSuccess();
    }

    protected function ajaxView($rents)
    {
        return response([
            'view' => view('admin.' . $this->entity . '.index.table', compact('repairs') + ['entity' => $this->entity])->render(),
            'pagination' => view('admin.partials.pagination', ['paginator' => $rents])->render(),
        ])->header('Cache-Control', 'no-cache, no-store');
    }


}
