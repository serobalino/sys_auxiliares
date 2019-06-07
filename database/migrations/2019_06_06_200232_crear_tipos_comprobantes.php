<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTiposComprobantes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipos_comprobante', function (Blueprint $table) {
            $table->increments('id_tc');
            $table->string('detalle_tc');
        });

        DB::table('tipos_comprobante')->insert([
            [
                'id_tc' =>  1,
                'detalle_tc' =>  'Factura'
            ],
            [
                'id_tc' =>  2,
                'detalle_tc' =>  'Nota de venta'
            ],
            [
                'id_tc' =>  3,
                'detalle_tc' =>  'Liquidación de Compra de bienes o Prestación de servicios'
            ],
            [
                'id_tc' =>  4,
                'detalle_tc' =>  'Nota de Crédito'
            ],
            [
                'id_tc' =>  5,
                'detalle_tc' =>  'Nota de Débito'
            ],
            [
                'id_tc' =>  6,
                'detalle_tc' =>  'Guía de Remisión'
            ],
            [
                'id_tc' =>  7,
                'detalle_tc' =>  'Comprobante de Retención'
            ],
            [
                'id_tc' =>  8,
                'detalle_tc' =>  'Entradas a espectáculos públicos'
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipos_comprobante');
    }
}
