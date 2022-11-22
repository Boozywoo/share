<?php

namespace App\Http\Middleware;

use App\Models\Driver;
use Closure;

class Client
{
    public function handle($request, Closure $next)
    {
        if (!auth()->user() || !auth()->user()->client) return abort(404);
        
        return $next($request);
    }
}
