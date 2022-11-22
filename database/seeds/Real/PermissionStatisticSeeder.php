<?php

use App\Models\Role;
use Bican\Roles\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionStatisticSeeder extends Seeder
{
    /**
     * Attach permission to work in Interface settings
     *
     * @return void
     */
    public function run()
    {
        $superAdminRole = Role::firstOrCreate(['name' => trans('admin.users.roles.' . Role::ROLE_SUPER_ADMIN), 'slug' => Role::ROLE_SUPER_ADMIN]);
        $adminRole = Role::firstOrCreate(['name' => trans('admin.users.roles.' . Role::ROLE_ADMIN), 'slug' => Role::ROLE_ADMIN]);
        $operatorRole = Role::firstOrCreate(['name' => trans('admin.users.roles.' . Role::ROLE_OPERATOR), 'slug' => Role::ROLE_OPERATOR]);
        $agentRole = Role::firstOrCreate(['name' => trans('admin.users.roles.' . Role::ROLE_AGENT), 'slug' => Role::ROLE_AGENT]);
        $methodistRole = Role::firstOrCreate(['name' => trans('admin.users.roles.' . Role::ROLE_METHODIST), 'slug' => Role::ROLE_METHODIST]);
        $mediatorRole = Role::firstOrCreate(['name' => trans('admin.users.roles.' . Role::ROLE_MEDIATOR), 'slug' => Role::ROLE_MEDIATOR]);

        $viewAdminStatistic = Permission::firstOrCreate(
            [
            'slug' => 'view.statistics',
            ],
            [
            'name' => trans('admin_labels.statistics'),
            ]
        );

        $superAdminRole->attachPermission($viewAdminStatistic);
        $adminRole->attachPermission($viewAdminStatistic);
        $operatorRole->attachPermission($viewAdminStatistic);
        $agentRole->attachPermission($viewAdminStatistic);
        $methodistRole->attachPermission($viewAdminStatistic);
        $mediatorRole->attachPermission($viewAdminStatistic);
    }
}
