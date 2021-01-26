<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTiposComprobantes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('tipos_comprobante')->insert([
            [
                'id_tc' => 9,
                'detalle_tc' => 'Tiquetes o vales emitidos por máquinas registradoras'
            ],
            [
                'id_tc' => 11,
                'detalle_tc' => 'Tiquetes o vales emitidos por máquinas registradoras'
            ],
            [
                'id_tc' => 12,
                'detalle_tc' => 'Documentos emitidos por instituciones financieras'
            ],
            [
                'id_tc' => 15,
                'detalle_tc' => 'Comprobante de venta emitido en el Exterior'
            ],
            [
                'id_tc' => 16,
                'detalle_tc' => 'Formulario Único de Exportación (FUE) o Declaración Aduanera Única (DAU) o Declaración Andina de Valor '
            ],
            [
                'id_tc' => 18,
                'detalle_tc' => 'Documentos autorizados utilizados en ventas excepto N/C N/D '
            ],
            [
                'id_tc' => 19,
                'detalle_tc' => 'Comprobantes de Pago de Cuotas o Aportes'
            ],
            [
                'id_tc' => 20,
                'detalle_tc' => 'Documentos por Servicios Administrativos emitidos por Inst. del Estado'
            ],
            [
                'id_tc' => 21,
                'detalle_tc' => 'Carta de Porte Aéreo'
            ],
            [
                'id_tc' => 22,
                'detalle_tc' => 'RECAP'
            ],
            [
                'id_tc' => 23,
                'detalle_tc' => 'Nota de Crédito TC'
            ],
            [
                'id_tc' => 24,
                'detalle_tc' => 'Nota de Débito TC'
            ],
            [
                'id_tc' => 41,
                'detalle_tc' => 'Comprobante de venta emitido por reembolso'
            ],
            [
                'id_tc' => 42,
                'detalle_tc' => 'Documento retención presuntiva y retención emitida por propio vendedor o por intermediario'
            ],
            [
                'id_tc' => 43,
                'detalle_tc' => 'Liquidación para Explotación y Exploracion de Hidrocarburos'
            ],
            [
                'id_tc' => 44,
                'detalle_tc' => 'Comprobante de Contribuciones y Aportes'
            ],
            [
                'id_tc' => 45,
                'detalle_tc' => 'Liquidación por reclamos de aseguradoras'
            ],
            [
                'id_tc' => 47,
                'detalle_tc' => 'Nota de Crédito por Reembolso Emitida por Intermediario'
            ],
            [
                'id_tc' => 48,
                'detalle_tc' => 'Nota de Débito por Reembolso Emitida por Intermediario'
            ],
            [
                'id_tc' => 49,
                'detalle_tc' => 'Proveedor Directo de Exportador Bajo Régimen Especial'
            ],
            [
                'id_tc' => 50,
                'detalle_tc' => 'A Inst. Estado y Empr. Públicas que percibe ingreso exento de Imp. Renta'
            ],
            [
                'id_tc' => 51,
                'detalle_tc' => 'N/C A Inst. Estado y Empr. Públicas que percibe ingreso exento de Imp. Renta'
            ],
            [
                'id_tc' => 52,
                'detalle_tc' => 'N/D A Inst. Estado y Empr. Públicas que percibe ingreso exento de Imp. Renta'
            ],
            [
                'id_tc' => 294,
                'detalle_tc' => 'Liquidación de compra de Bienes Muebles Usados'
            ],
            [
                'id_tc' => 344,
                'detalle_tc' => 'Liquidación de compra de vehículos usados '
            ],
            [
                'id_tc' => 364,
                'detalle_tc' => 'Acta Entrega-Recepción PET'
            ],
            [
                'id_tc' => 370,
                'detalle_tc' => 'Factura operadora transporte/socio'
            ],
            [
                'id_tc' => 371,
                'detalle_tc' => 'Comprobante socio a operadora de transporte '
            ],
            [
                'id_tc' => 372,
                'detalle_tc' => 'Nota de crédito operadora transporte/socio'
            ],
            [
                'id_tc' => 373,
                'detalle_tc' => 'Nota de débito operadora transporte/socio'
            ],
            [
                'id_tc' => 374,
                'detalle_tc' => 'Nota de débito operadora transporte/socio'
            ],
            [
                'id_tc' => 375,
                'detalle_tc' => 'Liquidación de compra RISE de bienes o prestación de servicios'
            ],

        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
