<?php

namespace App\Http\Middleware;

use Closure;

class SeleccionCliente
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        if(!session('cliente')){
            if ($request->expectsJson() || $request->ajax()){
                return response()->json(['return' => false,'url'=>route('adm.inicio')]);
            }else{
                return redirect(route('adm.inicio'));
            }
        }
        return $next($request);
    }
}
