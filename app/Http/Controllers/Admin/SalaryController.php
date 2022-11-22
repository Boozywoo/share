<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\SelectRepository;
use App\Models\User;
use App\Models\Salary;
use Auth;

class SalaryController extends Controller
{
    protected $entity = 'salary';
    protected $select;

    public function __construct(SelectRepository $selectRepository)
    {
        $this->select = $selectRepository;
    }

    public function create(User $user)
    {
        return view('admin.' . $this->entity . '.edit', compact('user') + ['entity' => $this->entity]);
    }

    public function store(User $user)
    {
        if (($sum = request('sum')) && ($user_id = request('user_id'))) {
            $request = [
                'user_id' => $user_id,
                'admin_id' => Auth::user()->id,
                'sum' => $sum,
            ];

            if (\request('currency_id')) {
                $request['currency_id'] = \request('currency_id');
            }

            Salary::create($request);
        }

        return $this->responseSuccess();
    }
}
