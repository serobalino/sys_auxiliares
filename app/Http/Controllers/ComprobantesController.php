<?php

namespace App\Http\Controllers;

use App\Clientes\Cliente;
use App\Comprobante;
use Illuminate\Database\QueryException;
use Jenssegers\Date\Date;


class ComprobantesController extends Controller
{
    protected $registro;

    public function __construct(HistorialController $registro)
    {
        $this->registro     =   $registro;
    }
    /**
     * @param $id
     * @param null $desde
     * @param null $hasta
     * @param array $comprobantes
     * @return Comprobante
     */
    public function consulta ($id,$desde=null,$hasta=null,$comprobantes=[1,4,5,7]){
        if($desde!==null && $hasta===null){
            return Comprobante::where("id_cl",$id)
                ->orderBy("fecha_co","asc")
                ->orderBy("id_tc","desc")
                ->where('fecha_co','>=', $desde)
                ->whereIn('id_tc',$comprobantes)
                ->get();
        }
        if ($desde===null && $hasta!==null){
            return Comprobante::where("id_cl",$id)
                ->orderBy("fecha_co","asc")
                ->orderBy("id_tc","desc")
                ->where('fecha_co','<=', $hasta)
                ->whereIn('id_tc',$comprobantes)
                ->get();
        }
        if ($desde!==null && $hasta!==null){
            return Comprobante::where("id_cl",$id)
                ->orderBy("fecha_co","asc")
                ->orderBy("id_tc","desc")
                ->whereBetween('fecha_co', [$desde, $hasta])
                ->whereIn('id_tc',$comprobantes)
                ->get();
        }
        return Comprobante::where("id_cl",$id)
            ->orderBy("fecha_co","asc")
            ->orderBy("id_tc","desc")
            ->whereIn('id_tc',$comprobantes)
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
            $this->registro->log(auth()->user(),$cliente,"Comprobante Subído",$clave);
            return true;
        } catch (QueryException $e){
            return false;
        }
    }
}
