<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsDriver
{
    public function handle($request, Closure $next, $guard = 'driver')
    {
        if (!Auth::guard($guard)->check()) {
            return redirect('/driver');
        } 
        return $next($request);
    }
}
