<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
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
        $guard = false;
        if(Auth::user()->role->role_name == 'admin' || Auth::user()->role->role_name == 'employee'){
            $guard = true;
        }
        if (Auth::check() && $guard){ //add $guard to set to true
            return $next($request);
        }   
        return redirect('/');
    }
}
