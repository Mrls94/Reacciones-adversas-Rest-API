<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExtendCodigoVarcharMedicamentosDiagnosticos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('medicamentos', function (Blueprint $table) {
            $table->string('medCodigo', 255)->change();
        });
        
        Schema::table('diagnosticos', function (Blueprint $table) {
            $table->string('codigo', 255)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('medicamentos', function (Blueprint $table) {
            $table->string('medCodigo', 10)->change();
        });
        
        Schema::table('diagnosticos', function (Blueprint $table) {
            $table->string('codigo', 10)->change();
        });
    }
}
