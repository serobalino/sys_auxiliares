<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTabla18Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tabla18', function (Blueprint $table) {
            $table->bigInteger('cod_t18');
            $table->string("detalle_t18",350)->nullable();
            $table->float("especifico_t18")->nullable();
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
        Schema::dropIfExists('tabla18');
    }
}
