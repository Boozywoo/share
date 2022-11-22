<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Bican\Roles\Models\Permission;
use Bican\Roles\Models\Role;

class PermissionController extends Controller
{
	protected $entity = 'users.permissions';

	public function index()
	{
        //$roles = Role::all();
		$roles = Role::where('name','<>', 'Супер админ')->where('company_id', '=', 0)->get();
		$permissions = Permission::all();
		return view('admin.' . $this->entity . '.index', compact('roles', 'permissions') + ['entity' => $this->entity]);
	}

	public function store()
	{
        //$roles = Role::all();
        $roles = Role::where('name','<>', 'Супер админ')->get();
		$permissions = request('permissions', []);
		foreach($roles as $role) {
			$rolePermissions = isset($permissions[$role->id]) ? $permissions[$role->id] : [];
			$role->permissions()->sync($rolePermissions);
		}

		return $this->responseSuccess();
	}
}
