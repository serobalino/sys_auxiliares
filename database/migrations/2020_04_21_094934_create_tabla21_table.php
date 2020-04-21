<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTabla21Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tabla21', function (Blueprint $table) {
            $table->string('cod_t21',8)->primary();
            $table->string("detalle_t21",350)->nullable();
            $table->string("formulario_t21")->nullable();
            $table->float("porcentaje_t21")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tabla21');
    }
}
