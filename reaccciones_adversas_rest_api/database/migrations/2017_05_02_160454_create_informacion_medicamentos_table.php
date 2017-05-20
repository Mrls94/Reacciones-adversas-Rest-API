<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInformacionMedicamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('informacion_medicamentos', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('id_medicamento')->unsigned();
            $table->foreign('id_medicamento')->references('id')->on('medicamentos');
            
            $table->string('dosis');
            $table->string('unidad_de_medida');
            
            $table->integer('id_via_de_administracion')->unsigned();
            $table->foreign('id_via_de_administracion')->references('id')->on('via_de_administracions');
            
            $table->dateTime('fecha_inicio_medicamento');
            $table->dateTime('fecha_finalizacion_medicamento');
            
            $table->integer('id_mecanismo_causa_ra')->unsigned();
            $table->foreign('id_mecanismo_causa_ra')->references('id')->on('mecanismo_causa_ras');
            
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
        Schema::drop('informacion_medicamentos');
    }
}
