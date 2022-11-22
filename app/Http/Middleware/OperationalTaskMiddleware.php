<?php

namespace App\Http\Middleware;

use App\Models\Department;
use Closure;
use Illuminate\Support\Facades\Auth;

class OperationalTaskMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!Department::where('director_id', Auth::id())->exists()){
            abort(404);
        }

        return $next($request);
    }
}
