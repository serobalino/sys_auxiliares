<?php

namespace App\Http\Controllers\Electronicas;

use App\Http\Controllers\Controller;
use App\Tablas\c_retencion;
use App\Tablas\PorcentajeRetencion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Lang;

//modelos
use App\Tablas\t_comprobante;
use App\Tablas\c_especial;
use App\Tablas\Porcentaje;
use App\Tablas\Impuesto;

use App\Egresos\Recibido;

use App\Egresos\Factura;
use App\Egresos\DetalleFactura;
use App\Egresos\ImpuestoFactura;
use App\Egresos\ProductoProveedor;
use App\Egresos\ImpuestoProducto;

use App\Egresos\Ncredito;
use App\Egresos\ImpuestoCredito;
use App\Egresos\DetalleNcredito;
use App\Egresos\CreditoProveedor;
use App\Egresos\ImpuestoNcredito;

use App\Egresos\Ndebito;
use App\Egresos\DetalleNdebito;
use App\Egresos\DebitoProveedor;
use App\Egresos\ImpuestoNdebito;

use App\Egresos\Retencion;
use App\Egresos\DetalleRetencion;

use App\doc_cliente;
use App\Proveedor;
use App\Pemision;
use App\Sucursal;


class ProcesarController extends Controller{
    public function vista(){
        $a  =   session()->get('cliente');
        return view('basico.procesando',$a);
    }
    public function procesar(){
        $cliente        =   session()->get('cliente')->id_cl;


        $comprobantes   =   t_comprobante::all();
        $documentos     =   doc_cliente::select('num_dc')->where('id_cl',$cliente)->get();


        $id         =   Auth::guard('adm')->user()->id_ad;
        $archivo    =   Storage::allfiles("/usuarios/$id/");
        $files=[];
        foreach($archivo as $file){
            $files[]=$this->comprobante($file,$documentos,$comprobantes);
            Storage::delete("/usuarios/$id/$file");
        }
        //Storage::deleteDirectory("/usuarios/$id/");//elimina las facturas subidas
        return $files;
    }

