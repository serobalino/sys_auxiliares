<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistorialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historial', function (Blueprint $table) {
            $table->json('solicitud_hi')->nullable();
            $table->unsignedBigInteger('us_hi');
            $table->unsignedInteger('cl_hi');
            $table->string('tipo_hi');
            $table->timestamps();

            $table->foreign('cl_hi')->references('id_cl')->on('clientes');
            $table->foreign('us_hi')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historial', function (Blueprint $table) {
            $table->dropForeign(['cl_hi','us_hi']);
        });
    }
}
