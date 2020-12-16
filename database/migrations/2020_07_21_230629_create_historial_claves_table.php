<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistorialClavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historial_claves', function (Blueprint $table) {
            $table->unsignedInteger('id_tc');
            $table->unsignedInteger('id_cl');
            $table->boolean('estado_hc')->default(true);
            $table->string('contrasena_hc');
            $table->foreign('id_tc')->references('id_tc')->on('tipo_clave');
            $table->foreign('id_cl')->references('id_cl')->on('clientes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historial_claves');
    }
}
