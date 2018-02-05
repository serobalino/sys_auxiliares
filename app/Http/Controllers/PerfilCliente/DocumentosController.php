<?php

namespace App\Http\Controllers\PerfilCliente;

use App\doc_cliente;
use App\Tablas\t_documento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DocumentosController extends Controller{
    function index(){
        $a  =   session()->get('cliente');
        $id=$a->id_cl;
        $documentos =   t_documento::leftjoin('documentos_clientes','documentos_clientes.codigo_td','=','tipo_documento.codigo_td')->where('documentos_clientes.id_cl',$a->id_cl)->get();
        //$tipos      =   t_documento::leftJoin('documentos_clientes','documentos_clientes.codigo_td','=','tipo_documento.codigo_td')->where('documentos_clientes.id_cl',$a->id_cl)->orwhereNull('documentos_clientes.codigo_td')->get();


        $tipos      =   DB::select("SELECT tipo_documento.codigo_td,tipo_documento.descripcion_td
                                    FROM tipo_documento LEFT JOIN documentos_clientes ON tipo_documento.codigo_td=documentos_clientes.codigo_td AND documentos_clientes.id_cl=$id
                                    WHERE  documentos_clientes.codigo_td IS NULL");
        $todo['tiene']      =   $documentos;
        $todo['puede']      =   $tipos;

        $todo['usuario']    =   $a;
        //return $todo;

        return view('basico.AdmCliente.documentosCliente',$todo);
    }
    public function store(Request $datos){
        $a  =   session()->get('cliente');
        $nuevo  =   new doc_cliente();
        $nuevo->codigo_td   =   $datos->codigo;
        $nuevo->id_cl       =   $a->id_cl;
        $nuevo->num_dc      =   $datos->comp;
        $nuevo->save();
        return redirect(route('adm.cli.per.doc',$a->ruc_cl));
    }

    public function verificar($ruc){
        $validar    =   doc_cliente::where('num_doc',$ruc)->first();
        if($validar){
            return ([
                'val'       =>  false,
                'mensaje'   =>  'Ya existe un contribuyente con este RUC ',$validar->es->razon_cl
            ]);
        }else{
            return ([
                'val'       =>  true
            ]);
        }
    }
}
