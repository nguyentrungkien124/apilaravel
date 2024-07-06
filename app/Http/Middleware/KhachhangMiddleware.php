<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class KhachhangMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {   
        if (auth()->guard('cus')->check()) {
            return $next($request);
        }
        return redirect()->route('account.login');
    }
}