    /*
     *  @file           archivo xml guardado
     *  @documentos     query con todos los documentos del cliente
     *  @compronantes   query con todos los comprobantes posibles del sistema
     */
    private function comprobante($file,$documentos,$comprobantes){
        $id     =   Auth::guard('adm')->user()->id_ad;

        $xml0   =   simplexml_load_string(Storage::get($file));
        $xml1   =   @simplexml_load_string($xml0->comprobante);
        if($xml1) {
            $nmr = (string)($xml0->numeroAutorizacion);
            $xml2 = $xml1->infoTributaria;

            $json = json_encode($xml2);
            $json2 = json_decode($json, TRUE);
            Carbon::setLocale('es');
            setlocale(LC_TIME, 'esp');

            /*basica todos tienen*/
            $codigo_comprobante = (int)$json2['codDoc'];

            $comprobante['archivo'] = str_replace("usuarios/$id/", '', $file);//archivo

            $comprador['tipo_emision'] = $json2['tipoEmision'];
            //$comprobante['ambiente']            =   $json2['ambiente'];//mabiente produccion o en pruebas

            $comprobante['comprobante'] = $comprobantes->where('codigo_tc', $codigo_comprobante)->first()->nombre_tc;


            switch ($codigo_comprobante) {
                case 1:
                    //factura
                    $comprobante['soportado'] = true;
                    $xml3 = $xml1->infoFactura;
                    $xml4 = $xml1->detalles;
                    //$xml5   =   $xml1->infoAdicional;

                    $json = json_encode($xml3);
                    $json3 = json_decode($json, TRUE);
                    $json = json_encode($xml4);
                    $json4 = json_decode($json, TRUE);
                    //$json   =   json_encode($xml5);
                    //$json5  =   json_decode($json,TRUE);


                    $empresa['ruc'] = $json2['ruc'];
                    $empresa['razon'] = $json2['razonSocial'];
                    @$empresa['comercial'] = $json2['nombreComercial'];
                    $empresa['auto'] = $nmr;
                    $empresa['matriz'] = $json2['dirMatriz'];
                    @$empresa['direccion'] = $json3['dirEstablecimiento'];
                    $empresa['establecimiento'] = $json2['estab'];
                    $empresa['punto'] = $json2['ptoEmi'];//punto de emision
                    $empresa['secuencial'] = $json2['secuencial'];//secuencia del facturero
                    @$empresa['resolucion'] = $json3['contribuyenteEspecial'];
                    if (@$json3['obligadoContabilidad'] == 'SI')
                        $var = true;
                    else
                        $var = false;
                    $empresa['contabilidad'] = $var;


                    $comprobante['fecha'] = @Carbon::createFromFormat('d/m/Y', $json3['fechaEmision'])->formatLocalized('%d %B %Y');
                    $comprador['nombre'] = $json3['razonSocialComprador'];
                    $comprador['tipo_documento'] = $json3['tipoIdentificacionComprador'];
                    $comprobante['total'] = $json3['importeTotal'];

                    $comprador['cod'] = $json3['identificacionComprador'];

                    $egreso['secuencial'] = $empresa['secuencial'];
                    $egreso['tipo'] = $codigo_comprobante;
                    $egreso['documento'] = $comprador['tipo_documento'];
                    $egreso['emision'] = $comprador['tipo_emision'];
                    $egreso['contabilidad'] = $var;
                    $egreso['clave'] = $json2['claveAcceso'];
                    $egreso['autorizacion'] = $nmr;

                    $valores_factura = collect(
                        [
                            'fecha' => $json3['fechaEmision'],
                            'base' => $json3['totalSinImpuestos'],
                            'descuento' => $json3['totalDescuento'],
                            'propina' => $json3['propina'],
                            'impuestos' => $json3['totalConImpuestos']['totalImpuesto'],
                            'total' => $json3['importeTotal'],
                            'moneda' => @$json3['moneda'],
                        ]
                    );

                    if (count($documentos->where('num_dc', $comprador['cod']))) {
                        $comprador['propietario'] = true;
                        $egreso_clave = $this->codigo_empresa($empresa);//clave del documento ingresado
                        $comprobante['egreso'] = $this->egresos_comprobante($egreso_clave, $egreso);
                        $this->egresos_factura($egreso_clave, $egreso['secuencial'], $valores_factura, $json4['detalle']);
                    } else {
                        $a['mensaje'] = Lang::get('system.documents.nopertain');
                        $a['valor'] = false;
                        $comprador['propietario'] = false;
                        $comprobante['egreso'] = $a;
                        $comprobante['contribuyente'] = $comprador;
                    }
                    break;
                case 2:
                    //nota de venta
                    $comprobante['soportado'] = false;
                    break;
                case 3:
                    $comprobante['soportado'] = false;
                    //Liquidación de Compra de bienes o Prestación de servicios
                    break;
                case 4:
                    $comprobante['soportado'] = true;
                    //nota de credito *
                    $xml3 = $xml1->infoNotaCredito;
                    $xml4 = $xml1->detalles;
                    //$xml5   =   $xml1->infoAdicional;

                    $json = json_encode($xml3);
                    $json3 = json_decode($json, TRUE);
                    $json = json_encode($xml4);
                    $json4 = json_decode($json, TRUE);
                    //$json   =   json_encode($xml5);
                    //$json5  =   json_decode($json,TRUE);

                    $empresa['ruc'] = $json2['ruc'];
                    $empresa['razon'] = $json2['razonSocial'];
                    @$empresa['comercial'] = $json2['nombreComercial'];
                    $empresa['auto'] = $nmr;
                    $empresa['matriz'] = $json2['dirMatriz'];
                    @$empresa['direccion'] = $json3['dirEstablecimiento'];
                    $empresa['establecimiento'] = $json2['estab'];
                    $empresa['punto'] = $json2['ptoEmi'];//punto de emision
                    $empresa['secuencial'] = $json2['secuencial'];//secuencia del facturero
                    @$empresa['resolucion'] = $json3['contribuyenteEspecial'];
                    if (@$json3['obligadoContabilidad'] == 'SI')
                        $var = true;
                    else
                        $var = false;
                    $empresa['contabilidad'] = $var;

                    $comprobante['fecha'] = Carbon::createFromFormat('d/m/Y', $json3['fechaEmision'])->formatLocalized('%d %B %Y');
                    $comprador['nombre'] = $json3['razonSocialComprador'];
                    $comprador['tipo_documento'] = $json3['tipoIdentificacionComprador'];
                    $comprador['cod'] = $json3['identificacionComprador'];
                    $comprobante['total'] = $json3['valorModificacion'];

                    $egreso['secuencial'] = $empresa['secuencial'];
                    $egreso['tipo'] = $codigo_comprobante;
                    $egreso['documento'] = $comprador['tipo_documento'];
                    $egreso['emision'] = $comprador['tipo_emision'];
                    $egreso['contabilidad'] = $var;
                    $egreso['clave'] = $json2['claveAcceso'];
                    $egreso['autorizacion'] = $nmr;

                    $valores_ncredito = collect(
                        [
                            'fecha' => Carbon::createFromFormat('d/m/Y', $json3['fechaEmision'])->toDateString(),
                            'base' => $json3['totalSinImpuestos'],
                            'modificado' => $json3['valorModificacion'],
                            'doc_modificado' => $json3['codDocModificado'],
                            'num_modificado' => $json3['numDocModificado'],
                            'fec_modificado' => Carbon::createFromFormat('d/m/Y', $json3['fechaEmisionDocSustento'])->toDateString(),
                            'impuestos' => $json3['totalConImpuestos']['totalImpuesto'],
                            'moneda' => @$json3['moneda'],
                            'motivo' => $json3['motivo'],
                        ]
                    );


                    if (count($documentos->where('num_dc', $comprador['cod']))) {
                        $comprador['propietario'] = true;
                        $egreso_clave = $this->codigo_empresa($empresa);//clave del documento ingresado
                        $comprobante['egreso'] = $this->egresos_comprobante($egreso_clave, $egreso);
                        $this->egresos_ncredito($egreso_clave, $egreso['secuencial'], $valores_ncredito, $json4['detalle']);
                    } else {
                        $a['mensaje'] = Lang::get('system.documents.nopertain');
                        $a['valor'] = false;
                        $comprador['propietario'] = false;
                        $comprobante['egreso'] = $a;
                        $comprobante['contribuyente'] = $comprador;
                    }
                    break;
                case 5:
                    $comprobante['soportado'] = true;
                    //nota de debito *
                    $xml3 = $xml1->infoNotaDebito;
                    $xml4 = $xml1->motivos;
                    $xml5 = $xml1->infoAdicional;

                    $json = json_encode($xml3);
                    $json3 = json_decode($json, TRUE);
                    $json = json_encode($xml4);
                    $json4 = json_decode($json, TRUE);
                    $json = json_encode($xml5);
                    $json5 = json_decode($json, TRUE);

                    $empresa['ruc'] = $json2['ruc'];
                    $empresa['razon'] = $json2['razonSocial'];
                    @$empresa['comercial'] = $json2['nombreComercial'];
                    $empresa['auto'] = $nmr;
                    $empresa['matriz'] = $json2['dirMatriz'];
                    @$empresa['direccion'] = $json3['dirEstablecimiento'];
                    $empresa['establecimiento'] = $json2['estab'];
                    $empresa['punto'] = $json2['ptoEmi'];//punto de emision
                    $empresa['secuencial'] = $json2['secuencial'];//secuencia del facturero
                    @$empresa['resolucion'] = $json3['contribuyenteEspecial'];
                    if (@$json3['obligadoContabilidad'] == 'SI')
                        $var = true;
                    else
                        $var = false;
                    $empresa['contabilidad'] = $var;

                    $comprobante['fecha'] = Carbon::createFromFormat('d/m/Y', $json3['fechaEmision'])->formatLocalized('%d %B %Y');
                    $comprador['nombre'] = $json3['razonSocialComprador'];
                    $comprador['tipo_documento'] = $json3['tipoIdentificacionComprador'];
                    $comprador['cod'] = $json3['identificacionComprador'];
                    $comprobante['total'] = $json3['valorTotal'];

                    $egreso['secuencial'] = $empresa['secuencial'];
                    $egreso['tipo'] = $codigo_comprobante;
                    $egreso['documento'] = $comprador['tipo_documento'];
                    $egreso['emision'] = $comprador['tipo_emision'];
                    $egreso['contabilidad'] = $var;
                    $egreso['clave'] = $json2['claveAcceso'];
                    $egreso['autorizacion'] = $nmr;


                    $valores_ndebito = collect(
                        [
                            'fecha' => Carbon::createFromFormat('d/m/Y', $json3['fechaEmision'])->toDateString(),
                            'total' => $json3['valorTotal'],
                            'base' => $json3['totalSinImpuestos'],
                            'doc_modificado' => $json3['codDocModificado'],
                            'num_modificado' => $json3['numDocModificado'],
                            'fec_modificado' => Carbon::createFromFormat('d/m/Y', $json3['fechaEmisionDocSustento'])->toDateString(),
                            'impuestos' => $json3['impuestos']['impuesto'],
                        ]
                    );

                    if (count($documentos->where('num_dc', $comprador['cod']))) {
                        $comprador['propietario'] = true;
                        $egreso_clave = $this->codigo_empresa($empresa);//clave del documento ingresado
                        $comprobante['egreso'] = $this->egresos_comprobante($egreso_clave, $egreso);
                        $this->egresos_ndebito($egreso_clave, $egreso['secuencial'], $valores_ndebito, $json4['motivo']);
                    } else {
                        $a['mensaje'] = Lang::get('system.documents.nopertain');
                        $a['valor'] = false;
                        $comprador['propietario'] = false;
                        $comprobante['egreso'] = $a;
                        $comprobante['contribuyente'] = $comprador;
                    }

                    $comprobante['contribuyente'] = $comprador;


                    break;
                case 6:
                    $comprobante['soportado'] = false;
                    //guia de remision
                    break;
                case 7:
                    $comprobante['soportado'] = true;
                    //comprobante de retencion *
                    $xml3 = $xml1->infoCompRetencion;
                    $xml4 = $xml1->impuestos;
                    $xml5 = $xml1->infoAdicional;

                    $json = json_encode($xml3);
                    $json3 = json_decode($json, TRUE);
                    $json = json_encode($xml4);
                    $json4 = json_decode($json, TRUE);
                    $json = json_encode($xml5);
                    $json5 = json_decode($json, TRUE);

                    $empresa['ruc'] = $json2['ruc'];
                    $empresa['razon'] = $json2['razonSocial'];
                    @$empresa['comercial'] = $json2['nombreComercial'];
                    $empresa['auto'] = $nmr;
                    $empresa['matriz'] = $json2['dirMatriz'];
                    @$empresa['direccion'] = $json3['dirEstablecimiento'];
                    $empresa['establecimiento'] = $json2['estab'];
                    $empresa['punto'] = $json2['ptoEmi'];//punto de emision
                    $empresa['secuencial'] = $json2['secuencial'];//secuencia del facturero
                    @$empresa['resolucion'] = $json3['contribuyenteEspecial'];
                    if (@$json3['obligadoContabilidad'] == 'SI')
                        $var = true;
                    else
                        $var = false;
                    $empresa['contabilidad'] = $var;


                    $comprobante['fecha'] = Carbon::createFromFormat('d/m/Y', $json3['fechaEmision'])->formatLocalized('%d %B %Y');
                    $comprador['nombre'] = $json3['razonSocialSujetoRetenido'];
                    $comprador['tipo_documento'] = $json3['tipoIdentificacionSujetoRetenido'];
                    $comprador['cod'] = $json3['identificacionSujetoRetenido'];

                    $egreso['secuencial'] = $empresa['secuencial'];
                    $egreso['tipo'] = $codigo_comprobante;
                    $egreso['documento'] = $comprador['tipo_documento'];
                    $egreso['emision'] = $comprador['tipo_emision'];
                    $egreso['contabilidad'] = $var;
                    $egreso['clave'] = $json2['claveAcceso'];
                    $egreso['autorizacion'] = $nmr;


                    $valores_retencion = collect(
                        [
                            'fecha' => Carbon::createFromFormat('d/m/Y', $json3['fechaEmision'])->toDateString(),
                            'pfiscal' => Carbon::createFromFormat('m/Y', $json3['periodoFiscal'])->toDateString(),
                        ]
                    );

                    if (count($documentos->where('num_dc', $comprador['cod']))) {
                        $comprador['propietario'] = true;
                        $egreso_clave = $this->codigo_empresa($empresa);//clave del documento ingresado
                        $comprobante['egreso'] = $this->egresos_comprobante($egreso_clave, $egreso);
                        $this->egresos_retencion($egreso_clave, $egreso['secuencial'], $valores_retencion, $json4['impuesto']);
                    } else {
                        $a['mensaje'] = Lang::get('system.documents.nopertain');
                        $a['valor'] = false;
                        $comprador['propietario'] = false;
                        $comprobante['egreso'] = $a;
                        $comprobante['contribuyente'] = $comprador;
                    }
                    break;
                case 8:
                    $comprobante['soportado'] = false;
                    //entradas a espectaculos publicos
                    break;
            }
        }else{
            $comprobante['archivo'] = str_replace("usuarios/$id/", '', $file);//archivo
            $comprador['propietario'] = false;
            $comprador['nombre']='Error de archivo';
            $comprobante['contribuyente'] = $comprador;
            $egreso['mensaje']='Erros de archivo XML';
            $comprobante['egreso'] = $egreso;
        }
        return $comprobante;
    }



