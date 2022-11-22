<?php

namespace App\Services\Role;



use App\Models\Role;
use Bican\Roles\Models\Permission;

class RoleService
{

    public function storePermission($permissions, $role)
    {
        $role->detachAllPermissions();
        foreach ($permissions as $key=>$permission){
            if($permission == 1){
                $role->attachPermission(Permission::find($key));
            }
        }
        return null;
    }

    public function delete($role){

        if(!$role->users()->count() && $role->company_id != 0){
            $role->detachAllPermissions();
            $role->delete();
        }
        return null;
    }


}
