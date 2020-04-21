<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTabla20Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tabla20', function (Blueprint $table) {
            $table->bigInteger('cod_t20')->primary();
            $table->string("detalle_t20",350)->nullable();
            $table->tinyInteger("porcentaje_t20");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tabla20');
    }
}