    /*
     * Funcion para insertar empresas
     * inserta sucursales
     * y puntos de emision
     *
     * */
    private function codigo_empresa($empresa){
        $proveedor  =   Proveedor::find($empresa['ruc']);
        if($proveedor){
            if($proveedor->contabilidad_pr!=$empresa['contabilidad']){
                $proveedor->contabilidad_pr=$empresa['contabilidad'];
                $proveedor->save();
            }
        }else{
            $proveedor  =   new Proveedor();
            $proveedor->ruc_pr            =   $empresa['ruc'];
            $proveedor->contabilidad_pr   =   $empresa['contabilidad'];
            $proveedor->razons_pr         =   $empresa['razon'];
            $proveedor->nombrec_pr        =   $empresa['comercial'];
            $proveedor->direccion_pr      =   $empresa['matriz'];
            $proveedor->save();
        }
        $sucursal   =   Sucursal::where('ruc_pr',$proveedor->ruc_pr)->where('codigo_su',$empresa['establecimiento'])->first();
        if($sucursal){

        }else{
            $especial   =   c_especial::find($empresa['resolucion']);
            if(!$especial){
                if($empresa['resolucion']){
                    $especial   =   new c_especial();
                    $especial->codigo_rc    =   $empresa['resolucion'];
                    $especial->save();
                    $a  =   $especial->codigo_rc;
                }else
                    $a  =   null;
            }else
                $a  =   $especial->codigo_rc;

            $sucursal   =   new Sucursal();
            $sucursal->codigo_su    =   $empresa['establecimiento'];
            $sucursal->ruc_pr       =   $proveedor->ruc_pr;
            $sucursal->codigo_rc    =   $a;
            $sucursal->direccion_su =   $empresa['direccion'];
            $sucursal->save();
        }

        $emision    =   Pemision::where('ruc_pr',$proveedor->ruc_pr)->where('codigo_su',$sucursal->codigo_su)->where('pemision_ac',$empresa['punto'])->first();
        if(!$emision){
            $emision    =   new  Pemision();
            $emision->pemision_ac   =   $empresa['punto'];
            $emision->codigo_su     =   $sucursal->codigo_su;
            $emision->ruc_pr        =   $proveedor->ruc_pr;
            $emision->save();
        }

        $auxiliar   =  collect(
            [
                'r'=>$emision->ruc_pr,
                's'=>$emision->codigo_su,
                'p'=>$emision->pemision_ac,
            ]
        );
        return  $auxiliar;
    }
    /*
     * funcion que guarda el comprobante
     *  array @empresa
     *  array @egreso
     *  devuleve si se inserto y con el mensaje
     *
     * */
    private function egresos_comprobante($empresa,$egreso){
        $recibido   =   Recibido::where('secuencial_eg',$egreso['secuencial'])->where('ruc_pr',$empresa['r'])->where('codigo_su',$empresa['s'])->where('pemision_ac',$empresa['p'])->first();
        $a['mensaje']   =   Lang::get('system.documents.exists');
        $a['valor']     =   false;
        if(!$recibido){
            $recibido   =   new Recibido();
            $recibido->secuencial_eg    =   $egreso['secuencial'];
            $recibido->id_cl            =   session()->get('cliente')->id_cl;
            $recibido->codigo_tc        =   $egreso['tipo'];
            $recibido->id_ti            =   1;//electronico
            $recibido->codigo_te        =   $egreso['emision'];
            $recibido->codigo_td        =   $egreso['documento'];
            $recibido->pemision_ac      =   $empresa['p'];
            $recibido->codigo_su        =   $empresa['s'];
            $recibido->ruc_pr           =   $empresa['r'];
            $recibido->contabilidad_eg  =   $egreso['contabilidad'];
            $recibido->claveaceso_eg    =   $egreso['clave'];
            $recibido->autorizacion_eg  =   $egreso['autorizacion'];
            $recibido->id_ad            =   Auth::guard('adm')->user()->id_ad;
            $recibido->save();
            $a['mensaje']   =   Lang::get('system.documents.success');
            $a['valor']     =   true;
        }
        return $a;
    }

