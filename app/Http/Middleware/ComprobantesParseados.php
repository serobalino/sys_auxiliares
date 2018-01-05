<?php

namespace App\Http\Middleware;

use Closure;
//guardados de los clientes
use App\Egresos\FacturaAuxiliar;
use App\Egresos\NcreditoAuxiliar;

//produtos proveedores
use App\Egresos\ProductoProveedor;

use Illuminate\Support\Facades\DB;

class ComprobantesParseados
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){

        if(!session('cliente') ){
            if ($request->expectsJson() || $request->ajax()){
                return response()->json(['return' => false,'url'=>route('adm.inicio')]);
            }else{
                return redirect(route('adm.inicio'));
            }
        }else{
            $id     =   session('cliente')->id_cl;
            /* parseador por producto
            $a      =   DB::select("SELECT razons_pr,nombrec_pr,direccion_pr,fechae_ef,pp.detalle_pp,pp.version_pp,cantidad_df,punitario_pp,psimpuestos_pp,moneda_ef,codigo_tc
                                    FROM egresos NATURAL JOIN egresos_facturas NATURAL JOIN detalle_facturas df LEFT JOIN facturas_auxiliar fa ON df.detalle_pp=fa.detalle_pp NATURAL JOIN proveedores JOIN productos_proveedores pp ON df.detalle_pp=pp.detalle_pp AND df.version_pp=pp.version_pp
                                    WHERE fa.detalle_pp IS NULL AND egresos.id_cl=$id");
            */
            //parseador por proveedor
            $a      =   DB::select("SELECT razons_pr,nombrec_pr,direccion_pr,p.ruc_pr
                                    FROM egresos NATURAL JOIN proveedores p LEFT JOIN auxiliar_proveedor ap ON p.ruc_pr=ap.ruc_pr 
                                    WHERE ap.ruc_pr IS NULL AND egresos.id_cl=$id");
            $a_n    =   count($a);
            if($a_n!==0){
                session(['productos' => collect($a)]);
                return redirect(route('adm.cli.sel',session('cliente')->ruc_cl));
            }
        }
        return $next($request);
    }
}
