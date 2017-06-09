<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReportsForignKey extends Migration
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
            $table->integer('id_report')->unsigned();
            $table->foreign('id_report')->references('id')->on('reports');
        });
        
        Schema::table('informacion_eas', function (Blueprint $table) {
            //
            $table->integer('id_report')->unsigned();
            $table->foreign('id_report')->references('id')->on('reports');
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
            $table->dropForeign(['id_report']);
            $table->dropColumn('id_report');
        });
        
        Schema::table('informacion_eas', function (Blueprint $table) {
            //
            $table->dropForeign(['id_report']);
            $table->dropColumn('id_report');
        });
    }
}