    /*
     * funcion que guarda facturas y los productos de los proveedores
     *
     * */
    private function egresos_factura($clave,$secuencial,$info,$detalles){
        $factura    =   Factura::where('secuencial_eg',$secuencial)->where('ruc_pr',$clave['r'])->where('codigo_su',$clave['s'])->where('pemision_ac',$clave['p'])->first();
        if(!$factura){
            $factura    =   new Factura();
            $factura->secuencial_eg    =   $secuencial;
            $factura->pemision_ac      =   $clave['p'];
            $factura->codigo_su        =   $clave['s'];
            $factura->ruc_pr           =   $clave['r'];
            $factura->fechae_ef        =   Carbon::createFromFormat('d/m/Y',$info['fecha'])->toDateString();//fecha de emision
            $factura->tsimpuestos_ef   =   $info['base'];//total sin impuestos
            $factura->tdescuento_ef    =   $info['descuento'];//descuento
            $factura->propina_ef       =   $info['propina'];//propina
            $factura->itotal_ef        =   $info['total'];//importe total
            $factura->moneda_ef        =   @$info['moneda'];//moneda
            $factura->save();

            $suma_impuestos            =    0.0;
            if(isset($info['impuestos'][0])){
                $i=0;
                foreach($info['impuestos'] as $fimpuesto){
                    $impuesto   =   new ImpuestoFactura();
                    $impuesto->secuencial_eg=   $factura->secuencial_eg;
                    $impuesto->pemision_ac  =   $factura->pemision_ac;
                    $impuesto->codigo_su    =   $factura->codigo_su;
                    $impuesto->ruc_pr       =   $factura->ruc_pr;
                    $impuesto->codigo_po    =   $this->codigo_porcentaje($info['impuestos'][$i]['codigoPorcentaje']);
                    $impuesto->codigo_im    =   $this->codigo_impuesto($info['impuestos'][$i]['codigo']);
                    $impuesto->valor_if     =   $info['impuestos'][$i]['valor'];
                    $impuesto->basei_if     =   $info['impuestos'][$i]['baseImponible'];
                    $impuesto->tarifa_if    =   @$info['impuestos'][$i]['descuentoAdicional'];
                    $impuesto->save();
                    $suma_impuestos        +=   $impuesto->valor_if;
                    unset($impuesto);
                    $i++;
                }
            }else{
                $impuesto   =   new ImpuestoFactura();
                $impuesto->secuencial_eg=   $factura->secuencial_eg;
                $impuesto->pemision_ac  =   $factura->pemision_ac;
                $impuesto->codigo_su    =   $factura->codigo_su;
                $impuesto->ruc_pr       =   $factura->ruc_pr;
                $impuesto->codigo_po    =   $this->codigo_porcentaje($info['impuestos']['codigoPorcentaje']);
                $impuesto->codigo_im    =   $this->codigo_impuesto($info['impuestos']['codigo']);
                $impuesto->valor_if     =   $info['impuestos']['valor'];
                $impuesto->basei_if     =   $info['impuestos']['baseImponible'];
                $impuesto->tarifa_if    =   @$info['impuestos']['descuentoAdicional'];
                $impuesto->save();
                $suma_impuestos        +=   $impuesto->valor_if;
            }
            Factura::where('secuencial_eg',$secuencial)->where('ruc_pr',$clave['r'])->where('codigo_su',$clave['s'])->where('pemision_ac',$clave['p'])->update(['timpuesto_ef'=>$suma_impuestos]);
            //aqui debe actualizar Factura con el total del impuesto calculado

            if(isset($detalles[0])){
                $i=0;
                foreach ($detalles as $aux){
                    $producto   =   ProductoProveedor::where('detalle_pp',$detalles[$i]['descripcion'])->get();
                    $version    =   count($producto)+1;
                    $producto   =   ProductoProveedor::where('ruc_pr',$factura->ruc_pr)->where('detalle_pp',$detalles[$i]['descripcion'])->where('punitario_pp',(double)$detalles[$i]['precioUnitario'])->first();
                    if(!$producto){
                        $producto   =   new ProductoProveedor();
                        $producto->codigo_pp        =   @$detalles[$i]['codigoPrincipal'];
                        $producto->version_pp       =   $version;
                        $producto->ruc_pr           =   $factura->ruc_pr;
                        $producto->cauxiliar_pp     =   @$detalles[$i]['codigoAuxiliar'];
                        $producto->detalle_pp       =   $detalles[$i]['descripcion'];
                        $producto->punitario_pp     =   $detalles[$i]['precioUnitario'];
                        $producto->descuento_pp     =   $detalles[$i]['descuento'];
                        $producto->psimpuestos_pp   =   $detalles[$i]['precioTotalSinImpuesto'];
                        $producto->save();
                        $this->impuesto_producto($producto,$detalles[$i]['impuestos']['impuesto']);
                    }
                    $this->detalle_factura($factura,$producto,(double)$detalles[$i]['cantidad']);
                    $i++;
                }
            }else{
                $producto   =   ProductoProveedor::where('detalle_pp',$detalles['descripcion'])->get();
                $version    =   count($producto)+1;
                $producto   =   ProductoProveedor::where('ruc_pr',$factura->ruc_pr)->where('detalle_pp',$detalles['descripcion'])->where('punitario_pp',(double)$detalles['precioUnitario'])->first();
                if(!$producto){
                    $producto   =   new ProductoProveedor();
                    $producto->codigo_pp        =   @$detalles['codigoPrincipal'];
                    $producto->version_pp       =   $version;
                    $producto->ruc_pr           =   $factura->ruc_pr;
                    $producto->cauxiliar_pp     =   @$detalles['codigoAuxiliar'];
                    $producto->detalle_pp       =   $detalles['descripcion'];
                    $producto->punitario_pp     =   $detalles['precioUnitario'];
                    $producto->descuento_pp     =   $detalles['descuento'];
                    $producto->psimpuestos_pp   =   $detalles['precioTotalSinImpuesto'];
                    $producto->save();
                    $this->impuesto_producto($producto,$detalles['impuestos']['impuesto']);
                }
                $this->detalle_factura($factura,$producto,(double)$detalles['cantidad']);
            }
        }
    }

