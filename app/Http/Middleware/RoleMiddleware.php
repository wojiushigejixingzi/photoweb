<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class RoleMiddleware
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
        if (false == empty(Auth::user()) && Auth::user()->role != "admin") {
            session()->flash('danger','您不是管理员');
            return redirect('index.php/login');
        }
        return $next($request);
    }
}
