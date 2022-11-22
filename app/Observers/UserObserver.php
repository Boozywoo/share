<?php

namespace App\Observers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UserObserver
{
    public function creating(User $user)
    {
        $changed = $user->getDirty();
        $changedInterface = array_get($changed, 'interface_setting_id');

        $user->date_change_password = Carbon::now();

        if ($changedInterface) {
            Cache::put('user_' . Auth::id() . '_interface_setting', $user->interface_setting, 720);
        }

    }

    public function updating(User $user)
    {
        $changed = $user->getDirty();
        $changedPassword = array_get($changed, 'password');
        $changedInterface = array_get($changed, 'interface_setting_id');
        if ($changedPassword) {
            $user->date_change_password = Carbon::now();
        }
        if ($changedInterface) {
            Cache::put('user_' . Auth::id() . '_interface_setting', $user->interface_setting, 720);
        }
    }
}