    /*
     * funcion que guarda la nota de credito y los detalles de credito
     *
     * */
    private function egresos_ncredito($clave,$secuencial,$info,$detalles){
        $ncredito   =   Ncredito::where('secuencial_eg',$secuencial)->where('ruc_pr',$clave['r'])->where('codigo_su',$clave['s'])->where('pemision_ac',$clave['p'])->first();
        if(!$ncredito){
            $ncredito   =   new Ncredito();
            $ncredito->secuencial_eg        =   $secuencial;
            $ncredito->pemision_ac          =   $clave['p'];
            $ncredito->codigo_su            =   $clave['s'];
            $ncredito->ruc_pr               =   $clave['r'];
            $ncredito->fechae_enc           =   Carbon::createFromFormat('Y-m-d',$info['fecha'])->toDateString();//fecha de emision

            $ncredito->sustento_codigo_tc   =   (int)$info['doc_modificado'];

            $ncredito->vmodificado_enc      =   $info['modificado'];//descuento
            $ncredito->tsimpuestos_enc      =   $info['base'];//total sin impuestos
            $ncredito->motivo_enc           =   $info['motivo'];//propina
            $ncredito->moneda_enc           =   @$info['moneda'];//moneda
            $ncredito->nsustento_enc        =   $info['num_modificado'];//importe total
            $ncredito->fsustento_enc        =   $info['fec_modificado'];
            $ncredito->save();

            $suma_impuestos            =    0.0;
            if(isset($info['impuestos'][0])){
                $i=0;
                foreach($info['impuestos'] as $nimpuesto){
                    $impuesto   =   new ImpuestoNcredito();
                    $impuesto->secuencial_eg    =   $ncredito->secuencial_eg;
                    $impuesto->pemision_ac      =   $ncredito->pemision_ac;
                    $impuesto->codigo_su        =   $ncredito->codigo_su;
                    $impuesto->ruc_pr           =   $ncredito->ruc_pr;
                    $impuesto->codigo_im        =   $this->codigo_impuesto($info['impuestos'][$i]['codigo']);
                    $impuesto->codigo_po        =   $this->codigo_porcentaje($info['impuestos'][$i]['codigoPorcentaje']);
                    $impuesto->valor_inc        =   $info['impuestos'][$i]['valor'];
                    $impuesto->basei_inc        =   $info['impuestos'][$i]['baseImponible'];
                    $impuesto->save();
                    $suma_impuestos            +=   $impuesto->valor_inc;
                    unset($impuesto);
                    $i++;
                }
            }else{
                $impuesto   =   new ImpuestoNcredito();
                $impuesto->secuencial_eg    =   $ncredito->secuencial_eg;
                $impuesto->pemision_ac      =   $ncredito->pemision_ac;
                $impuesto->codigo_su        =   $ncredito->codigo_su;
                $impuesto->ruc_pr           =   $ncredito->ruc_pr;
                $impuesto->codigo_im        =   $this->codigo_impuesto($info['impuestos']['codigo']);
                $impuesto->codigo_po        =   $this->codigo_porcentaje($info['impuestos']['codigoPorcentaje']);
                $impuesto->valor_inc        =   $info['impuestos']['valor'];
                $impuesto->basei_inc        =   $info['impuestos']['baseImponible'];
                $impuesto->save();
                $suma_impuestos            +=   $impuesto->valor_inc;
            }
            Ncredito::where('secuencial_eg',$secuencial)->where('ruc_pr',$clave['r'])->where('codigo_su',$clave['s'])->where('pemision_ac',$clave['p'])->update(['timpuestos_enc'=>$suma_impuestos]);
            //aqui debe actualizar Factura con el total del impuesto calculado


            if(isset($detalles[0])){
                $i=0;
                foreach ($detalles as $aux){
                    $producto   =   CreditoProveedor::where('detalle_dp',$detalles[$i]['descripcion'])->get();
                    $version    =   count($producto)+1;
                    $producto   =   CreditoProveedor::where('ruc_pr',$ncredito->ruc_pr)->where('detalle_dp',$detalles[$i]['descripcion'])->where('punitario_dp',(double)$detalles[$i]['precioUnitario'])->first();
                    if(!$producto){
                        $producto   =   new CreditoProveedor();
                        $producto->codigo_dp        =   @$detalles[$i]['codigoInterno'];
                        $producto->version_dp       =   $version;
                        $producto->ruc_pr           =   $ncredito->ruc_pr;
                        $producto->cauxiliar_dp     =   @$detalles[$i]['codigoAuxiliar'];
                        $producto->detalle_dp       =   $detalles[$i]['descripcion'];
                        $producto->punitario_dp     =   $detalles[$i]['precioUnitario'];
                        $producto->descuento_dp     =   $detalles[$i]['descuento'];
                        $producto->psimpuestos_dp   =   $detalles[$i]['precioTotalSinImpuesto'];
                        $producto->save();
                        $this->impuesto_credito($producto,$detalles[$i]['impuestos']['impuesto']);
                    }
                    $this->detalle_ncredito($ncredito,$producto,(double)$detalles[$i]['cantidad']);
                    $i++;
                }
            }else{
                $producto   =   CreditoProveedor::where('detalle_dp',$detalles['descripcion'])->get();
                $version    =   count($producto)+1;
                $producto   =   CreditoProveedor::where('ruc_pr',$ncredito->ruc_pr)->where('detalle_dp',$detalles['descripcion'])->where('punitario_dp',(double)$detalles['precioUnitario'])->first();
                if(!$producto){
                    $producto   =   new CreditoProveedor();
                    $producto->codigo_dp        =   @$detalles['codigoInterno'];
                    $producto->version_dp       =   $version;
                    $producto->ruc_pr           =   $ncredito->ruc_pr;
                    $producto->cauxiliar_dp     =   @$detalles['codigoAuxiliar'];
                    $producto->detalle_dp       =   $detalles['descripcion'];
                    $producto->punitario_dp     =   $detalles['precioUnitario'];
                    $producto->descuento_dp     =   $detalles['descuento'];
                    $producto->psimpuestos_dp   =   $detalles['precioTotalSinImpuesto'];
                    $producto->save();
                    $this->impuesto_credito($producto,$detalles['impuestos']['impuesto']);
                }
                $this->detalle_ncredito($ncredito,$producto,(double)$detalles['cantidad']);
            }
        }
    }

