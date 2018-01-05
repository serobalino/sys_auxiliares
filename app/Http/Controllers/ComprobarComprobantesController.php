<?php

namespace App\Http\Controllers;

use App\Egresos\AuxiliarProveedor;
use App\Egresos\Factura;
use App\Egresos\ProductoProveedor;
use App\Egresos\Recibido;
use App\Tablas\Generado;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Egresos\FacturaAuxiliar;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jenssegers\Date\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;


class ComprobarComprobantesController extends Controller{
    public function clasificar(){

        //$auxiliares =   Generado::where('auxiliar_tg',true)->get();//lista los dos tipos de auxiliares
        $auxiliares_iva         =   Generado::where('tipo_tg','I')->get();//lista los dos tipos de auxiliares
        $auxiliares_renta       =   Generado::where('tipo_tg','R')->get();//lista los dos tipos de auxiliares

        $datos['apellidos_cl']  =   session('cliente')->apellidos_cl;
        $datos['nombres_cl']    =   session('cliente')->nombres_cl;
        $datos['ruc_cl']        =   session('cliente')->ruc_cl;
        $datos['razon_cl']      =   session('cliente')->razon_cl;

        $datos['numero']        =   collect(session('productos'))->count();
        $datos['detalle']       =   collect(session('productos'))->last();

        //return ($datos);

        //$datos['impuestos']     =   $this->impuestos_producto(collect(session('productos'))->last());


        $datos['botones_i']     =   $auxiliares_iva;
        $datos['botones_r']     =   $auxiliares_renta;

        //return($datos);



        //return $datos;
        return view('basico.parsearComprobantes',$datos);
    }

    public function guardar(Request $datos){
        $productos  =   ProductoProveedor::where('detalle_pp',$datos->detalle)->get();
        foreach ($productos as $producto){
            $verificar  =   FacturaAuxiliar::where('id_cl',session('cliente')->id_cl)->where('version_pp',$producto->version_pp)->where('detalle_pp',$producto->detalle_pp)->first();
            if(!$verificar){
                $auxiliar   =   new FacturaAuxiliar();
                $auxiliar->id_cl        =   session('cliente')->id_cl;
                $auxiliar->version_pp   =   $producto->version_pp;
                $auxiliar->detalle_pp   =   $producto->detalle_pp;
                $auxiliar->id_ad        =   Auth::guard('adm')->user()->id_ad;
                if($datos->auxiliar!=='false')
                    $auxiliar->id_tg        =   $datos->auxiliar;
                $auxiliar->save();
            }
        }
        return redirect(route('adm.cli.aux',session('cliente')->ruc_cl));
    }

    /**
     *
     * guarda un provedor dependiendo para el auxiliar que le sirve
     * resive @ruc @id_de_auxiliar
     */
    public function guardarProv(Request $datos){
        $verificar  =   AuxiliarProveedor::where('ruc_pr',$datos->ruc)->first();
        if(!$verificar){
            $verificar = new AuxiliarProveedor();
            $verificar->ruc_pr  =   $datos->ruc;
            $verificar->id_cl   =   session('cliente')->id_cl;
            $verificar->id_tg   =   $datos->auxiliar;;
            $verificar->id_ad   =   Auth::guard('adm')->user()->id_ad;;
            $verificar->save();
        }
        return redirect(route('adm.cli.aux',session('cliente')->ruc_cl));
    }

