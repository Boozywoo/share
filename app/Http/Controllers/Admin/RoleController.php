<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Models\Role;
use App\Repositories\SelectRepository;
use App\Services\Role\RoleService;
use Bican\Roles\Models\Permission;

class RoleController extends Controller
{
    protected $select;
    protected $entity = 'settings.roles';

    public function __construct(SelectRepository $selectRepository)
    {
        $this->select = $selectRepository;
    }

    public function index()
    {
        $user = auth()->user();
        $roles = Role::whereIn('company_id', [0, $user->company_id])->paginate(10);
        if (request()->ajax() && !request('_pjax')) return $this->ajaxView($roles);

        if (request()->ajax() && !request('_pjax')) {
            return $this->ajaxView($roles);
        }


        return view("admin.$this->entity.index", compact('roles') + ['entity' => $this->entity]);
    }

    public function create()
    {
        $role = new Role();
        $allPermissions = Permission::all();
        $permissions = $this->splitPermissions($allPermissions, true);
        $separatePermissions = $this->splitPermissions($allPermissions, false);
        $company = auth()->user()->company_id ? auth()->user()->company_id : 0;
        return view("admin.$this->entity.edit", compact('role', 'permissions', 'separatePermissions', 'company') + ['entity' => $this->entity]);
    }

    public function edit(Role $role)
    {
        $company = $role->company_id;
        $allPermissions = Permission::all();
        $permissions = $this->splitPermissions($allPermissions, true);
        $separatePermissions = $this->splitPermissions($allPermissions, false);
        return view("admin.$this->entity.edit", compact('role', 'permissions', 'separatePermissions', 'company') + ['entity' => $this->entity]);

    }

    public function store(RoleRequest $request)
    {

        $service = new RoleService();
        if ($id = request('id')) {
            $role = Role::find($id);
            $role->update(request()->all());
            $service->storePermission(request('permissions'), $role);
        } else {
            $role = Role::create(request()->all());
            $service->storePermission(request('permissions'), $role);

        }

        return $this->responseSuccess();
    }

    public function delete(Role $role)
    {
        $service = new RoleService();
        if (!$role->users()->count() && $role->company_id != 0) {
            $service->delete($role);
            return $this->responseSuccess();
        } else {
            return $this->responseError();
        }
    }

    protected function ajaxView($roles)
    {
        return response([
            'view' => view('admin.' . $this->entity . '.index.table', compact('roles') + ['entity' => $this->entity])->render(),
            'pagination' => view('admin.partials.pagination', ['paginator' => $roles])->render(),
        ])->header('Cache-Control', 'no-cache, no-store');
    }

    private function splitPermissions($allPermissions, bool $general)
    {
        return $allPermissions->filter(function ($item) use ($general) {
            if ($item->slug == 'view.notifications.hr') {
                return !$general;
            }
            $arrSlug = explode('.', $item->slug);
            if ($arrSlug[1] == 'repair') {
                return !$general;
            }
            return $general;
        });
    }

}
