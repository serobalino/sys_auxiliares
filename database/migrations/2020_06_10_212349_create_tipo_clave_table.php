<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoClaveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_clave', function (Blueprint $table) {
            $table->increments('id_tc');
            $table->string('nombre_tc');
        });
        DB::table('tipo_clave')->insert([
            [
                'id_tc' =>  1,
                'nombre_tc' =>  'SRI'
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
        Schema::dropIfExists('tipo_clave');
    }
}
