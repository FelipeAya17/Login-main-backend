<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableTercerosAddGeneros extends Migration{
    public function up(){
        Schema::table('terceros', function(Blueprint $table){
            $table->bigInteger('genero_id')->unsigned()->nullable();
            $table->foreign('genero_id')->references('id')->on('generos');
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
