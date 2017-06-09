<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameColumnMedUnidadMedia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medicamentos', function (Blueprint $table) {
            $table->renameColumn('medUnidadMedia', 'medUnidadMedida');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medicamentos', function (Blueprint $table) {
            $table->renameColumn('medUnidadMedida', 'medUnidadMedia');
        });
    }
}
