<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTercerosDirecciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terceros_direcciones', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tercero_id')->unsigned();
            $table->foreign('tercero_id')->references('id')->on('terceros');
            $table->string('nombre_personalizado', 200);
            $table->longText('direccion');
            $table->string('barrio', 200);
            $table->string('numero_contacto', 10);
            $table->bigInteger('usuario_inserta_id')->unsigned();
            $table->foreign('usuario_inserta_id')->references('id')->on('users');
            $table->dateTime('fecha_inserta', $precision = 0);
            $table->bigInteger('usuario_actualiza_id')->unsigned()->nullable();
            $table->foreign('usuario_actualiza_id')->references('id')->on('users');
            $table->dateTime('fecha_actualiza', $precision = 0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('terceros_direcciones');
    }
}
