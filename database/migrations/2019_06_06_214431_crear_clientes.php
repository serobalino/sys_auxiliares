<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearClientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("clientes", function (Blueprint $table) {
            $table->increments('id_cl');
            $table->string('nombres_cl');
            $table->string('apellidos_cl');
            $table->string('dni_cl',10);
            $table->string('ruc_cl',13);
            $table->string('razon_cl');
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
        Schema::dropIfExists('clientes');
    }
}
