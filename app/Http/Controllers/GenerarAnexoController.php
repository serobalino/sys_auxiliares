<?php

namespace App\Http\Controllers;

use App\Catalogos\Tabla17;
use App\Catalogos\Tabla18;
use App\Catalogos\Tabla19;
use App\Catalogos\Tabla20;
use App\Cliente;
use App\Http\Requests\Comprobantes\Lista\RangoRequest;
use Illuminate\Http\Request;
use Jenssegers\Date\Date;
use PHPExcel_Cell_DataType;
use PHPExcel_Style_Alignment;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class GenerarAnexoController extends Controller
{
    protected $comprobantes;

    protected $registro;

    public function __construct(ComprobantesController $comprobantes,HistorialController $registro)
    {
        $this->comprobantes =   $comprobantes;
        $this->registro     =   $registro;
    }


    /**
     * @var array
     */
    private $impuestos  =   [];

    /**
     * @var string
     */
    private $letra      =   "I";

    /**
     * @param $impuesto
     * @param $bandera
     * @param int $comprobante
     * @return object
     */
    private function listaImpuestos($impuesto,bool $bandera,int $comprobante){
        $array      =   collect($this->impuestos);
        switch ($comprobante) {
            case 1://factura
                $resultado  =   $array->where('codigo1',$impuesto->codigo)
                    ->where('codigo2',$impuesto->codigoPorcentaje)
                    ->where('base',$bandera)
                    ->where('id_tc',$comprobante)
                    ->first();
                break;
            case 2://nota de venta
                break;
            case 3://liquidacion de compra
                break;
            case 4://nota de credito
                $resultado  =   $array->where('codigo1',$impuesto->codigo)
                    ->where('codigo2',$impuesto->codigoPorcentaje)
                    ->where('base',$bandera)
                    ->where('id_tc',1)
                    ->first();
                break;
            case 5://nota de debito
                $resultado  =   $array->where('codigo1',$impuesto->codigo)
                    ->where('codigo2',$impuesto->codigoPorcentaje)
                    ->where('base',$bandera)
                    ->where('id_tc',1)
                    ->first();
                break;
            case 6://guia de remision
                break;
            case 7://comprobante de retencion
                $resultado  =   $array->where('codigo1',$impuesto->codigo)
                    ->where('codigo2',$impuesto->codigoRetencion)
                    ->where('base',$bandera)
                    ->where('id_tc',$comprobante)
                    ->first();
                break;
            case 8://entradas a espectaculos
                break;
            default:
                break;
        }


        if($resultado){
            return (object)$resultado;
        }else{
            switch ($comprobante) {
                case 2://nota de venta
                    break;
                case 3://liquidacion de compra
                    break;
                case 6://guia de remision
                    break;
                case 7://comprobante de retencion
                    $aux    =   Tabla20::find($impuesto->codigoRetencion);
                    if(!$aux){
                        $aux                    =   new Tabla20();
                        $aux->cod_t20           =   $impuesto->codigoRetencion;
                        $aux->porcentaje_t20    =   (int)$impuesto->porcentajeRetener;
                        $aux->save();
                    }

                    $this->letra++;
                    $obj2    =   [
                        'letra'=>$this->letra,
                        'codigo1'=>$impuesto->codigo,
                        'codigo2'=>$impuesto->codigoRetencion,
                        'imp1'=>Tabla17::find($impuesto->codigo),
                        'imp2'=>$aux,
                        'base'=>true,
                        'id_tc'=>$comprobante
                    ];
                    $obj2    =   (object)$obj2;
                    $array->push($obj2);

                    $this->letra++;
                    $obj1    =   [
                        'letra'=>$this->letra,
                        'codigo1'=>$impuesto->codigo,
                        'codigo2'=>$impuesto->codigoRetencion,
                        'imp1'=>$obj2->imp1,
                        'imp2'=>$obj2->imp2,
                        'base'=>!$obj2->base,
                        'id_tc'=>$comprobante
                    ];
                    $obj1    =   (object)$obj1;
                    $array->push($obj1);
                    break;
                case 8://entradas a espectaculos
                    break;
                default:
                    $this->letra++;
                    $obj2    =   [
                        'letra'=>$this->letra,
                        'codigo1'=>$impuesto->codigo,
                        'codigo2'=>$impuesto->codigoPorcentaje,
                        'imp1'=>Tabla17::find($impuesto->codigo),
                        'imp2'=>Tabla18::find($impuesto->codigoPorcentaje) ? Tabla18::find($impuesto->codigoPorcentaje) : Tabla19::find($impuesto->codigoPorcentaje),
                        'base'=>true,
                        'id_tc'=>1
                    ];
                    $obj2    =   (object)$obj2;
                    $array->push($obj2);

                    $this->letra++;
                    $obj1    =   [
                        'letra'=>$this->letra,
                        'codigo1'=>$impuesto->codigo,
                        'codigo2'=>$impuesto->codigoPorcentaje,
                        'imp1'=>$obj2->imp1,
                        'imp2'=>$obj2->imp2,
                        'base'=>!$obj2->base,
                        'id_tc'=>1
                    ];
                    $obj1    =   (object)$obj1;
                    $array->push($obj1);
                    break;
            }

            $this->impuestos=$array;
            return $bandera ? $obj2 : $obj1;
        }
    }

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
        //
    }

    /**
     * @param Request $request
     * @param $id
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function show(RangoRequest $request,$id){
        $cliente    =   Cliente::find($id);
        $nomArchivo =   "$cliente->apellidos_cl $cliente->nombres_cl";
        $nomArchivo =   str_replace(' ', '-', $nomArchivo);
        $nomArchivo =   preg_replace('/[^A-Za-z0-9\-]/', '', $nomArchivo);
        $inicio     =   5;

        $archivo    =   new Spreadsheet();
        $archivo->getProperties()->setCreator('ASECONT - PUYO');
        $archivo->getActiveSheet()->setCellValue('A1',$cliente->apellidos_cl." ".$cliente->nombres_cl);
        $archivo->getActiveSheet()->setCellValue('A2',$cliente->razon_cl);
        $archivo->getActiveSheet()->setCellValueExplicit('A3',$cliente->ruc_cl,PHPExcel_Cell_DataType::TYPE_STRING);
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
        $archivo->getActiveSheet()->setCellValue("C$fila","Clave de Acceso");
        $archivo->getActiveSheet()->setCellValue("D$fila","Estb.");
        $archivo->getActiveSheet()->setCellValue("E$fila","P.Emi.");
        $archivo->getActiveSheet()->setCellValue("F$fila","Secuencial");
        $archivo->getActiveSheet()->setCellValue("G$fila","RUC");
        $archivo->getActiveSheet()->setCellValue("H$fila","Nombre Comercial");
        $archivo->getActiveSheet()->setCellValue("I$fila","Detalle");

        $fila++;
        Date::setLocale('es');
        $data   =   $this->comprobantes->consulta($id,$request->desde,$request->hasta);
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
            }
            try {
                $empresa=mb_strtoupper($nivel->comprobante->infoTributaria->nombreComercial);
            } catch (\Exception $e) {
                $empresa=mb_strtoupper($nivel->comprobante->infoTributaria->razonSocial);
            }
            $cont++;
            $archivo->getActiveSheet()->insertNewRowBefore($fila,1);
            switch ($nivel->id_tc){
                case 1://Factura
                    if(gettype($nivel->comprobante->detalles->detalle)==="array")
                        $producto="VARIOS PRODUCTOS";
                    else
                        $producto=mb_strtoupper($nivel->comprobante->detalles->detalle->descripcion);

                    $archivo->getActiveSheet()->setCellValue("A$fila",$cont)
                        ->setCellValue("B$fila","$minMes-$dia")
                        ->setCellValueExplicit("C$fila",$nivel->id_co,PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValueExplicit("D$fila",$nivel->comprobante->infoTributaria->estab,PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValueExplicit("E$fila",$nivel->comprobante->infoTributaria->ptoEmi,PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValueExplicit("F$fila",$nivel->comprobante->infoTributaria->secuencial,PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValueExplicit("G$fila",$nivel->comprobante->infoTributaria->ruc,PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValue("H$fila",$empresa)
                        ->setCellValue("I$fila",$producto);

                    if(gettype($nivel->comprobante->info->totalConImpuestos->totalImpuesto)==="array"){
                        foreach ($nivel->comprobante->info->totalConImpuestos->totalImpuesto as $impuesto){
                            if($impuesto->baseImponible>0){
                                $aux    =   $this->listaImpuestos($impuesto,true,$nivel->id_tc);
                                $archivo->getActiveSheet()->setCellValue($aux->letra.$fila,(float)$impuesto->baseImponible);
                                $archivo->getActiveSheet()->getStyle($aux->letra.$fila)->getNumberFormat()->setFormatCode('0.00');
                            }
                            if($impuesto->valor>0){
                                $aux2    =   $this->listaImpuestos($impuesto,false,$nivel->id_tc);
                                $archivo->getActiveSheet()->setCellValue($aux2->letra.$fila,(float)$impuesto->valor);
                                $archivo->getActiveSheet()->getStyle($aux2->letra.$fila)->getNumberFormat()->setFormatCode('0.00');
                            }
                        }
                    }else{
                        if($nivel->comprobante->info->totalConImpuestos->totalImpuesto->baseImponible>0){
                            $aux    =   $this->listaImpuestos($nivel->comprobante->info->totalConImpuestos->totalImpuesto,true,$nivel->id_tc);
                            $archivo->getActiveSheet()->setCellValue($aux->letra.$fila,(float)$nivel->comprobante->info->totalConImpuestos->totalImpuesto->baseImponible);
                            $archivo->getActiveSheet()->getStyle($aux->letra.$fila)->getNumberFormat()->setFormatCode('0.00');
                        }
                        if($nivel->comprobante->info->totalConImpuestos->totalImpuesto->valor>0){
                            $aux2    =   $this->listaImpuestos($nivel->comprobante->info->totalConImpuestos->totalImpuesto,false,$nivel->id_tc);
                            $archivo->getActiveSheet()->setCellValue($aux2->letra.$fila,(float)$nivel->comprobante->info->totalConImpuestos->totalImpuesto->valor);
                            $archivo->getActiveSheet()->getStyle($aux2->letra.$fila)->getNumberFormat()->setFormatCode('0.00');
                        }
                    }
                    $archivo->getActiveSheet()->getStyle("A$fila:ZZ$fila")
                        ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

                    break;
                case 2://nota de venta
                    break;
                case 3://liquidacion de compra
                    break;
                case 4://nota de credito
                    $archivo->getActiveSheet()->setCellValue("A$fila",$cont)
                        ->setCellValue("B$fila","$minMes-$dia")
                        ->setCellValueExplicit("C$fila",$nivel->id_co,PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValueExplicit("D$fila",$nivel->comprobante->infoTributaria->estab,PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValueExplicit("E$fila",$nivel->comprobante->infoTributaria->ptoEmi,PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValueExplicit("F$fila",$nivel->comprobante->infoTributaria->secuencial,PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValueExplicit("G$fila",$nivel->comprobante->infoTributaria->ruc,PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValue("H$fila",$empresa)
                        ->setCellValue("I$fila", mb_strtoupper($nivel->tipo->detalle_tc." ".$nivel->comprobante->info->fechaEmisionDocSustento." #".$nivel->comprobante->info->numDocModificado, 'UTF-8'));

                    if(gettype($nivel->comprobante->info->totalConImpuestos->totalImpuesto)==="array"){
                        foreach ($nivel->comprobante->info->totalConImpuestos->totalImpuesto as $impuesto){
                            if($impuesto->baseImponible>0){
                                $aux    =   $this->listaImpuestos($impuesto,true,$nivel->id_tc);
                                $archivo->getActiveSheet()->setCellValue($aux->letra.$fila,$impuesto->baseImponible);
                                $archivo->getActiveSheet()->getStyle($aux->letra.$fila)->getNumberFormat()->setFormatCode('0.00');
                            }
                            if($impuesto->valor>0){
                                $aux2    =   $this->listaImpuestos($impuesto,false,$nivel->id_tc);
                                $archivo->getActiveSheet()->setCellValue($aux2->letra.$fila,$impuesto->valor);
                                $archivo->getActiveSheet()->getStyle($aux2->letra.$fila)->getNumberFormat()->setFormatCode('0.00');
                            }
                        }
                    }else{
                        if($nivel->comprobante->info->totalConImpuestos->totalImpuesto->baseImponible>0){
                            $aux    =   $this->listaImpuestos($nivel->comprobante->info->totalConImpuestos->totalImpuesto,true,$nivel->id_tc);
                            $archivo->getActiveSheet()->setCellValue($aux->letra.$fila,$nivel->comprobante->info->totalConImpuestos->totalImpuesto->baseImponible);
                            $archivo->getActiveSheet()->getStyle($aux->letra.$fila)->getNumberFormat()->setFormatCode('0.00');
                        }
                        if($nivel->comprobante->info->totalConImpuestos->totalImpuesto->valor>0){
                            $aux2    =   $this->listaImpuestos($nivel->comprobante->info->totalConImpuestos->totalImpuesto,false,$nivel->id_tc);
                            $archivo->getActiveSheet()->setCellValue($aux2->letra.$fila,$nivel->comprobante->info->totalConImpuestos->totalImpuesto->valor);
                            $archivo->getActiveSheet()->getStyle($aux2->letra.$fila)->getNumberFormat()->setFormatCode('0.00');
                        }
                    }
                    $archivo->getActiveSheet()->getStyle("A$fila:ZZ$fila")
                        ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKRED);

                    break;
                case 5://nota de debito
                    $archivo->getActiveSheet()->setCellValue("A$fila",$cont)
                        ->setCellValue("B$fila","$minMes-$dia")
                        ->setCellValueExplicit("C$fila",$nivel->id_co,PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValueExplicit("D$fila",$nivel->comprobante->infoTributaria->estab,PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValueExplicit("E$fila",$nivel->comprobante->infoTributaria->ptoEmi,PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValueExplicit("F$fila",$nivel->comprobante->infoTributaria->secuencial,PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValueExplicit("G$fila",$nivel->comprobante->infoTributaria->ruc,PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValue("H$fila",$empresa)
                        ->setCellValue("I$fila", mb_strtoupper($nivel->tipo->detalle_tc." ".$nivel->comprobante->info->fechaEmisionDocSustento." #".$nivel->comprobante->info->numDocModificado, 'UTF-8'));
                    if(gettype($nivel->comprobante->info->impuestos->impuesto)==="array"){
                        foreach ($nivel->comprobante->info->impuestos->impuesto as $impuesto){
                            if($impuesto->baseImponible>0){
                                $aux    =   $this->listaImpuestos($impuesto,true,$nivel->id_tc);
                                $archivo->getActiveSheet()->setCellValue($aux->letra.$fila,$impuesto->baseImponible);
                                $archivo->getActiveSheet()->getStyle($aux->letra.$fila)->getNumberFormat()->setFormatCode('0.00');
                            }
                            if($impuesto->valor>0){
                                $aux2    =   $this->listaImpuestos($impuesto,false,$nivel->id_tc);
                                $archivo->getActiveSheet()->setCellValue($aux2->letra.$fila,$impuesto->valor);
                                $archivo->getActiveSheet()->getStyle($aux2->letra.$fila)->getNumberFormat()->setFormatCode('0.00');
                            }
                        }
                    }else{
                        if($nivel->comprobante->info->impuestos->impuesto->baseImponible>0){
                            $aux    =   $this->listaImpuestos($nivel->comprobante->info->impuestos->impuesto,true,$nivel->id_tc);
                            $archivo->getActiveSheet()->setCellValue($aux->letra.$fila,$nivel->comprobante->info->impuestos->impuesto->baseImponible);
                            $archivo->getActiveSheet()->getStyle($aux->letra.$fila)->getNumberFormat()->setFormatCode('0.00');
                        }
                        if($nivel->comprobante->info->impuestos->impuesto->valor>0){
                            $aux2    =   $this->listaImpuestos($nivel->comprobante->info->impuestos->impuesto,false,$nivel->id_tc);
                            $archivo->getActiveSheet()->setCellValue($aux2->letra.$fila,$nivel->comprobante->info->impuestos->impuesto->valor);
                            $archivo->getActiveSheet()->getStyle($aux2->letra.$fila)->getNumberFormat()->setFormatCode('0.00');
                        }
                    }
                    $archivo->getActiveSheet()->getStyle("A$fila:ZZ$fila")
                        ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKBLUE);
                    break;
                case 6://guia de remision
                    break;
                case 7://comprobante de retencion
                    $archivo->getActiveSheet()->setCellValue("A$fila",$cont)
                        ->setCellValue("B$fila","$minMes-$dia")
                        ->setCellValueExplicit("C$fila",$nivel->id_co,PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValueExplicit("D$fila",$nivel->comprobante->infoTributaria->estab,PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValueExplicit("E$fila",$nivel->comprobante->infoTributaria->ptoEmi,PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValueExplicit("F$fila",$nivel->comprobante->infoTributaria->secuencial,PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValueExplicit("G$fila",$nivel->comprobante->infoTributaria->ruc,PHPExcel_Cell_DataType::TYPE_STRING)
                        ->setCellValue("H$fila",$empresa)
                        ->setCellValue("I$fila", mb_strtoupper($nivel->tipo->detalle_tc, 'UTF-8'));

                    if(gettype($nivel->comprobante->impuestos->impuesto)==="array"){
                        foreach ($nivel->comprobante->impuestos->impuesto as $impuesto){
                            if($impuesto->baseImponible>0){
                                $aux    =   $this->listaImpuestos($impuesto,true,$nivel->id_tc);
                                $archivo->getActiveSheet()->setCellValue($aux->letra.$fila,$impuesto->baseImponible);
                                $archivo->getActiveSheet()->getStyle($aux->letra.$fila)->getNumberFormat()->setFormatCode('0.00');
                            }
                            if($impuesto->valorRetenido>0){
                                $aux2    =   $this->listaImpuestos($impuesto,false,$nivel->id_tc);
                                $archivo->getActiveSheet()->setCellValue($aux2->letra.$fila,$impuesto->valorRetenido);
                                $archivo->getActiveSheet()->getStyle($aux2->letra.$fila)->getNumberFormat()->setFormatCode('0.00');
                            }
                        }
                    }else{
                        if($nivel->comprobante->impuestos->impuesto->baseImponible>0){
                            $aux    =   $this->listaImpuestos($nivel->comprobante->impuestos->impuesto,true,$nivel->id_tc);
                            $archivo->getActiveSheet()->setCellValue($aux->letra.$fila,$nivel->comprobante->impuestos->impuesto->baseImponible);
                            $archivo->getActiveSheet()->getStyle($aux->letra.$fila)->getNumberFormat()->setFormatCode('0.00');
                        }
                        if($nivel->comprobante->impuestos->impuesto->valorRetenido>0){
                            $aux2    =   $this->listaImpuestos($nivel->comprobante->impuestos->impuesto,false,$nivel->id_tc);
                            $archivo->getActiveSheet()->setCellValue($aux2->letra.$fila,$nivel->comprobante->impuestos->impuesto->valorRetenido);
                            $archivo->getActiveSheet()->getStyle($aux2->letra.$fila)->getNumberFormat()->setFormatCode('0.00');
                        }
                    }
                    $archivo->getActiveSheet()->getStyle("A$fila")
                        ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);

                    break;
                case 8://entradas a espectaculos
                    break;
                default:
                    break;
            }
            $fila++;
        }
        //Aumenta una columna para el total
        $this->letra++;

        $archivo->getActiveSheet()->getStyle("A$inicio:$this->letra$inicio")->getAlignment()->setHorizontal('center');
        //agrega el valor total de la factura
        $fila   =   $inicio+1;
        $aux_ano    =   "";
        $aux_mes    =   "";
        foreach ($data as $nivel){
            $fecha  =   Date::createFromFormat('Y-m-d',$nivel->fecha_co);
            $ano    =   $fecha->format('Y');
            $mes    =   $fecha->format('F');
            if($ano!==$aux_ano){
                $fila++;
                $aux_ano=$ano;
                $aux_mes=$mes;
            }
            if($mes!=$aux_mes){
                $fila++;
                $aux_mes=$mes;
            }
            $archivo->getActiveSheet()->setCellValue($this->letra.$fila,$nivel->valor);
            $archivo->getActiveSheet()->getStyle($this->letra.$fila)->getNumberFormat()->setFormatCode('0.00');
            $fila++;
        }

        //Titulos en los impuestos
        for ($i="J";$i<=$this->letra;$i++ ){
            $array      =   collect($this->impuestos);
            $resultado  =   $array->where('letra',$i)->first();
            if($resultado){
                if($resultado->base){
                    $archivo->getActiveSheet()->setCellValue($i.$inicio,"B.I. ".@$resultado->imp2->label." ".@$resultado->imp1->impuesto_t17);
                }else{
                    $archivo->getActiveSheet()->setCellValue($i.$inicio,@$resultado->imp2->label." ".@$resultado->imp1->impuesto_t17);
                }
            }
        }
        //Titulo al final
        $archivo->getActiveSheet()->setCellValue($this->letra.$inicio,"Total");

        //Ajuta el tamaño de la celda para todas las columnas usadas
        for ($i="A";$i<=$this->letra;$i++ ){
            if($i>="J"){
                $archivo->getActiveSheet()->getColumnDimension($i)->setWidth(12.7);
            }else{
                if($i==="I"){
                    $archivo->getActiveSheet()->getColumnDimension($i)->setWidth(67.7);
                }else{
                    $archivo->getActiveSheet()->getColumnDimension($i)->setAutoSize(true);
                }
            }
        }
        $archivo->getActiveSheet()->getStyle("A$inicio:".$this->letra.$inicio)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $archivo->getActiveSheet()->getStyle("A$inicio:".$this->letra.$inicio)->getAlignment()->setWrapText(true);

        //Combinar celdas
        $archivo->getActiveSheet()->mergeCells("A1:".$this->letra."1");
        $archivo->getActiveSheet()->mergeCells("A2:".$this->letra."2");
        $archivo->getActiveSheet()->mergeCells("A3:".$this->letra."3");

        $actual =   now();

        //log
        $this->registro->log(auth()->user(),$cliente,"Generacion de Excel con comprobantes",$request);


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=$nomArchivo.xlsx");
        header('Cache-Control: max-age=0');
        header("nombre: $nomArchivo $actual.xlsx");
        $salida = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($archivo, 'Xlsx');
        $salida->save('php://output');
        exit;
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
     * @param RangoRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(RangoRequest $request, $id)
    {
        $lista  = $this->comprobantes->consulta($id,$request->desde,$request->hasta);
        return response($lista);
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
}