    /*
     * funcion que guarda la nota de debito y los moivos de la nota de debito
     *
     * */
    private function egresos_ndebito($clave,$secuencial,$info,$motivos){
        $ndebito = Ndebito::where('secuencial_eg', $secuencial)->where('ruc_pr', $clave['r'])->where('codigo_su', $clave['s'])->where('pemision_ac', $clave['p'])->first();
        if (!$ndebito) {
            $ndebito = new Ndebito();
            $ndebito->secuencial_eg = $secuencial;
            $ndebito->pemision_ac = $clave['p'];
            $ndebito->codigo_su = $clave['s'];
            $ndebito->ruc_pr = $clave['r'];
            $ndebito->fechae_end = Carbon::createFromFormat('Y-m-d', $info['fecha'])->toDateString();//fecha de emision

            $ndebito->sustento_codigo_tc = (int)$info['doc_modificado'];

            $ndebito->tsimpuestos_end = $info['base'];//total sin impuestos
            $ndebito->vtotal_end = $info['total'];
            $ndebito->nsustento_end = $info['num_modificado'];
            $ndebito->fsustento_end = $info['fec_modificado'];
            $ndebito->save();

            $suma_impuestos = 0.0;
            if (isset($info['impuestos'][0])) {
                $i = 0;
                foreach ($info['impuestos'] as $nimpuesto) {
                    $impuesto = new ImpuestoNdebito();
                    $impuesto->secuencial_eg    = $ndebito->secuencial_eg;
                    $impuesto->pemision_ac      = $ndebito->pemision_ac;
                    $impuesto->codigo_su        = $ndebito->codigo_su;
                    $impuesto->ruc_pr           = $ndebito->ruc_pr;
                    $impuesto->codigo_im        = $this->codigo_impuesto($info['impuestos'][$i]['codigo']);
                    $impuesto->codigo_po        = $this->codigo_porcentaje($info['impuestos'][$i]['codigoPorcentaje']);
                    $impuesto->valor_ind        = $info['impuestos'][$i]['valor'];
                    $impuesto->basei_ind        = $info['impuestos'][$i]['baseImponible'];
                    $impuesto->tarifa_ind       = $info['impuestos'][$i]['tarifa'];
                    $impuesto->save();
                    $suma_impuestos            += $impuesto->valor_inc;
                    unset($impuesto);
                    $i++;
                }
            } else {
                $impuesto = new ImpuestoNdebito();
                $impuesto->secuencial_eg    = $ndebito->secuencial_eg;
                $impuesto->pemision_ac      = $ndebito->pemision_ac;
                $impuesto->codigo_su        = $ndebito->codigo_su;
                $impuesto->ruc_pr           = $ndebito->ruc_pr;
                $impuesto->codigo_im        = $this->codigo_impuesto($info['impuestos']['codigo']);
                $impuesto->codigo_po        = $this->codigo_porcentaje($info['impuestos']['codigoPorcentaje']);
                $impuesto->valor_ind        = $info['impuestos']['valor'];
                $impuesto->basei_ind        = $info['impuestos']['baseImponible'];
                $impuesto->tarifa_ind       = $info['impuestos']['tarifa'];
                $impuesto->save();
                $suma_impuestos            += $impuesto->valor_inc;
            }
            Ndebito::where('secuencial_eg', $secuencial)->where('ruc_pr', $clave['r'])->where('codigo_su', $clave['s'])->where('pemision_ac', $clave['p'])->update(['timpuestos_end'=>$suma_impuestos]);
            //actualiza el total de impuestos de la nota de credito

            if (isset($motivos[0])) {
                $i = 0;
                foreach ($motivos as $aux) {
                    $producto = DebitoProveedor::where('descripcion_mc', $motivos[$i]['razon'])->get();
                    $version = count($producto) + 1;
                    $producto = DebitoProveedor::where('ruc_pr', $ndebito->ruc_pr)->where('descripcion_mc', $motivos[$i]['razon'])->first();
                    if (!$producto) {
                        $producto = new DebitoProveedor();
                        $producto->descripcion_mc   = $motivos[$i]['razon'];
                        $producto->version_mc       = $version;
                        $producto->ruc_pr           = $ndebito->ruc_pr;
                        $producto->save();
                    }
                    $this->detalle_ndebito($ndebito,$producto,(double)$motivos[$i]['valor']);
                    $i++;
                }
            } else {
                $producto = DebitoProveedor::where('descripcion_mc', $motivos['razon'])->get();
                $version = count($producto) + 1;
                $producto = DebitoProveedor::where('ruc_pr', $ndebito->ruc_pr)->where('descripcion_mc', $motivos['razon'])->first();
                if (!$producto) {
                    $producto = new DebitoProveedor();
                    $producto->descripcion_mc   = $motivos['razon'];
                    $producto->version_mc       = $version;
                    $producto->ruc_pr           = $ndebito->ruc_pr;
                    $producto->save();
                }
                $this->detalle_ndebito($ndebito, $producto, (double)$motivos['valor']);
            }
        }
    }

