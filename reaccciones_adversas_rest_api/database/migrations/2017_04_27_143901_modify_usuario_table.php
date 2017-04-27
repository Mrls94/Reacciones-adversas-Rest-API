<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyUsuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('usuarios','role')){
            Schema::table('usuarios', function (Blueprint $table) {
                //
                $table->dropColumn('role');
            });   
        }
        
        Schema::table('usuarios', function (Blueprint $table) {
            //
            if(!Schema::hasColumn('usuarios', 'token_generation_date')){
                $table->date('token_generation_date')->after('token');   
            }
            $table->string('token',255)->nullable()->change();
            $table->string('role', 10)->default('medico');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            //
            $table->dropColumn('token_generation_date');
            $table->string('token',255)->unique()->change();
        });
    }
}
