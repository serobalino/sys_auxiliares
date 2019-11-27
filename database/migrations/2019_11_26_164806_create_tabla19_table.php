<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTabla19Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //lista de impuestos ICE
        Schema::create('tabla19', function (Blueprint $table) {
            $table->bigInteger('cod_t19');
            $table->string("detalle_t18",350)->nullable();
            $table->string("especifico_t18")->nullable();
            $table->float("valorem_t18")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tabla16');
    }
}
