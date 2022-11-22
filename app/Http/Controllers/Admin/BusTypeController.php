<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bus;
use App\Models\Client;
use App\Models\Schedule;
use App\Models\Tour;
use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\SelectRepository;
use \App\Models\BusType;

class BusTypeController extends Controller
{
    protected $entity = 'bus_type';
    protected $select;


    public function __construct(SelectRepository $selectRepository)
    {
        $this->select = $selectRepository;
    }


    public function index()
    {
        $busTypes = BusType::paginate();;
        return view('admin.bus_type.index', compact('busTypes') + ['entity' => $this->entity]);
    }

    public function edit(BusType $busType)
    {
        return view('admin.' . $this->entity . '.edit', compact('busType') + ['entity' => $this->entity]);
    }

    public function create()
    {
        $busType = new BusType();
        return view('admin.' . $this->entity . '.edit', compact('busType') + ['entity' => $this->entity]);
    }

    public function store()
    {
        if ($id = \request('id'))
            BusType::whereId($id)->update(['name' => \request('name')]);
        else BusType::create(['name' => \request('name')]);
        return $this->responseSuccess();
    }
}
