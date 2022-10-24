<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableTerceros extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::table('users', function(Blueprint $table){
           $table->bigInteger('tercero_id')->unsigned()->nullable();
           $table->foreign('tercero_id')->references('id')->on('terceros');
           $table->boolean('activo');
           $table->dateTime('fecha_ultimo_ingreso', $precision = 0)->nullable();
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
