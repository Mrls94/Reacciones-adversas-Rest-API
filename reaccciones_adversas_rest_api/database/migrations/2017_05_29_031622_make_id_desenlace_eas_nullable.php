<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeIdDesenlaceEasNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('informacion_eas', function (Blueprint $table) {
            //
            $table->integer('id_desenlace_ea')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('informacion_eas', function (Blueprint $table) {
            //
            $table->integer('id_desenlace_ea')->unsigned->change();
        });
    }
}