    /*
     * funcion que guarda la retecion los impuestos de la misma
     * */
    private function egresos_retencion($clave,$secuencial,$info,$impuestos){
        $retencion = Retencion::where('secuencial_eg', $secuencial)->where('ruc_pr', $clave['r'])->where('codigo_su', $clave['s'])->where('pemision_ac', $clave['p'])->first();
        if(!$retencion){
            $retencion  =   new Retencion();
            $retencion->secuencial_eg   =   $secuencial;
            $retencion->pemision_ac     =   $clave['p'];
            $retencion->codigo_su       =   $clave['s'];
            $retencion->ruc_pr          =   $clave['r'];
            $retencion->pfiscal_er      =   $info['pfiscal'];
            $retencion->fechae_er       =   $info['fecha'];
            $retencion->save();
            $suma_impuestos = 0.0;

            if (isset($impuestos[0])) {
                $i = 0;
                foreach ($impuestos as $aux) {
                    $detalle  =   new DetalleRetencion();
                    $detalle->secuencial_eg       =   $retencion->secuencial_eg;
                    $detalle->pemision_ac         =   $retencion->pemision_ac;
                    $detalle->codigo_su           =   $retencion->codigo_su;
                    $detalle->ruc_pr              =   $retencion->ruc_pr;
                    $detalle->codigo_su           =   $retencion->codigo_su;

                    $detalle->codigo_im           =   $this->codigo_impuesto($impuestos[$i]['codigo']);
                    $detalle->codigo_pr           =   $this->codigo_porcentaje_renta($impuestos[$i]['porcentajeRetener']);
                    $detalle->codigo_re           =   $this->codigo_renta($impuestos[$i]['codigoRetencion']);

                    $detalle->sustento_ruc_pr     =   $retencion->ruc_pr;
                    $detalle->sustento_codigo_tc  =   (int)$impuestos[$i]['codDocSustento'];

                    $detalle->basei_ir            =   $impuestos[$i]['baseImponible'];
                    $detalle->vretenido_ir        =   $impuestos[$i]['valorRetenido'];
                    $detalle->nsustento_ir        =   @$impuestos[$i]['numDocSustento'];
                    $detalle->fsustento_ir        =   Carbon::createFromFormat('d/m/Y',$impuestos[$i]['fechaEmisionDocSustento'])->toDateString();
                    $detalle->save();
                    $suma_impuestos+=$detalle->vretenido_ir;
                    $i++;
                }
            } else {
                $detalle  =   new DetalleRetencion();
                $detalle->secuencial_eg       =   $retencion->secuencial_eg;
                $detalle->pemision_ac         =   $retencion->pemision_ac;
                $detalle->codigo_su           =   $retencion->codigo_su;
                $detalle->ruc_pr              =   $retencion->ruc_pr;
                $detalle->codigo_su           =   $retencion->codigo_su;

                $detalle->codigo_im           =   $this->codigo_impuesto($impuestos['codigo']);
                $detalle->codigo_pr           =   $this->codigo_porcentaje_renta($impuestos['porcentajeRetener']);
                $detalle->codigo_re           =   $this->codigo_renta($impuestos['codigoRetencion']);

                $detalle->sustento_ruc_pr     =   $retencion->ruc_pr;
                $detalle->sustento_codigo_tc  =   (int)$impuestos['codDocSustento'];

                $detalle->basei_ir            =   $impuestos['baseImponible'];
                $detalle->vretenido_ir        =   $impuestos['valorRetenido'];
                $detalle->nsustento_ir        =   @$impuestos['numDocSustento'];
                $detalle->fsustento_ir        =   Carbon::createFromFormat('d/m/Y',$impuestos['fechaEmisionDocSustento'])->toDateString();
                $detalle->save();
                $suma_impuestos+=$detalle->vretenido_ir;
            }
            //actualizar total de impuesto
            Retencion::where('secuencial_eg', $secuencial)->where('ruc_pr', $clave['r'])->where('codigo_su', $clave['s'])->where('pemision_ac', $clave['p'])->update(['timpuestos_er'=>$suma_impuestos]);
        }

    }

    /*
     * verificar si existe el codigo de porcettaje
     * */
    private function codigo_porcentaje($codigo){
        $valor =   Porcentaje::find((int)$codigo);
        if(!$valor){
            $valor  =   new Porcentaje();
            $valor->codigo_po   =   (int)$codigo;
            $valor->save();
        }
        return $valor->codigo_po;
    }

    /*
     * devuelve el codigo del impuesto en base al porcentaje
     *  NO ES FIABLE
     * */
    private function codigo_porcentaje_renta($porcentaje){
        $renta  =   PorcentajeRetencion::where('porcentaje_pr','=',(double)$porcentaje)->first();
        if(!$renta){
            $renta  =   new PorcentajeRetencion();
            //$renta->codigo_pr       =   (double)$porcentaje;
            $renta->porcentaje_pr   =   (double)$porcentaje;
            $renta->save();
        }
        return($renta->codigo_pr);
    }

    /*
     * verifica que exista un codigo de renta
     *
     * */
    private function codigo_renta($codigo){
        $renta  =   c_retencion::find($codigo);
        if(!$renta){
            $renta              =   new c_retencion();
            $renta->codigo_re   =   (string)$codigo;
            $renta->save();
        }
        return ($renta->codigo_re);
    }

    /*
     * verificar si existe el codigo de impuesto
     * */
    private function  codigo_impuesto($codigo){
        $valor =   Impuesto::find((int)$codigo);
        if(!$valor){
            $valor  =   new Impuesto();
            $valor->codigo_im   =   (int)$codigo;
            $valor->save();
        }
        return $valor->codigo_im;
    }
    /*
     * ingresa el impuesto de los productos nuevos
     * resibe @producto clave del producto
     *        @impuestos valores de la factura electronica
     *
     * */
    private function impuesto_producto($producto,$impuestos){
        if(isset($impuestos[0])){
            $i=0;
            foreach ($impuestos as $aux){
                $impuesto   =   new ImpuestoProducto();
                $impuesto->codigo_im    =   $this->codigo_impuesto($impuestos[$i]['codigo']);
                $impuesto->version_pp   =   $producto->version_pp;
                $impuesto->detalle_pp   =   $producto->detalle_pp;
                $impuesto->codigo_po    =   $this->codigo_porcentaje($impuestos[$i]['codigoPorcentaje']);
                $impuesto->tarifa_ip    =   @$impuestos[$i]['descuentoAdicional'];
                $impuesto->basei_ip     =   $impuestos[$i]['baseImponible'];
                $impuesto->valor_ip     =   $impuestos[$i]['valor'];
                $impuesto->save();
                $i++;
            }

        }else{
            $impuesto   =   new ImpuestoProducto();
            $impuesto->codigo_im    =   $this->codigo_impuesto($impuestos['codigo']);
            $impuesto->version_pp   =   $producto->version_pp;
            $impuesto->detalle_pp   =   $producto->detalle_pp;
            $impuesto->codigo_po    =   $this->codigo_porcentaje($impuestos['codigoPorcentaje']);
            $impuesto->tarifa_ip    =   @$impuestos['descuentoAdicional'];
            $impuesto->basei_ip     =   $impuestos['baseImponible'];
            $impuesto->valor_ip     =   $impuestos['valor'];
            $impuesto->save();
        }
    }

    /*
     * ingresa el impuesto de las notas de credito nuevas
     *
     *
     * */
    private function impuesto_credito($producto,$impuestos){
        if(isset($impuestos[0])){
            $i=0;
            foreach ($impuestos as $aux){
                $impuesto   =   new ImpuestoCredito();
                $impuesto->codigo_im    =   $this->codigo_impuesto($impuestos[$i]['codigo']);
                $impuesto->version_dp   =   $producto->version_dp;
                $impuesto->detalle_dp   =   $producto->detalle_dp;
                $impuesto->codigo_po    =   $this->codigo_porcentaje($impuestos[$i]['codigoPorcentaje']);
                $impuesto->tarifa_id    =   @$impuestos[$i]['descuentoAdicional'];
                $impuesto->basei_id     =   $impuestos[$i]['baseImponible'];
                $impuesto->valor_id     =   $impuestos[$i]['valor'];
                $impuesto->save();
                $i++;
            }

        }else{
            $impuesto   =   new ImpuestoCredito();
            $impuesto->codigo_im    =   $this->codigo_impuesto($impuestos['codigo']);
            $impuesto->version_dp   =   $producto->version_dp;
            $impuesto->detalle_dp   =   $producto->detalle_dp;
            $impuesto->codigo_po    =   $this->codigo_porcentaje($impuestos['codigoPorcentaje']);
            $impuesto->tarifa_id    =   @$impuestos['descuentoAdicional'];
            $impuesto->basei_id     =   $impuestos['baseImponible'];
            $impuesto->valor_id     =   $impuestos['valor'];
            $impuesto->save();
        }
    }

