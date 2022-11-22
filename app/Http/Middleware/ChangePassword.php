<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;

class ChangePassword
{
    public function handle($request, Closure $next)
    {
        if ($authUser = auth()->user()) {
            if(Carbon::now()->diffInMonths($authUser->date_change_password) > 2) {
                return redirect(route('admin.auth.changePassword'));
            }
        }

        return $next($request);
    }
}
