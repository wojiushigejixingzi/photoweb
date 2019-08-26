<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *如果不是管理员则提示无权限
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (false == empty(Auth::user()) && Auth::user()->role != "admin") {
            return redirect('login');
        }
            return $next($request);
    }
}
