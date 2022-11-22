<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Client;
class ApiClient
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
        if (get_class(auth()->user()) != Client::class) return ['result' => 'error', 'message' => 'invalid token'];
        return $next($request);
    }
}