    public function generar(){
        $auxiliares        =   Generado::join('impuestos', 'impuestos.codigo_im', '=', 'tipo_generados.codigo_im')->where('tipo_tg','I')->orWhere('tipo_tg','R')->get();//lista los dos tipos de auxiliares
        return view('basico.fechaAuxiliar',['cliente'=>session('cliente'),'aux'=>$auxiliares,]);
    }
    public function resultado(Request $datos){
        /*
        $egresos    =   Recibido::where('id_cl',session('cliente')->id_cl)->get();
        $facturas   =   Factura::whereBetween('fechae_ef',[$datos->inicio,$datos->fin])->get();
        */



        $usuario    =   session('cliente')->id_cl;
        $inicio     =   $datos->inicio;
        $fin        =   $datos->fin;
        $caracter   =   $datos->tipo;



        if($caracter>0)
            $inject =   "id_tg=$caracter";
        else
            $inject =   "tipo_tg='$caracter'";

        $query    =   DB::select("SELECT DISTINCT(CONCAT(pemision_ac,'-',codigo_su,'-',secuencial_eg,'-',codigo_tc,'-',ruc_pr)) com,CONCAT(codigo_su,'-',pemision_ac,'-',secuencial_eg) AS id_,ruc_pr ruc,
                                    razons_pr razon,nombrec_pr comercial,fingreso_eg ingreso,fechae_ef emision,ROUND(tsimpuestos_ef,2) tsi,ROUND(tdescuento_ef,2) td,ROUND(propina_ef,2) pro,ROUND(itotal_ef,2) inp,ROUND(timpuesto_ef,2) imp,moneda_ef moneda,label_tg aux,contabilidad_eg conta,nombre_tc compro,
                                    (SELECT detalle_pp FROM detalle_facturas WHERE CONCAT(pemision_ac,'-',codigo_su,'-',secuencial_eg,'-',codigo_tc,'-',ruc_pr)=com LIMIT 1) detalle,
                                    (SELECT COUNT(detalle_pp) FROM detalle_facturas WHERE CONCAT(pemision_ac,'-',codigo_su,'-',secuencial_eg,'-',codigo_tc,'-',ruc_pr)=com) prod,
                                    ROUND((SELECT valor_if FROM impuesto_facturas WHERE CONCAT(pemision_ac,'-',codigo_su,'-',secuencial_eg,'-',codigo_tc,'-',ruc_pr)=com AND codigo_im=2 AND codigo_po=0),2) iva0,
                                    ROUND((SELECT valor_if FROM impuesto_facturas WHERE CONCAT(pemision_ac,'-',codigo_su,'-',secuencial_eg,'-',codigo_tc,'-',ruc_pr)=com AND codigo_im=2 AND codigo_po=2),2) iva12,
                                    ROUND((SELECT valor_if FROM impuesto_facturas WHERE CONCAT(pemision_ac,'-',codigo_su,'-',secuencial_eg,'-',codigo_tc,'-',ruc_pr)=com AND codigo_im=2 AND codigo_po=3),2) iva14,
                                    ROUND((SELECT valor_if FROM impuesto_facturas WHERE CONCAT(pemision_ac,'-',codigo_su,'-',secuencial_eg,'-',codigo_tc,'-',ruc_pr)=com AND codigo_im=2 AND codigo_po=6),2) noiva,
                                    ROUND((SELECT valor_if FROM impuesto_facturas WHERE CONCAT(pemision_ac,'-',codigo_su,'-',secuencial_eg,'-',codigo_tc,'-',ruc_pr)=com AND codigo_im=2 AND codigo_po=7),2) siniva,
                                    ROUND((SELECT valor_if FROM impuesto_facturas WHERE CONCAT(pemision_ac,'-',codigo_su,'-',secuencial_eg,'-',codigo_tc,'-',ruc_pr)=com AND codigo_im=3 AND codigo_po=3111),2) iceAlc,
                                    ROUND((SELECT valor_if FROM impuesto_facturas WHERE CONCAT(pemision_ac,'-',codigo_su,'-',secuencial_eg,'-',codigo_tc,'-',ruc_pr)=com AND codigo_im=3 AND codigo_po=3092),2) iceTv,
                                    ROUND((SELECT valor_if FROM impuesto_facturas WHERE CONCAT(pemision_ac,'-',codigo_su,'-',secuencial_eg,'-',codigo_tc,'-',ruc_pr)=com AND codigo_im=3 AND codigo_po=3093),2) iceTel
                                    FROM auxiliar_proveedor fa NATURAL JOIN tipo_generados tg NATURAL JOIN detalle_facturas df NATURAL JOIN egresos_facturas NATURAL JOIN egresos NATURAL JOIN proveedores NATURAL JOIN tipo_comprobante
                                    WHERE $inject AND id_cl=$usuario AND fechae_ef BETWEEN '$inicio' AND '$fin'
                                    UNION 
                                    
                                    SELECT DISTINCT(CONCAT(pemision_ac,'-',codigo_su,'-',secuencial_eg,'-',codigo_tc,'-',ruc_pr)) com,CONCAT(codigo_su,'-',pemision_ac,'-',secuencial_eg) AS id_,ruc_pr ruc,
                                    razons_pr razon,nombrec_pr comercial,fingreso_eg ingreso,fechae_enc emision,ROUND(tsimpuestos_enc,2) tsi,ROUND(0,2) td,ROUND(0,2) pro,ROUND(vmodificado_enc,2) inp,ROUND(timpuestos_enc,2) imp,moneda_enc moneda,label_tg aux,contabilidad_eg conta,nombre_tc compro,
                                    (SELECT detalle_dp FROM detalle_notasc WHERE CONCAT(pemision_ac,'-',codigo_su,'-',secuencial_eg,'-',codigo_tc,'-',ruc_pr)=com LIMIT 1) detalle,
                                    (SELECT COUNT(detalle_dp) FROM detalle_notasc WHERE CONCAT(pemision_ac,'-',codigo_su,'-',secuencial_eg,'-',codigo_tc,'-',ruc_pr)=com) prod,
                                    ROUND((SELECT valor_inc FROM impuesto_notasc WHERE CONCAT(pemision_ac,'-',codigo_su,'-',secuencial_eg,'-',codigo_tc,'-',ruc_pr)=com AND codigo_im=2 AND codigo_po=0),2) iva0,
                                    ROUND((SELECT valor_inc FROM impuesto_notasc WHERE CONCAT(pemision_ac,'-',codigo_su,'-',secuencial_eg,'-',codigo_tc,'-',ruc_pr)=com AND codigo_im=2 AND codigo_po=2),2) iva12,
                                    ROUND((SELECT valor_inc FROM impuesto_notasc WHERE CONCAT(pemision_ac,'-',codigo_su,'-',secuencial_eg,'-',codigo_tc,'-',ruc_pr)=com AND codigo_im=2 AND codigo_po=3),2) iva14,
                                    ROUND((SELECT valor_inc FROM impuesto_notasc WHERE CONCAT(pemision_ac,'-',codigo_su,'-',secuencial_eg,'-',codigo_tc,'-',ruc_pr)=com AND codigo_im=2 AND codigo_po=6),2) noiva,
                                    ROUND((SELECT valor_inc FROM impuesto_notasc WHERE CONCAT(pemision_ac,'-',codigo_su,'-',secuencial_eg,'-',codigo_tc,'-',ruc_pr)=com AND codigo_im=2 AND codigo_po=7),2) siniva,
                                    ROUND((SELECT valor_inc FROM impuesto_notasc WHERE CONCAT(pemision_ac,'-',codigo_su,'-',secuencial_eg,'-',codigo_tc,'-',ruc_pr)=com AND codigo_im=3 AND codigo_po=3111),2) iceAlc,
                                    ROUND((SELECT valor_inc FROM impuesto_notasc WHERE CONCAT(pemision_ac,'-',codigo_su,'-',secuencial_eg,'-',codigo_tc,'-',ruc_pr)=com AND codigo_im=3 AND codigo_po=3092),2) iceTv,
                                    ROUND((SELECT valor_inc FROM impuesto_notasc WHERE CONCAT(pemision_ac,'-',codigo_su,'-',secuencial_eg,'-',codigo_tc,'-',ruc_pr)=com AND codigo_im=3 AND codigo_po=3093),2) iceTel
                                    FROM auxiliar_proveedor nc NATURAL JOIN tipo_generados tg NATURAL JOIN detalle_notasc dnc NATURAL JOIN egresos_notasc NATURAL JOIN egresos NATURAL JOIN proveedores NATURAL JOIN tipo_comprobante
                                    WHERE $inject AND id_cl=$usuario AND fechae_enc BETWEEN '$inicio' AND '$fin'
                                    
                                    UNION
                                    SELECT DISTINCT(CONCAT(pemision_ac,'-',codigo_su,'-',secuencial_eg,'-',codigo_tc,'-',ruc_pr)) com,CONCAT(codigo_su,'-',pemision_ac,'-',secuencial_eg) AS id_,ruc_pr ruc,
                                    razons_pr razon,nombrec_pr comercial,fingreso_eg ingreso,fechae_end emision,ROUND(tsimpuestos_end,2) tsi,ROUND(0,2) td,ROUND(0,2) pro,ROUND(vtotal_end,2) inp,ROUND(timpuestos_end,2) imp,null moneda,'Débito' aux,contabilidad_eg conta,nombre_tc compro,
                                    (SELECT descripcion_mc FROM motivos_nota_debito WHERE CONCAT(pemision_ac,'-',codigo_su,'-',secuencial_eg,'-',codigo_tc,'-',ruc_pr)=com LIMIT 1) detalle,
                                    (SELECT COUNT(descripcion_mc) FROM motivos_nota_debito WHERE CONCAT(pemision_ac,'-',codigo_su,'-',secuencial_eg,'-',codigo_tc,'-',ruc_pr)=com) prod,
                                    ROUND((SELECT valor_ind FROM impuesto_notasd WHERE CONCAT(pemision_ac,'-',codigo_su,'-',secuencial_eg,'-',codigo_tc,'-',ruc_pr)=com AND codigo_im=2 AND codigo_po=0),2) iva0,
                                    ROUND((SELECT valor_ind FROM impuesto_notasd WHERE CONCAT(pemision_ac,'-',codigo_su,'-',secuencial_eg,'-',codigo_tc,'-',ruc_pr)=com AND codigo_im=2 AND codigo_po=2),2) iva12,
                                    ROUND((SELECT valor_ind FROM impuesto_notasd WHERE CONCAT(pemision_ac,'-',codigo_su,'-',secuencial_eg,'-',codigo_tc,'-',ruc_pr)=com AND codigo_im=2 AND codigo_po=3),2) iva14,
                                    ROUND((SELECT valor_ind FROM impuesto_notasd WHERE CONCAT(pemision_ac,'-',codigo_su,'-',secuencial_eg,'-',codigo_tc,'-',ruc_pr)=com AND codigo_im=2 AND codigo_po=6),2) noiva,
                                    ROUND((SELECT valor_ind FROM impuesto_notasd WHERE CONCAT(pemision_ac,'-',codigo_su,'-',secuencial_eg,'-',codigo_tc,'-',ruc_pr)=com AND codigo_im=2 AND codigo_po=7),2) siniva,
                                    ROUND((SELECT valor_ind FROM impuesto_notasd WHERE CONCAT(pemision_ac,'-',codigo_su,'-',secuencial_eg,'-',codigo_tc,'-',ruc_pr)=com AND codigo_im=3 AND codigo_po=3111),2) iceAlc,
                                    ROUND((SELECT valor_ind FROM impuesto_notasd WHERE CONCAT(pemision_ac,'-',codigo_su,'-',secuencial_eg,'-',codigo_tc,'-',ruc_pr)=com AND codigo_im=3 AND codigo_po=3092),2) iceTv,
                                    ROUND((SELECT valor_ind FROM impuesto_notasd WHERE CONCAT(pemision_ac,'-',codigo_su,'-',secuencial_eg,'-',codigo_tc,'-',ruc_pr)=com AND codigo_im=3 AND codigo_po=3093),2) iceTel
                                    FROM  motivos_nota_debito mnd NATURAL JOIN egresos_notasd NATURAL JOIN egresos NATURAL JOIN proveedores NATURAL JOIN tipo_comprobante
                                    WHERE id_cl=$usuario  AND fechae_end BETWEEN '$inicio' AND '$fin'
                                    ORDER BY emision");
        return ($query);
    }
    /*
     *
     *
     * */
    private function impuestos_producto($detalles){

        switch ($detalles->codigo_tc){
            case 1:
                // factura
                $clave      =   $detalles->detalle_pp;
                $version    =   $detalles->version_pp;
                $query      =   DB::select("SELECT impuesto_im detalle,porcentaje_po porcentaje,valor_ip valor
                                            FROM productos_proveedores NATURAL JOIN impuestos_productos_pr NATURAL JOIN porcentajes NATURAL JOIN impuestos
                                            WHERE detalle_pp='$clave' AND version_pp=$version");
                break;
            case 2:
                //nota de venta
                break;
            case 3:
                //liquidacion de compras
                break;
            case 4:
                //nota de credito
                $clave      =   $detalles->detalle_dp;
                $version    =   $detalles->version_dp;
                $query      =   DB::select("SELECT impuesto_im detalle,porcentaje_po porcentaje,valor_id valor
                                            FROM descuentos_proveedores NATURAL JOIN impuestos_descuentos_pr NATURAL JOIN porcentajes NATURAL JOIN impuestos
                                            WHERE detalle_dp='$clave' AND version_dp=$version");
                break;
            case 5:
                //nota de debito
                break;
            case 6:
                //guia de remicion
                break;
            case 7:
                //Comprobante de retencion
                break;
            case 8:
                //ntradas a espectculos
                break;
            default:
                $query      =   false;
                break;
        }
        return($query);
    }

    public function generar_excel(Request $datos){

        $fechaInici =   Carbon::createFromFormat('Y-m-d', $datos->inicio)->toDateTimeString();
        $fechaFin   =   Carbon::createFromFormat('Y-m-d', $datos->fin)->toDateTimeString();
        $nomArchivo =   session('cliente')->razon_cl." ".$fechaInici." ".$fechaFin;

        $query      =   $this->resultado($datos);
        $archivo    =   new Spreadsheet();
        $archivo->getProperties()->setCreator('ASECONT - PUYO');

        $archivo->getActiveSheet()->setCellValue('A1',session('cliente')->apellidos_cl." ".session('cliente')->nombres_cl);
        $archivo->getActiveSheet()->setCellValue('A2',session('cliente')->razon_cl);
        $archivo->getActiveSheet()->setCellValue('A3',session('cliente')->ruc_cl);

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


        $fila   =   5;
        $cont   =   0;

        //auxiliares
        $aux_ano    =   "";
        $aux_mes    =   "";

        $sum_ano_siniva   =   0.0;
        $sum_mes_siniva   =   0.0;
        $sum_ano_noiva    =   0.0;
        $sum_mes_noiva    =   0.0;
        $sum_ano_iva0     =   0.0;
        $sum_mes_iva0     =   0.0;
        $sum_ano_iva12    =   0.0;
        $sum_mes_iva12    =   0.0;
        $sum_ano_iva14    =   0.0;
        $sum_mes_iva14    =   0.0;
        $sum_ano_td       =   0.0;
        $sum_mes_td       =   0.0;
        $sum_ano_inp      =   0.0;
        $sum_mes_inp      =   0.0;


        $archivo->getActiveSheet()->setCellValue("A$fila","Num");
        $archivo->getActiveSheet()->setCellValue("B$fila","Fecha");
        $archivo->getActiveSheet()->setCellValue("C$fila","Secuencial");
        $archivo->getActiveSheet()->setCellValue("D$fila","RUC");
        $archivo->getActiveSheet()->setCellValue("E$fila","Nombre Comercial");
        $archivo->getActiveSheet()->setCellValue("F$fila","Detalle");
        $archivo->getActiveSheet()->setCellValue("G$fila","Base Imponible");
        $archivo->getActiveSheet()->setCellValue("H$fila","Sin IVA");
        $archivo->getActiveSheet()->setCellValue("I$fila","No tiene IVA");
        $archivo->getActiveSheet()->setCellValue("J$fila","Tarifa 0");
        $archivo->getActiveSheet()->setCellValue("K$fila","Tarifa 12");
        $archivo->getActiveSheet()->setCellValue("L$fila","Tarifa 14");
        $archivo->getActiveSheet()->setCellValue("M$fila","Total");
        $fila++;



        Date::setLocale('es');
        foreach ($query as $nivel){
            //fecha
            $fecha  =   Date::createFromFormat('Y-m-d',$nivel->emision);
            $ano    =   $fecha->format('Y');
            $dia    =   $fecha->format('j');
            $mes    =   $fecha->format('F');
            $minMes =   mb_strtoupper(substr($mes, 0,3));
            $sum_ano_siniva   +=   $nivel->siniva;
            $sum_ano_noiva    +=   $nivel->noiva;
            $sum_ano_iva0     +=   $nivel->iva0;
            $sum_ano_iva12    +=   $nivel->iva12;
            $sum_ano_iva14    +=   $nivel->iva14;
            $sum_ano_td       +=   $nivel->td;
            $sum_ano_inp      +=   $nivel->inp;
            if($ano!=$aux_ano){
                $archivo->getActiveSheet()->insertNewRowBefore($fila,1);
                $archivo->getActiveSheet()->setCellValue("A$fila",$ano);
                $fila++;
                $aux_ano=$ano;
                $aux_mes=$mes;

                //auxiliares totales
                $sum_ano_siniva   =   0.0;
                $sum_ano_noiva    =   0.0;
                $sum_ano_iva0     =   0.0;
                $sum_ano_iva12    =   0.0;
                $sum_ano_iva14    =   0.0;
                $sum_ano_td       =   0.0;
                $sum_ano_inp      =   0.0;
            }
            if($mes!=$aux_mes){
                $archivo->getActiveSheet()->insertNewRowBefore($fila,1);
                /*
                $archivo->getActiveSheet()->setCellValue("G$fila",$sum_mes_siniva);
                $archivo->getActiveSheet()->setCellValue("H$fila",$sum_mes_noiva);
                $archivo->getActiveSheet()->setCellValue("I$fila",$sum_mes_iva0);
                $archivo->getActiveSheet()->setCellValue("J$fila",$sum_mes_iva12);
                $archivo->getActiveSheet()->setCellValue("K$fila",$sum_mes_iva14);
                $archivo->getActiveSheet()->setCellValue("L$fila",$sum_mes_td);
                $archivo->getActiveSheet()->setCellValue("M$fila",$sum_mes_inp);
                */
                $fila++;
                $aux_mes=$mes;
                $archivo->getActiveSheet()->insertNewRowBefore($fila,1);
                $fila++;

                $sum_mes_siniva   =   0.0;
                $sum_mes_noiva    =   0.0;
                $sum_mes_iva0     =   0.0;
                $sum_mes_iva12    =   0.0;
                $sum_mes_iva14    =   0.0;
                $sum_mes_td       =   0.0;
                $sum_mes_inp      =   0.0;
            }else{
                $sum_mes_siniva   +=   $nivel->siniva;
                $sum_mes_noiva    +=   $nivel->noiva;
                $sum_mes_iva0     +=   $nivel->iva0;
                $sum_mes_iva12    +=   $nivel->iva12;
                $sum_mes_iva14    +=   $nivel->iva14;
                $sum_mes_td       +=   $nivel->td;
                $sum_mes_inp      +=   $nivel->inp;
            }
            $producto=mb_strtoupper($nivel->detalle);
            if($nivel->prod>1)
                $producto="VARIOS PRODUCTOS";

            $empresa=mb_strtoupper($nivel->comercial);
            if(!$empresa)
                $empresa=mb_strtoupper($nivel->razon);
            $cont++;
            $archivo->getActiveSheet()->insertNewRowBefore($fila,1);
            $archivo->getActiveSheet()->setCellValue("A$fila",$cont)
                                      ->setCellValue("B$fila","$minMes-$dia")
                                      ->setCellValue("C$fila",$nivel->id_)
                                      ->setCellValue("D$fila",$nivel->ruc)
                                      ->setCellValue("E$fila",$empresa)
                                      ->setCellValue("F$fila",$producto)

                                      ->setCellValue("G$fila",$nivel->tsi)
                                      ->setCellValue("H$fila",$nivel->siniva)
                                      ->setCellValue("I$fila",$nivel->noiva)
                                      ->setCellValue("J$fila",$nivel->iva0)
                                      ->setCellValue("K$fila",$nivel->iva12)
                                      ->setCellValue("L$fila",$nivel->iva14)
                                      //->setCellValue("M$fila",$nivel->td)descuento
                                      ->setCellValue("M$fila",$nivel->inp);
            //formato de dos decimales a las columnas con numeros
            $archivo->getActiveSheet()->getStyle("G$fila")->getNumberFormat()->setFormatCode('0.00');
            $archivo->getActiveSheet()->getStyle("H$fila")->getNumberFormat()->setFormatCode('0.00');
            $archivo->getActiveSheet()->getStyle("I$fila")->getNumberFormat()->setFormatCode('0.00');
            $archivo->getActiveSheet()->getStyle("J$fila")->getNumberFormat()->setFormatCode('0.00');
            $archivo->getActiveSheet()->getStyle("K$fila")->getNumberFormat()->setFormatCode('0.00');
            $archivo->getActiveSheet()->getStyle("L$fila")->getNumberFormat()->setFormatCode('0.00');
            $archivo->getActiveSheet()->getStyle("M$fila")->getNumberFormat()->setFormatCode('0.00');
            $fila++;

        }
        $archivo->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
        $archivo->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
        $archivo->getActiveSheet()->getColumnDimension("C")->setAutoSize(true);
        $archivo->getActiveSheet()->getColumnDimension("D")->setAutoSize(true);
        $archivo->getActiveSheet()->getColumnDimension("E")->setAutoSize(true);
        $archivo->getActiveSheet()->getColumnDimension("F")->setAutoSize(true);
        $archivo->getActiveSheet()->getColumnDimension("G")->setAutoSize(true);
        $archivo->getActiveSheet()->getColumnDimension("H")->setAutoSize(true);
        $archivo->getActiveSheet()->getColumnDimension("I")->setAutoSize(true);
        $archivo->getActiveSheet()->getColumnDimension("J")->setAutoSize(true);
        $archivo->getActiveSheet()->getColumnDimension("K")->setAutoSize(true);
        $archivo->getActiveSheet()->getColumnDimension("L")->setAutoSize(true);
        $archivo->getActiveSheet()->getColumnDimension("M")->setAutoSize(true);


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=$nomArchivo.xlsx");
        header('Cache-Control: max-age=0');
        $salida = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($archivo, 'Xlsx');
        $salida->save('php://output');
        exit;
    }
}
