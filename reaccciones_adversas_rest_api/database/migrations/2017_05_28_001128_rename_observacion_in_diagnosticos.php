<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameObservacionInDiagnosticos extends Migration
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
            $table->renameColumn('observacion', 'observaciones');
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
            $table->renameColumn('observaciones', 'observacion');
        });
    }
}
