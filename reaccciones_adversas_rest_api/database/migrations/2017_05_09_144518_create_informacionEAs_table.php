<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInformacionEAsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('informacion_eas', function (Blueprint $table) {
            //
            $table->increments('id');
            
            $table->dateTime('fecha_inicio');
            
            $table->integer('id_diagnostico')->unsigned();
            $table->foreign('id_diagnostico')->references('id')->on('diagnosticos');
            
            $table->string('descripcion');
            
            $table->integer('id_desenlace_ea')->unsigned();
            $table->foreign('id_desenlace_ea')->references('id')->on('desenlace_eas');
            
            $table->integer('id_seriedad')->unsigned();
            $table->foreign('id_seriedad')->references('id')->on('seriedads');
            
            $table->boolean('despues_de_administrar_farmaco');
            $table->boolean('desaparecio_a_suspension_farmaco');
            $table->boolean('misma_reaccion_a_farmaco');
            $table->string('ampliar_informacion');
            $table->string('otros_factores');
            
            
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('informacion_eas');
    }
}