    /*
     * ingresa los detalles de la factura
     *
     * */
    private function detalle_factura($factura,$producto,$cantidad){
        $detalle    =   DetalleFactura::where('secuencial_eg',$factura->secuencial_eg)->
                                        where('pemision_ac',$factura->pemision_ac)->
                                        where('codigo_su',$factura->codigo_su)->
                                        where('ruc_pr',$factura->ruc_pr)->
                                        where('version_pp',$producto->version_pp)->
                                        where('detalle_pp',$producto->detalle_pp)->
                                        where('codigo_tc',1)->first();

        if(!$detalle){
            $detalle    =   new DetalleFactura();
            $detalle->secuencial_eg =   $factura->secuencial_eg;
            $detalle->pemision_ac   =   $factura->pemision_ac;
            $detalle->codigo_su     =   $factura->codigo_su;
            $detalle->ruc_pr        =   $factura->ruc_pr;
            $detalle->version_pp    =   $producto->version_pp;
            $detalle->detalle_pp    =   $producto->detalle_pp;
            $detalle->cantidad_df   =   $cantidad;
            $detalle->save();
        }else{
            //DB::raw('count(*) as user_count, status');
            $cantidad+=(double)$detalle->cantidad_df;
            DetalleFactura::where(
                [
                    ['secuencial_eg','=',$detalle->secuencial_eg],
                    ['pemision_ac','=',$detalle->pemision_ac],
                    ['codigo_su','=',$detalle->codigo_su],
                    ['ruc_pr','=',$detalle->ruc_pr],
                    ['version_pp','=',$detalle->version_pp],
                    ['detalle_pp','=',$detalle->detalle_pp],
                ])->delete();
            $detalle    =   new DetalleFactura();
            $detalle->secuencial_eg =   $factura->secuencial_eg;
            $detalle->pemision_ac   =   $factura->pemision_ac;
            $detalle->codigo_su     =   $factura->codigo_su;
            $detalle->ruc_pr        =   $factura->ruc_pr;
            $detalle->version_pp    =   $producto->version_pp;
            $detalle->detalle_pp    =   $producto->detalle_pp;
            $detalle->cantidad_df   =   $cantidad;
            $detalle->save();
        }
    }

    /*
     * detalle nota de credito
     *
     *
     * */
    private function detalle_ncredito($ncredito,$producto,$cantidad){
        $detalle    =   DetalleNcredito::where('secuencial_eg',$ncredito->secuencial_eg)->
                                            where('pemision_ac',$ncredito->pemision_ac)->
                                            where('codigo_su',$ncredito->codigo_su)->
                                            where('ruc_pr',$ncredito->ruc_pr)->
                                            where('version_dp',$producto->version_dp)->
                                            where('detalle_dp',$producto->detalle_dp)->
                                            where('codigo_tc',1)->first();

        if(!$detalle){
            $detalle    =   new DetalleNcredito();
            $detalle->secuencial_eg =   $ncredito->secuencial_eg;
            $detalle->pemision_ac   =   $ncredito->pemision_ac;
            $detalle->codigo_su     =   $ncredito->codigo_su;
            $detalle->ruc_pr        =   $ncredito->ruc_pr;
            $detalle->version_dp    =   $producto->version_dp;
            $detalle->detalle_dp    =   $producto->detalle_dp;
            $detalle->cantidad_dnc  =   $cantidad;
            $detalle->save();
        }else{
            //DB::raw('count(*) as user_count, status');
            $cantidad+=(double)$detalle->cantidad_dnc;
            DetalleNcredito::where(
                [
                    ['secuencial_eg','=',$detalle->secuencial_eg],
                    ['pemision_ac','=',$detalle->pemision_ac],
                    ['codigo_su','=',$detalle->codigo_su],
                    ['ruc_pr','=',$detalle->ruc_pr],
                    ['version_dp','=',$detalle->version_dp],
                    ['detalle_dp','=',$detalle->detalle_dp],
                ])->delete();
            $detalle    =   new DetalleNcredito();
            $detalle->secuencial_eg =   $ncredito->secuencial_eg;
            $detalle->pemision_ac   =   $ncredito->pemision_ac;
            $detalle->codigo_su     =   $ncredito->codigo_su;
            $detalle->ruc_pr        =   $ncredito->ruc_pr;
            $detalle->version_dp    =   $producto->version_dp;
            $detalle->detalle_dp    =   $producto->detalle_dp;
            $detalle->cantidad_dnc  =   $cantidad;
            $detalle->save();
        }
    }

    /*
     * detalle nota de debito
     *
     * */
    private function detalle_ndebito($ndebito,$razon,$valor){
        $detalle    =   DetalleNdebito::where('secuencial_eg',$ndebito->secuencial_eg)->
                                            where('pemision_ac',$ndebito->pemision_ac)->
                                            where('codigo_su',$ndebito->codigo_su)->
                                            where('ruc_pr',$ndebito->ruc_pr)->
                                            where('version_mc',$razon->version_mc)->
                                            where('descripcion_mc',$razon->descripcion_mc)->first();
        if(!$detalle){
            $detalle    =   new DetalleNdebito();
            $detalle->secuencial_eg     =   $ndebito->secuencial_eg;
            $detalle->pemision_ac       =   $ndebito->pemision_ac;
            $detalle->codigo_su         =   $ndebito->codigo_su;
            $detalle->ruc_pr            =   $ndebito->ruc_pr;
            $detalle->version_mc        =   $razon->version_mc;
            $detalle->descripcion_mc    =   $razon->descripcion_mc;
            $detalle->valor_mnd         =   $valor;
            $detalle->save();
        }else{
            $valor+=(double)$detalle->valor_mnd;
            DetalleNdebito::where(
                [
                    ['secuencial_eg','=',$detalle->secuencial_eg],
                    ['pemision_ac','=',$detalle->pemision_ac],
                    ['codigo_su','=',$detalle->codigo_su],
                    ['ruc_pr','=',$detalle->ruc_pr],
                    ['version_mc','=',$detalle->version_mc],
                    ['descripcion_mc','=',$detalle->descripcion_mc],
                ])->delete();
            $detalle    =   new DetalleNdebito();
            $detalle->secuencial_eg     =   $ndebito->secuencial_eg;
            $detalle->pemision_ac       =   $ndebito->pemision_ac;
            $detalle->codigo_su         =   $ndebito->codigo_su;
            $detalle->ruc_pr            =   $ndebito->ruc_pr;
            $detalle->version_mc        =   $razon->version_mc;
            $detalle->descripcion_mc    =   $razon->descripcion_mc;
            $detalle->valor_mnd         =   $valor;
            $detalle->save();
        }

    }
}
