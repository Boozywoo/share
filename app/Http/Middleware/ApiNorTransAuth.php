<?php

namespace App\Http\Middleware;

use Closure;

class ApiNorTransAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user()) {
            return $next($request);
        } else {
            return response()->json(["result" => "error", "message" => __("messages.admin.auth.not_authorized")], 400);
        }

    }
}
