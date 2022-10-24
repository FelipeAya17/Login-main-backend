<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTerceros extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terceros', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tipo_documento_id')->unsigned();
            $table->foreign('tipo_documento_id')->references('id')->on('tipos_documentos');
            $table->string('numero_documento', 30);
            $table->string('nombres', 80);
            $table->string('apellidos', 80);
            $table->string('correo_electronico', 80)->nullable();
            $table->string('numero_telefonico', 20)->nullable();
            $table->string('numero_celular', 20)->nullable();
            $table->dateTime('fecha_registro', $precision = 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('terceros');
    }
}
