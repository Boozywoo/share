<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(['email' => '6482222@gmail.com'],[
            'first_name' => 'Георгий',
            'middle_name' => '',
            'last_name' => '',
            'email' => '6482222@gmail.com',
            'phone' => '375296482222',
            'password' => '648severTM',
            'status' => User::STATUS_ACTIVE,
        ]);

        $superAdminRole = Role::where('slug', Role::ROLE_SUPER_ADMIN)->first();
        $superAdmin = User::where('email', '6482222@gmail.com')->first();
        $superAdmin->attachRole($superAdminRole);
    }
}
