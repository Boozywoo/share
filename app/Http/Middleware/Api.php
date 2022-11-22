<?php

namespace App\Http\Middleware;

use Closure;

class Api
{
    public function handle($request, Closure $next)
    {
        auth()->shouldUse('api');
        return $next($request);
    }
}
