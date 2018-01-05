<?php

namespace App\Http\Controllers\Electronicas;

use App\Egresos\Factura;
use App\Egresos\FacturaAuxiliar;
use App\Egresos\Recibido;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ParsearController extends Controller{
    public function clasificar(){
        $id =   session('cliente')->id_cl;
        $a  =   DB::select("SELECT razons_pr,nombrec_pr,direccion_pr,fechae_ef,detalle_pp,cantidad_df,punitario_pp,psimpuestos_pp
                          FROM egresos NATURAL JOIN egresos_facturas NATURAL JOIN detalle_facturas NATURAL JOIN proveedores NATURAL JOIN productos_proveedores
                          WHERE id_cl=$id");
        $b  =   FacturaAuxiliar::where('id_cl',$id)->count();

        return view('basico.parsearComprobantes');
    }

    public function generar(){
        return 'Puede elegir';
    }
}
