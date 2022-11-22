<?php

namespace App\Jobs\User;

use App\Models\Company;
use App\Models\Role;
use App\Models\Route;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UserImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $users;

    public function __construct($users)
    {
        $this->users = $users;
    }

    public function handle()
    {
        foreach ($this->users as $user) {
            $role = Role::where('name', ucfirst($user['rol']))->first();
            $role = $role ? $role : Role::where('slug', 'operator')->first();
            if ($user['imya'] && $user['email'] && $user['telefon']) {
                $user['telefon'] = preg_replace('/[^0-9.]+/', '', $user['telefon']);
                $userData = [
                    'first_name' => $user['imya'],
                    'email' => $user['email'],
                    'phone' => $user['telefon'],
                    'password' => $user['parol'],
                    'status' => User::STATUS_ACTIVE,
                ];
                if ($user = User::where('phone', $user['telefon'])->first()) {
                    unset($userData['phone']);
                    $user->update($userData);
                } elseif ($user = User::where('email', $user['email'])->first()) {
                    unset($userData['email']);
                    $user->update($userData);
                } else {
                    $user = User::create($userData);
                }
                $user->attachRole($role);
                $user->routes()->sync(Route::all()->pluck('id')->toArray());
                $user->companies()->sync(Company::all()->pluck('id')->toArray());
            }
        }
    }
}
