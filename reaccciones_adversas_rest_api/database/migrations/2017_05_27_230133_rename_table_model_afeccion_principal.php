<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameTableModelAfeccionPrincipal extends Migration
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
            $table->renameColumn('afeccion_principal', 'no_son_afeccion_principal');
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
            $table->renameColumn('no_son_afeccion_principal', 'afeccion_principal');
        });
    }
}
