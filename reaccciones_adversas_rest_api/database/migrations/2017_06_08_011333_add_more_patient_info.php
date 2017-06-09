<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMorePatientInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            //
            $table->string('lugar');
            $table->integer('cama');
            $table->longText('mas_informacion_diagnosticos');
            $table->string('efecto_adverso');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            //
            $table->dropColumn(['lugar', 'cama', 'mas_informacion_diagnosticos', 'efecto_adverso']);
        });
    }
}
