<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;

class ArchivosController extends Controller
{
    function store2(Request $request){
        $validator = Validator::make($request->all(), [
            'usuario' => 'required',
            'archivos' => 'required',
        ]);
        if ($validator->fails()) {
            return (['Error faltan campos']);
        } else {
            if (Storage::files()) {

            } else {

            }
        }
    }
    public function index(){
        $archivo=Storage::allfiles('/usuarios/1/');
        $files=[];
        foreach($archivo as $file){
            $xml0   =   simplexml_load_string(Storage::get($file));
            $xml1   =   simplexml_load_string($xml0->comprobante);
            $nmr    =   (string)($xml0->numeroAutorizacion);
            $xml2   =   $xml1->infoTributaria;
            $xml3   =   $xml1->infoFactura;
            $xml4   =   $xml1->detalles;
            $xml5   =   $xml1->infoAdicional;

            $json   =   json_encode($xml2);
            $json2  =   json_decode($json,TRUE);
            $json   =   json_encode($xml3);
            $json3  =   json_decode($json,TRUE);
            $json   =   json_encode($xml4);
            $json4  =   json_decode($json,TRUE);
            $json   =   json_encode($xml5);
            $json5  =   json_decode($json,TRUE);

            $comprobante['archivo']             =   $file;//archivo

            $comprador['nombre']                =   $json3['razonSocialComprador'];
            $comprador['tipo_documento']        =   $json3['tipoIdentificacionComprador'];
            $comprador['tipo_emision']          =   $json2['tipoEmision'];
            $comprador['cod']                   =   $json3['identificacionComprador'];

            $comprobante['comprador']           =   $comprador;

            $comprobante['tipo']                =   $json2['codDoc'];//comprar con la tabla numero 2
            $comprobante['establecimiento']     =   $json2['estab'];//secuencia de factura
            $comprobante['secuencial']          =   $json2['secuencial'];//secuencia del facturero
            $comprobante['punto_emision']       =   $json2['ptoEmi'];//punto de emision
            $comprobante['ambiente']            =   $json2['ambiente'];
            $comprobante['tipo_emision']        =   $json2['tipoEmision'];
            $comprobante['razon_social']        =   $json2['razonSocial'];
            $comprobante['nombre_comercial']    =   $json2['nombreComercial'];
            $comprobante['autorizacion']        =   $nmr;
            $comprobante['ruc']                 =   $json2['ruc'];

            if(@$json3['obligadoContabilidad']=='SI')
                $var                            =   true;
            else
                $var                            =   false;
            $comprobante['contabilidad']        =   $var;


            $var=null;
            if(@$json3['dirEstablecimiento'])
                $var                            =   $json3['dirEstablecimiento'];

            $comprobante['direccion']           =   $var;

            $comprobante['detalle']             =   $json4['detalle'];

            $comprobante['t_baseImponible']     =   (double)$json3['totalSinImpuestos'];
            $comprobante['t_descuento']         =   (double)$json3['totalDescuento'];
            $comprobante['t_propina']           =   (double)$json3['propina'];
            $comprobante['t_impuestos']         =   $json3['totalConImpuestos'];
            $comprobante['t_total']             =   (double)$json3['importeTotal'];
            $comprobante['t_monneda']           =   $json3['moneda'];
            $comprobante['formaPago']           =   @$json3['pagos'];

            $files[]=$comprobante;
        }
        return $files;
    }
    public function delete($usuario){
        if(Storage::deleteDirectory("usuarios/id_usuario/"))
            return(['Se elimino correctamente']);
        else
            return(['No se pudo eliminar los archivos del usuario']);
    }



    ///web
    public function subirArchivos(Request $request){

        $path =storage_path("app/usuarios/id_usuario/");
        $files = $request->file('file');
        $i=0;
        foreach($files as $file){
            $i++;
            $ext = $file->getClientOriginalExtension();
            $nom = $file->getClientOriginalName();
            $file->move($path,$nom);
        }
    }
    public function indexArchivos(){
        //algo
        return view('lista');
    }
}
