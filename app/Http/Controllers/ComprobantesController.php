<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Comprobante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPExcel_Cell_DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Jenssegers\Date\Date;
use SoapClient;
use stdClass;

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
    public function store(Request $request){
        $validacion =   Validator::make($request->all(), [
            'archivo'       => 'required|file',
            'cliente'       => 'required|exists:clientes,id_cl',
        ]);
        if($validacion->fails()){
            $texto  =   '';
            foreach ($validacion->errors()->all() as $errores)
                $texto.=$errores.PHP_EOL;
            return response(['val'=>false,'message'=>$texto,'datos'=>$validacion->errors()->all()],500);
        }else{
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
                                $comprobante                =   new Comprobante();
                                $comprobante->id_co         =   $item;
                                $comprobante->id_tc         =   (int) $sri->infoTributaria->codDoc;
                                $comprobante->id_cl         =   $elegido->id_cl;
                                $comprobante->fecha_co      =   Date::createFromFormat('d/m/Y',$sri->info->fechaEmision)->format("Y-m-d");
                                $comprobante->estado_co     =   true;
                                $comprobante->comprobante   =   $sri;
                                $comprobante->save();
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
    }

    /**
     * @param Request $request
     * @param $id
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function show(Request $request,$id)
    {
        $cliente    =   Cliente::find($id);
        $nomArchivo =   "$cliente->apellidos_cl $cliente->nombres_cl";
        $letra      =   "I";
        $inicio     =   5;
        $impuestos  =   collect([]);

        $archivo    =   new Spreadsheet();
        $archivo->getProperties()->setCreator('ASECONT - PUYO');
        $archivo->getActiveSheet()->setCellValue('A1',$cliente->apellidos_cl." ".$cliente->nombres_cl);
        $archivo->getActiveSheet()->setCellValue('A2',$cliente->razon_cl);
        $archivo->getActiveSheet()->setCellValueExplicit('A3',$cliente->ruc_cl,PHPExcel_Cell_DataType::TYPE_STRING);
        $archivo->getActiveSheet()->mergeCells('A1:M1');
        $archivo->getActiveSheet()->mergeCells('A2:M2');
        $archivo->getActiveSheet()->mergeCells('A3:M3');
        $archivo->getActiveSheet()->getRowDimension('1')->setRowHeight(22);
        $archivo->getActiveSheet()->getRowDimension('2')->setRowHeight(20);
        $archivo->getActiveSheet()->getRowDimension('3')->setRowHeight(18);
        $archivo->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
        $archivo->getActiveSheet()->getStyle("A2")->getFont()->setBold(true);
        $archivo->getActiveSheet()->getStyle("A3")->getFont()->setBold(true);
        //$archivo->getActiveSheet()->getStyle('A2:I2')->getFill()->setFillType();
        $archivo->getActiveSheet()->getStyle('A1:A3')->getFill()->getStartColor()->setARGB('29bb04');
        $titulos_sty = array(
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '292542'),
                'size'  => 13,
                'name'  => 'Verdana'
            ));
        $archivo->getActiveSheet()->getStyle('A1')->applyFromArray($titulos_sty);
        $archivo->getActiveSheet()->getStyle('A2')->applyFromArray($titulos_sty);
        $archivo->getActiveSheet()->getStyle('A3')->applyFromArray($titulos_sty);
        $archivo->getActiveSheet()->getStyle('A1:B3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $fila   =   $inicio;
        $cont   =   0;
        //auxiliares
        $aux_ano    =   "";
        $aux_mes    =   "";
        $archivo->getActiveSheet()->setCellValue("A$fila","Num");
        $archivo->getActiveSheet()->setCellValue("B$fila","Fecha");
        $archivo->getActiveSheet()->setCellValue("C$fila","Secuencial");
        $archivo->getActiveSheet()->setCellValue("D$fila","RUC");
        $archivo->getActiveSheet()->setCellValue("E$fila","Nombre Comercial");
        $archivo->getActiveSheet()->setCellValue("F$fila","Detalle");
        $archivo->getActiveSheet()->setCellValue("G$fila","Base Imponible");
        $archivo->getActiveSheet()->setCellValue("H$fila","Descuento");

        $fila++;
        Date::setLocale('es');
        $data   =   $this->consultaFecha($request->desde,$request->hasta,$id);
        foreach ($data as $nivel){
            //fecha
            $fecha  =   Date::createFromFormat('Y-m-d',$nivel->fecha_co);
            $ano    =   $fecha->format('Y');
            $dia    =   $fecha->format('j');
            $mes    =   $fecha->format('F');
            $minMes =   mb_strtoupper(substr($mes, 0,3));
            if($ano!==$aux_ano){
                $archivo->getActiveSheet()->insertNewRowBefore($fila,1);
                $archivo->getActiveSheet()->setCellValue("A$fila",$ano);
                $fila++;
                $aux_ano=$ano;
                $aux_mes=$mes;
            }
            if($mes!=$aux_mes){
                $archivo->getActiveSheet()->insertNewRowBefore($fila,1);
                $fila++;
                $aux_mes=$mes;
                $archivo->getActiveSheet()->insertNewRowBefore($fila,1);
                $fila++;

            }

            if(gettype($nivel->comprobante->detalles->detalle)==="array")
                $producto="VARIOS PRODUCTOS";
            else
                $producto=mb_strtoupper($nivel->comprobante->detalles->detalle->descripcion);

            $empresa=mb_strtoupper(@$nivel->comprobante->infoTributaria->nombreComercial);
            if(!$empresa)
                $empresa=mb_strtoupper($nivel->comprobante->infoTributaria->razonSocial);
            $cont++;
            $archivo->getActiveSheet()->insertNewRowBefore($fila,1);
            $archivo->getActiveSheet()->setCellValue("A$fila",$cont)
                ->setCellValue("B$fila","$minMes-$dia")
                ->setCellValueExplicit("C$fila",$nivel->id_co,PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit("D$fila",$nivel->comprobante->infoTributaria->ruc,PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue("E$fila",$empresa)
                ->setCellValue("F$fila",$producto)
                ->setCellValue("G$fila",$nivel->comprobante->info->totalSinImpuestos)
                ->setCellValue("H$fila",$nivel->comprobante->info->totalDescuento);
            if(gettype($nivel->comprobante->info->totalConImpuestos->totalImpuesto)==="array"){
                foreach ($nivel->comprobante->info->totalConImpuestos->totalImpuesto as $impuesto){
                    $aux    =   $this->listaImpuestos($impuestos,$impuesto,$letra);
                    $archivo->getActiveSheet()->setCellValue($aux->letra.$fila,$impuesto->valor);
                }
            }else{
                $aux    =   $this->listaImpuestos($impuestos,$nivel->comprobante->info->totalConImpuestos->totalImpuesto,$letra);
                $archivo->getActiveSheet()->setCellValue($aux->letra.$fila,$nivel->comprobante->info->totalConImpuestos->totalImpuesto->valor);
            }
            $fila++;
        }
        $letra++;


        $fila   =   $inicio+1;
        foreach ($data as $nivel){
            $fila++;
            $archivo->getActiveSheet()->setCellValue($letra.$fila,$nivel->comprobante->info->importeTotal);
        }

        for ($i="I";$i<=$letra;$i++ ){
            $archivo->getActiveSheet()->setCellValue($letra.$inicio,$i);
        }


        for ($i="A";$i<=$letra;$i++ ){
            $archivo->getActiveSheet()->getColumnDimension($i)->setAutoSize(true);
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=$nomArchivo.xlsx");
        header('Cache-Control: max-age=0');
        header("nombre: $nomArchivo.xlsx");
        $salida = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($archivo, 'Xlsx');
        $salida->save('php://output');
        exit;
    }

    private function listaImpuestos($array,$impuesto,$letra){
        $resultado  =   $array->where('codigo',$impuesto->codigo)->where('codigoPorcentaje',$impuesto->codigoPorcentaje)->first();
        if($resultado){
            return (object)$resultado;
        }else{
            $letra++;
            $obj    =   ['letra'=>$letra,'codigo'=>$impuesto->codigo,'codigoPorcentaje'=>$impuesto->codigoPorcentaje];
            //todo agregar objeto de tablas
            $obj    =   (object)$obj;
            $array->push($obj);
            return $obj;
        }
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
            $lista = $this->consultaFecha($request->desde,$request->hasta,$id);
            return response($lista);
        }
    }

    private function consultaFecha($desde,$hasta,$id){
        if($desde || $hasta){
            return Comprobante::where("id_cl",$id)
                ->orderBy("fecha_co","asc")
                ->orderBy("id_tc","desc")
                ->whereBetween('fecha_co', [$desde, $hasta])
                ->get();
        }else{
            return Comprobante::where("id_cl",$id)
                ->orderBy("fecha_co","asc")
                ->orderBy("id_tc","desc")
                ->get();
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
            $aux            =   new stdClass();
            $aux->val       =   false;
            $aux->message   =   "No existe el comprobante en la base de datos del SRI";
            return $aux;
        }

    }
}
