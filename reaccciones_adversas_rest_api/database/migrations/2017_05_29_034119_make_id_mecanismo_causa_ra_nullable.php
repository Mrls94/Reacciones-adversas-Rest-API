<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeIdMecanismoCausaRaNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('informacion_medicamentos', function (Blueprint $table) {
            //
            $table->integer('id_mecanismo_causa_ra')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('informacion_medicamentos', function (Blueprint $table) {
            //
            $table->integer('id_mecanismo_causa_ra')->unsigned()->change();
        });
    }
}
