<?php

use Illuminate\Database\Seeder;
use Bican\Roles\Models\Permission;
use App\Models\Role;


class updatepermissiontable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdminRole = Role::firstOrCreate(['name' => trans('admin.users.roles.' . Role::ROLE_SUPER_ADMIN), 'slug' => Role::ROLE_SUPER_ADMIN]);
        $viewCron = Permission::firstOrCreate([
            'name' => trans('admin.cron.title'),
            'slug' => 'view.cron',
        ]);
        $superAdminRole->attachPermission($viewCron);
    }
}
