<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DepartmentRequest;
use App\Models\Company;
use App\Models\Department;
use App\Models\User;
use App\Repositories\SelectRepository;

class DepartmentController extends Controller
{
    protected $entity = 'companies.departments';

    protected $select;

    public function __construct(SelectRepository $select)
    {
        $this->select = $select;
    }

    public function index(Company $company)
    {
        $departments = $company->departments()->paginate(10);
        
        if (request()->ajax() && !request('_pjax')) return $this->ajaxView($departments, $company);

        return view("admin.$this->entity.index", compact('company', 'departments') + ['entity' => $this->entity]);
    }

    public function create(Company $company)
    {
        $department = new Department();
        $directors = $company->users()->get()->pluck('full_name', 'id');
        $directors->prepend('- ' . trans('admin_labels.choose_director') . ' -', '');

        $superdepartments = $company->departments()->get()->pluck('name', 'id');
        $superdepartments->prepend('- ' . 'Choose department' . ' -', '');

        return view("admin.$this->entity.edit", compact('company', 'department', 'directors', 'superdepartments')
            + ['entity' => $this->entity]);
    }

    public function edit(Company $company, Department $department)
    {
        $directors = $company->users()->whereIn('department_id', [0, $department->id])->get()->pluck('full_name', 'id');
        $directors->prepend('- ' . trans('admin_labels.choose_director') . ' -', '');

        $superdepartments = $company->departments()->where('id', '<>', $department->id)->get()->pluck('name', 'id');
        $superdepartments->prepend('- ' . 'Choose department' . ' -', '');

        return view("admin.$this->entity.edit", compact('company', 'department', 'directors', 'superdepartments')
            + ['entity' => $this->entity]);
    }

    public function store(DepartmentRequest $request)
    {

        if ($id = request('id')) {
            $department = Department::find(request('id'));
            $department->update(request()->all());

            // это не нужно, поскольку начальник может не входить в отдел
            // $department->director->update(['department_id' => $department->id]);
        } else {
            $department = Department::create(request()->all());

            // это не нужно, поскольку начальник может не входить в отдел
            // $department->director->update(['department_id' => $department->id]);
        }

        return $this->responseSuccess();
    }

    protected function ajaxView($departments, $company)
    {
        return response([
            'view' => view('admin.' . $this->entity . '.index.table', compact('departments','company') + ['entity' => $this->entity])->render(),
            'pagination' => view('admin.partials.pagination', ['paginator' => $departments])->render(),
        ])->header('Cache-Control', 'no-cache, no-store');
    }

    public function list(Company $company, Department $department)
    {
        $users = User::whereNull('client_id')->where(['department_id' => $department->id])->filter(\request()->all())->latest()->paginate();
        if (request()->ajax() && !request('_pjax')) return $this->ajaxView($users);
        return view('admin.' . $this->entity . '.index.list', compact('users','department','company') + ['entity' => $this->entity]);
    }

    public function setDepartmentPopup(Company $company, Department $department)
    {
        $buses = $company->buses()->get();
        $checked = $department->buses()->get()->pluck('id', 'id');
        return ['html' => view('admin.companies.departments.popups.content',
            compact('department', 'checked', 'buses') + ['entity' => $this->entity])->render()];
    }

    public function setDepartmentBus(Department $department)
    {
        $buses = request('buses');
        $department->buses()->detach();
        if($buses) {
            foreach($buses as $bus) {
                $model = $department->load('company')->company->buses()->whereId($bus)->first();
                if($model){
                    $model->departments()->syncWithoutDetaching($department->id);
                }
            }
        }

        return $this->responseSuccess();
    }
}
