<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PositionRequest;
use App\Models\Company;
use App\Models\User;
use App\Models\Position;

class PositionController extends Controller
{
    protected $entity = 'companies.positions';
    
    public function index(Company $company)
    {
        $positions = $company->positions()->paginate(10);

        if (request()->ajax() && !request('_pjax')) return $this->ajaxView($positions, $company);
        
        return view("admin.$this->entity.index", compact('company', 'positions') + ['entity' => $this->entity]);
    }

    public function create(Company $company)
    {
        $position = new Position();
        
        return view("admin.$this->entity.edit", compact('company', 'position') 
            + ['entity' => $this->entity]);
    }

    public function edit(Company $company, Position $position)
    {
        return view("admin.$this->entity.edit", compact('company', 'position') 
            + ['entity' => $this->entity]);
    }

    public function store(PositionRequest $request)
    {
        if ($id = request('id')) {
            $position = Position::find(request('id'));
            $position->update(request()->all());
        } else {
            $position = Position::create(request()->all());
        }

        return $this->responseSuccess();
    }

    protected function ajaxView($positions, $company)
    {
        return response([
            'view' => view('admin.' . $this->entity . '.index.table', compact('positions', 'company') + ['entity' => $this->entity])->render(),
            'pagination' => view('admin.partials.pagination', ['paginator' => $positions])->render(),
        ])->header('Cache-Control', 'no-cache, no-store');
    }

    public function list(Company $company, Position $position)
    {
        $users = User::whereNull('client_id')->where(['position_id' => $position->id])->filter(\request()->all())->latest()->paginate();
        if (request()->ajax() && !request('_pjax')) return $this->ajaxView($users);
        return view('admin.' . $this->entity . '.index.list', compact('users','position','company') + ['entity' => $this->entity]);
    }
}
