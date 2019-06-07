<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearComprobantes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comprobantes', function (Blueprint $table) {
            $table->char('id_co',49)->primary();
            $table->unsignedInteger('id_cl');
            $table->unsignedInteger('id_tc');
            $table->date('fecha_co');
            $table->boolean('estado_co')->default(false);
            $table->longText('comprobante')->nullable();
            $table->timestamps();

            $table->foreign('id_cl')->references('id_cl')->on('clientes');
            $table->foreign('id_tc')->references('id_tc')->on('tipos_comprobante');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comprobantes', function (Blueprint $table) {
            $table->dropForeign(['id_cl','id_tc']);
        });
    }
}
