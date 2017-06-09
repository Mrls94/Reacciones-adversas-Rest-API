<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsDiagnosticosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_diagnosticos', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('id_report')->unsigned();
            $table->foreign('id_report')->references('id')->on('reports');
            
            $table->integer('id_diagnostico')->unsigned();
            $table->foreign('id_diagnostico')->references('id')->on('diagnosticos');
            
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
        Schema::drop('report_diagnosticos');
    }
}
