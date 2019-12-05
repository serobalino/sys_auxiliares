<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Comprobante;


use App\Http\Requests\Comprobantes\Subir\ReporteRequest;
use App\Http\Requests\Comprobantes\Subir\XmlRequest;
use Jenssegers\Date\Date;
use SimpleXMLElement;
use SoapClient;
use stdClass;

class SubirController extends Controller
{
    protected $comprobantes;

    public function __construct(ComprobantesController $comprobantes)
    {
        $this->comprobantes = $comprobantes;
    }

    /**
     * @param ReporteRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function reporte(ReporteRequest $request){
        set_time_limit ( 5*60);
        $guardados=0;
        $existentes=0;
        $nopertenece=0;
        $son=0;

        $file       =   $request->file('archivo');
        $archivo    =   file_get_contents($file);
        $archivo    =   str_replace(PHP_EOL, ' ', $archivo);
        $elementos  =   explode("	",$archivo);

        $elegido    =   Cliente::find($request->cliente);
        foreach ($elementos as $item){
            if(strlen($item)===49 && (int)$item>0){
                $son++;
                $comprobante    = Comprobante::find($item);
                if($comprobante){
                    $existentes++;
                }else{
                    $sri                        =   $this->consultar($item);
                    if($sri->val){
                        if($elegido->dni_cl===$sri->id || $elegido->ruc_cl===$sri->id){
                            unset($sri->val);
                            unset($sri->id);
                            $this->comprobantes->guardar($item,$elegido,$sri);
                            $guardados++;
                        }else{
                            $errores[]  = ["consulta"=>$sri,"comprobante"=>$item,"mesanjes"=>"No existe cliente con $sri->id"];
                            $nopertenece++;
                        }
                    }else{
                        $errores[]  = ["respuesta"=>$sri,"comprobante"=>$item];
                    }
                }
            }
        }
        return response([
            "guardados"     =>  $guardados,
            "existentes"    =>  $existentes,
            "nopertenece"   =>  $nopertenece,
            "son"           =>  $son,
            "errores"       =>  @$errores ? $errores : 0,
        ]);
    }

    /**
     * @param $claveAceso
     * @return mixed|object|stdClass
     */
    private function consultar($claveAceso){
        $opts=array(
            'http'=>array(
                'user_agent'=>'PHPSoapClient'
            )
        );
        $context=stream_context_create($opts);
        $soapClientOptions=array(
            'stream_context'=>$context,
            'cache_wsdl'=>WSDL_CACHE_NONE
        );
        $url="https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantes?wsdl";
        try {
            $client = new SoapClient($url, $soapClientOptions);
            return $this->parseXml($client->autorizacionComprobante(['claveAccesoComprobante'=>$claveAceso]));
        } catch (\Exception $e) {
            $aux            =   new stdClass();
            $aux->val       =   false;
            $aux->message   =   "No existe el comprobante en la base de datos del SRI";
            return $aux;
        }
    }

    /**
     * @param $xml
     * @return \SimpleXMLElement
     */
    private function enlinea($xml){
        return simplexml_load_string($xml->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->comprobante);
    }

    /**
     * @param $xml
     * @return \SimpleXMLElement
     */
    private function offlinea($xml){
        return simplexml_load_string($xml->comprobante);
    }

    /**
     * @param $aux
     * @param null $archivo
     * @return mixed|object
     */
    private function parseXml($aux,$archivo=null){
        try {
            try{
                $aux=$this->enlinea($aux);
            }catch (\Exception $e) {
                $aux=$this->offlinea($aux);
            }
            $json=json_decode(json_encode($aux));
            switch ((int)$json->infoTributaria->codDoc) {
                case 1://factura
                    $json->val=true;
                    $json->info=$json->infoFactura;
                    $json->id=$json->info->identificacionComprador;
                    unset($json->infoFactura);
                    break;
                case 2://nota de venta
                    $json->val=false;
                    $json->info=$json->infoNotaVenta;
                    unset($json->infoNotaVenta);
                    break;
                case 3://liquidacion de compra
                    $json->val=false;
                    $json->info=$json->infoFactura;
                    unset($json->infoFactura);
                    break;
                case 4://nota de credito
                    $json->val=true;
                    $json->id=$json->infoNotaCredito->identificacionComprador;
                    $json->info=$json->infoNotaCredito;
                    unset($json->infoNotaCredito);
                    break;
                case 5://nota de debito
                    $json->val=true;
                    $json->id=$json->infoNotaDebito->identificacionComprador;
                    $json->info=$json->infoNotaDebito;
                    unset($json->infoNotaDebito);
                    break;
                case 6://guia de remision
                    $json->val=false;
                    $json->info=$json->infoFactura;
                    unset($json->infoFactura);
                    break;
                case 7://comprobante de retencion
                    $json->val=true;
                    $json->info=$json->infoCompRetencion;
                    $json->id=$json->infoCompRetencion->identificacionSujetoRetenido;
                    unset($json->infoCompRetencion);
                    break;
                case 8://entradas a espectaculos
                    $json->val=false;
                    $json->info=$json->infoFactura;
                    unset($json->infoFactura);
                    break;
                default:
                    $json->val=false;
                    break;
            }
            return $json;
        } catch (\Exception $e) {
            $nombre    =   @$archivo->getClientOriginalName();
            return (object)['val'=>false,'message'=>"Error en la estructura del Comprobante Electrónico $nombre"];
        }
    }

    /**
     * @param XmlRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function comprobante(XmlRequest $request){
        $guardados=0;
        $existentes=0;
        $nopertenece=0;
        $son=0;

        $elegido    =   Cliente::find($request->cliente);

        $files      =   $request->file('archivos');
        foreach ($files as $item){
            $xml    =   $this->parseXml(new SimpleXMLElement(file_get_contents($item)),$item);
            $son++;
            if($xml->val){
                if(strlen($xml->infoTributaria->claveAcceso)===49 && (int)$xml->infoTributaria->claveAcceso>0){
                    $comprobante    = Comprobante::find($xml->infoTributaria->claveAcceso);
                    if($comprobante){
                        $existentes++;
                    }else{
                        if($elegido->dni_cl===$xml->id || $elegido->ruc_cl===$xml->id){
                            unset($xml->val);
                            unset($xml->id);
                            $this->comprobantes->guardar($xml->infoTributaria->claveAcceso,$elegido,$xml);
                            $guardados++;
                        }else{
                            $errores[]  = ["comprobante"=>$xml->infoTributaria->claveAcceso,"mesanjes"=>"No le pertenece el comprobante emitido para $xml->id"];
                            $nopertenece++;
                        }

                    }
                }else{
                    $errores[]  = ["respuesta"=>$xml,"comprobante"=>$xml->infoTributaria->claveAcceso];
                }
            }else{
                $errores[]  = ["respuesta"=>$xml->message];
            }
        }
        return response([
            "guardados"     =>  $guardados,
            "existentes"    =>  $existentes,
            "nopertenece"   =>  $nopertenece,
            "son"           =>  $son,
            "errores"       =>  @$errores ? $errores : 0,
        ]);
    }
}
