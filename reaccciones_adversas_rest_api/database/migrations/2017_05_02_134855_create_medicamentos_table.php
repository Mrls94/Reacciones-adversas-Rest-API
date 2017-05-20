<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicamentos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('medCodigo', 10);
            $table->string('medNombreGenerico');
            $table->string('medFormaFarmaceutica');
            $table->string('medConcentracion');
            $table->integer('medUnidadMedia');
            $table->integer('medRes008');
            $table->integer('medRes029');
            $table->integer('medRes5521');
            $table->integer('medRes5926');
            $table->integer('medRes5592');
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
        Schema::drop('medicamentos');
    }
}
