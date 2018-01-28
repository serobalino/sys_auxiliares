<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null){
        if(Auth::guard($guard)->check()){
            switch ($guard){
                case    'adm':
                    return redirect(route("adm.inicio"));
                    break;
                case    'cli':
                    return redirect(route("cli.inicio"));
                    break;
                default      :
                    return redirect(route("comun.login"));
                    break;
            }
        }
        return $next($request);
    }
}
