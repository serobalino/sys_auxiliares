<?php

namespace App\Http\Controllers;

use App\Catalogos\Tabla17;
use App\Catalogos\Tabla18;
use App\Catalogos\Tabla19;
use App\Cliente;
use App\Comprobante;
use App\Http\Requests\Comprobantes\Lista\RangoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Date\Date;
use PHPExcel_Cell_DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Http\Controllers\ComprobantesController;

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
    private $letra      =   "K";

    /**
     * @param $impuesto
     * @return object
     */
    private function listaImpuestos($impuesto){
        $array      =   collect($this->impuestos);
        $resultado  =   $array->where('codigo',$impuesto->codigo)->where('codigoPorcentaje',$impuesto->codigoPorcentaje)->first();
        if($resultado){
            return (object)$resultado;
        }else{
            $this->letra++;
            $obj    =   [
                'letra'=>$this->letra,
                'codigo'=>$impuesto->codigo,
                'codigoPorcentaje'=>$impuesto->codigoPorcentaje,
                'tarifa'=>@$impuesto->tarifa,
                'imp'=>Tabla17::find($impuesto->codigo),
                'por'=>Tabla18::find($impuesto->codigoPorcentaje) ? Tabla18::find($impuesto->codigoPorcentaje) : Tabla19::find($impuesto->codigoPorcentaje)
            ];
            $obj    =   (object)$obj;
            $array->push($obj);
            $this->impuestos=$array;
            return $obj;
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
        $nomArchivo =    preg_replace('/[^A-Za-z0-9\-]/', '', $nomArchivo);
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
        $archivo->getActiveSheet()->setCellValue("J$fila","Base Imponible");
        $archivo->getActiveSheet()->setCellValue("K$fila","Descuento");

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
                ->setCellValueExplicit("D$fila",$nivel->comprobante->infoTributaria->estab,PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit("E$fila",$nivel->comprobante->infoTributaria->ptoEmi,PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit("F$fila",$nivel->comprobante->infoTributaria->secuencial,PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit("G$fila",$nivel->comprobante->infoTributaria->ruc,PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue("H$fila",$empresa)
                ->setCellValue("I$fila",$producto)
                ->setCellValue("J$fila",$nivel->comprobante->info->totalSinImpuestos)
                ->setCellValue("K$fila",$nivel->comprobante->info->totalDescuento>0 ? $nivel->comprobante->info->totalDescuento : '');

            $archivo->getActiveSheet()->getStyle("G$fila")->getNumberFormat()->setFormatCode('0.00');
            $archivo->getActiveSheet()->getStyle("H$fila")->getNumberFormat()->setFormatCode('0.00');

            if(gettype($nivel->comprobante->info->totalConImpuestos->totalImpuesto)==="array"){
                foreach ($nivel->comprobante->info->totalConImpuestos->totalImpuesto as $impuesto){
                    if($impuesto->baseImponible>0){
                        $aux    =   $this->listaImpuestos($impuesto);
                        $archivo->getActiveSheet()->setCellValue($aux->letra.$fila,$impuesto->baseImponible);
                        $archivo->getActiveSheet()->getStyle($aux->letra.$fila)->getNumberFormat()->setFormatCode('0.00');
                    }
                }
            }else{
                if($nivel->comprobante->info->totalConImpuestos->totalImpuesto->baseImponible>0){
                    $aux    =   $this->listaImpuestos($nivel->comprobante->info->totalConImpuestos->totalImpuesto);
                    $archivo->getActiveSheet()->setCellValue($aux->letra.$fila,$nivel->comprobante->info->totalConImpuestos->totalImpuesto->baseImponible);
                    $archivo->getActiveSheet()->getStyle($aux->letra.$fila)->getNumberFormat()->setFormatCode('0.00');
                }
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
            $archivo->getActiveSheet()->setCellValue($this->letra.$fila,$nivel->comprobante->info->importeTotal);
            $archivo->getActiveSheet()->getStyle($this->letra.$fila)->getNumberFormat()->setFormatCode('0.00');
            $fila++;
        }

        //Titulos en los impuestos
        for ($i="L";$i<=$this->letra;$i++ ){
            $array      =   collect($this->impuestos);
            $resultado  =   $array->where('letra',$i)->first();
            if($resultado)
            $archivo->getActiveSheet()->setCellValue($i.$inicio,$resultado->por->detalle_t18." ".$resultado->imp->impuesto_t17);
        }
        //Titulo al final
        $archivo->getActiveSheet()->setCellValue($this->letra.$inicio,"Total");

        //Ajuta el tamaño de la celda para todas las columnas usadas
        for ($i="A";$i<=$this->letra;$i++ ){
            $archivo->getActiveSheet()->getColumnDimension($i)->setAutoSize(true);
        }

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
