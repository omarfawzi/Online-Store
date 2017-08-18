<?php
/**
 * Created by PhpStorm.
 * User: Omar
 * Date: 18-Aug-17
 * Time: 8:15 PM
 */

namespace app\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class WebRedirect
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard('customer')->check()) {
            return back();
        }
        return $next($request);
    }
}