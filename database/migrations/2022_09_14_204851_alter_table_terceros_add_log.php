<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableTercerosAddLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::table('terceros', function (Blueprint $table) {
            $table->bigInteger('usuario_inserta_id')->unsigned()->nullable();
            $table->foreign('usuario_inserta_id')->references('id')->on('users');
            $table->bigInteger('usuario_actualiza_id')->unsigned()->nullable();
            $table->foreign('usuario_actualiza_id')->references('id')->on('users');
            $table->dateTime('fecha_modifica', $precision = 0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
