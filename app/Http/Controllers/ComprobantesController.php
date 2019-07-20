<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Comprobante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Date\Date;
use SoapClient;

class ComprobantesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $guardados=0;
        $existentes=0;
        $nopertenece=0;
        $son=0;

        $file       =   $request->file('archivo');
        $archivo    =   file_get_contents($file);
        $archivo    =   str_replace(PHP_EOL, ' ', $archivo);
        $elementos  =   explode("	",$archivo);
        $lista      =   [];
        foreach ($elementos as $item){
            if(strlen($item)===49 && (int)$item>0){
                $son++;
                $comprobante    = Comprobante::find($item);
                if($comprobante){
                    $existentes++;
                }else{
                    $sri                        =   $this->consultar($item);
                    $cliente                    =   Cliente::where("dni_cl",$sri->info->identificacionComprador)->orWhere("ruc_cl",$sri->info->identificacionComprador)->first();
                    if($cliente && @$sri->val){
                        unset($sri->val);
                        $comprobante                =   new Comprobante();
                        $comprobante->id_co         =   $item;
                        $comprobante->id_tc         =   (int) $sri->infoTributaria->codDoc;
                        $comprobante->id_cl         =   $cliente->id_cl;
                        $comprobante->fecha_co      =   Date::createFromFormat('d/m/Y',$sri->info->fechaEmision)->format("Y-m-d");
                        $comprobante->estado_co     =   true;
                        $comprobante->comprobante   =   $sri;
                        $comprobante->save();
                        $guardados++;
                    }else{
                        $errores[]  = ["consulta"=>$sri,"comprobante"=>$item];
                        $nopertenece++;
                    }
                }
            }
        }
        return response([
            "guardados"     =>  $guardados,
            "existentes"    =>  $existentes,
            "nopertenece"   =>  $nopertenece,
            "son"           =>  $son,
            "errores"       =>  @$errores
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validacion =   Validator::make($request->all(), [
            'desde' => 'nullable|date',
            'hasta' => 'nullable|date|after:desde',
        ]);
        if($validacion->fails()){
            $texto  =   '';
            foreach ($validacion->errors()->all() as $errores)
                $texto.=$errores.PHP_EOL;
            return response(['val'=>false,'message'=>$texto,'datos'=>$validacion->errors()->all()],500);
        }else{
            if($request->desde && $request->hasta)
                $lista  =   Comprobante::where("id_cl",$id)
                    ->orderBy("fecha_co","asc")
                    ->orderBy("id_tc","desc")
                    ->whereBetween('fecha_co', [$request->desde, $request->hasta])
                    ->get();
            else
                $lista  =   Comprobante::where("id_cl",$id)
                    ->orderBy("fecha_co","asc")
                    ->orderBy("id_tc","desc")
                    ->get();

            return response($lista);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /**
     *
    ***/
    public function consultar($claveAceso){
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
            $aux=$client->autorizacionComprobante(['claveAccesoComprobante'=>$claveAceso]);
            $aux=simplexml_load_string($aux->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->comprobante);
            $json=json_decode(json_encode($aux));
            $json->val=true;

            switch ((int)$json->infoTributaria->codDoc) {
                case 1://factura
                    $json->info=$json->infoFactura;
                    unset($json->infoFactura);
                    break;
                case 2://nota de venta
                    $json->info=$json->infoNotaVenta;
                    unset($json->infoNotaVenta);
                    break;
                case 3://liquidacion de compra
                    $json->info=$json->infoFactura;
                    unset($json->infoFactura);
                    break;
                case 4://nota de credito
                    $json->info=$json->infoNotaCredito;
                    unset($json->infoNotaCredito);
                    break;
                case 5://nota de debito
                    $json->info=$json->infoNotaDebito;
                    unset($json->infoNotaDebito);
                    break;
                case 6://guia de remision
                    $json->info=$json->infoFactura;
                    unset($json->infoFactura);
                    break;
                case 7://comprobante de retencion
                    $json->info=$json->infoCompRetencion;
                    unset($json->infoCompRetencion);
                    break;
                case 8://entradas a espectaculos
                    $json->info=$json->infoFactura;
                    unset($json->infoFactura);
                    break;
            }


            return $json;
        } catch (\Exception $e) {
            $aux["val"]=false;
            $aux["message"]=$e->getMessage();
            return $aux;
        }

    }
}
