<?php

namespace App\Http\Middleware;

use App\Models\Driver;
use Closure;

class ApiDriver
{
    public function handle($request, Closure $next)
    {
        //if (get_class(auth()->user()) != Driver::class) return ['result' => 'error', 'message' => 'invalid token'];

        return $next($request);
    }
}
