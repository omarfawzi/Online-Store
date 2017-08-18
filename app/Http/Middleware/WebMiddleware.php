<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;


class WebMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!Auth::guard('customer')->check())
            return redirect()->back()->withErrors(['login'=>'try']);
        return $next($request);
    }
}