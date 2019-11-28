<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Comprobante;
use Illuminate\Database\QueryException;
use Jenssegers\Date\Date;


class ComprobantesController extends Controller
{
    /**
     * @param $id
     * @param null $desde
     * @param null $hasta
     * @param array $comprobantes
     * @return Comprobante
     */
    public function consulta ($id,$desde=null,$hasta=null,$comprobantes=[1]){
        if($desde!==null && $hasta===null){
            return Comprobante::where("id_cl",$id)
                ->orderBy("fecha_co","asc")
                ->orderBy("id_tc","desc")
                ->where('fecha_co','>=', $desde)
                ->where('id_tc',$comprobantes)
                ->get();
        }
        if ($desde===null && $hasta!==null){
            return Comprobante::where("id_cl",$id)
                ->orderBy("fecha_co","asc")
                ->orderBy("id_tc","desc")
                ->where('fecha_co','<=', $hasta)
                ->where('id_tc',$comprobantes)
                ->get();
        }
        if ($desde!==null && $hasta!==null){
            return Comprobante::where("id_cl",$id)
                ->orderBy("fecha_co","asc")
                ->orderBy("id_tc","desc")
                ->whereBetween('fecha_co', [$desde, $hasta])
                ->where('id_tc',$comprobantes)
                ->get();
        }
        return Comprobante::where("id_cl",$id)
            ->orderBy("fecha_co","asc")
            ->orderBy("id_tc","desc")
            ->where('id_tc',$comprobantes)
            ->get();
    }

    /**
     * @param $clave
     * @param Cliente $cliente
     * @param $objeto
     * @return bool
     */
    public function guardar($clave,Cliente $cliente,$objeto){
        try{
            $comprobante                =   new Comprobante();
            $comprobante->id_co         =   $clave;
            $comprobante->id_tc         =   (int) $objeto->infoTributaria->codDoc;
            $comprobante->id_cl         =   $cliente->id_cl;
            $comprobante->fecha_co      =   Date::createFromFormat('d/m/Y',$objeto->info->fechaEmision)->format("Y-m-d");
            $comprobante->estado_co     =   true;
            $comprobante->comprobante   =   $objeto;
            $comprobante->save();
            return true;
        } catch (QueryException $e){
            return false;
        }
    }
}
