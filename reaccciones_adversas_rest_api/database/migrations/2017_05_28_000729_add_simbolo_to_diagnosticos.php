<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSimboloToDiagnosticos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('diagnosticos', function (Blueprint $table) {
            //
            $table->string('simbolo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('diagnosticos', function (Blueprint $table) {
            //
            $table->dropColumn('simbolo');
        });
    }
}
