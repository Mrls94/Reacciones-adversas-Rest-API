<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        
        DB::table('via_de_administracions')->insert([
            'nombre' => 'V.O'
        ]);
        
        DB::table('via_de_administracions')->insert([
            'nombre' => 'I.V'
        ]);
        
        DB::table('via_de_administracions')->insert([
            'nombre' => 'I.M'
        ]);
        
        DB::table('via_de_administracions')->insert([
            'nombre' => 'S.C'
        ]);
        
        DB::table('via_de_administracions')->insert([
            'nombre' => 'TÓPICO'
        ]);
        
        DB::table('via_de_administracions')->insert([
            'nombre' => 'SUBLINGUAL'
        ]);
        
        DB::table('via_de_administracions')->insert([
            'nombre' => 'INTRADÉRMICA'
        ]);
        
        DB::table('via_de_administracions')->insert([
            'nombre' => 'RECTAL'
        ]);
        
        DB::table('via_de_administracions')->insert([
            'nombre' => 'INTRAARTICULAR'
        ]);
        
        DB::table('via_de_administracions')->insert([
            'nombre' => 'INTRAPERITONEAL'
        ]);
        
        DB::table('via_de_administracions')->insert([
            'nombre' => 'INTRAMUSCULAR'
        ]);
        
        DB::table('via_de_administracions')->insert([
            'nombre' => 'PULMONAR'
        ]);
        
        DB::table('via_de_administracions')->insert([
            'nombre' => 'INTRATECAL'
        ]);
        
        DB::table('via_de_administracions')->insert([
            'nombre' => 'INTRAVENTRICULAR'
        ]);
        
        DB::table('via_de_administracions')->insert([
            'nombre' => 'INTRACARDIACA'
        ]);
        
        DB::table('via_de_administracions')->insert([
            'nombre' => 'INTRAOCULAR'
        ]);
        
        DB::table('via_de_administracions')->insert([
            'nombre' => 'INTRAVAGINAL'
        ]);
        
        // MECANISMO CAUSA RA
        
        DB::table('mecanismo_causa_ras')->insert([
            'nombre' => 'APARECE A LA SUSPENSIÓN DEL FÁRMACO'
        ]);
        
        DB::table('mecanismo_causa_ras')->insert([
            'nombre' => 'FALLO TERAPÉUTICO'
        ]);
        
        DB::table('mecanismo_causa_ras')->insert([
            'nombre' => 'PRODUCIDO POR USO CRÓNICO'
        ]);
        
        DB::table('mecanismo_causa_ras')->insert([
            'nombre' => 'RELACIONADO CON LA DOSIS'
        ]);
        
        //DESENLACE EA
        DB::table('desenlace_eas')->insert([
            'nombre' => 'DESCONOCIDO'
        ]);
        
        DB::table('desenlace_eas')->insert([
            'nombre' => 'FATAL'
        ]);
        
        DB::table('desenlace_eas')->insert([
            'nombre' => 'NO RECUPERADO / NO RESUELTO'
        ]);
        
        DB::table('desenlace_eas')->insert([
            'nombre' => 'RECUPERADO / RESOLVIENDO'
        ]);
        
        DB::table('desenlace_eas')->insert([
            'nombre' => 'RECUPERADO / RESUELTO CON SECUELAS'
        ]);
        
        DB::table('desenlace_eas')->insert([
            'nombre' => 'RECUPERADO / RESUELTO SIN SECUELAS'
        ]);
        
        //SERIEDAD
        DB::table('seriedads')->insert([
            'nombre' => 'AMENAZA DE VIDA'
        ]);
        
        DB::table('seriedads')->insert([
            'nombre' => 'ANOMALIDAD CONGÉNITA'
        ]);
        
        DB::table('seriedads')->insert([
            'nombre' => 'MUERTE'
        ]);
        
        DB::table('seriedads')->insert([
            'nombre' => 'PERMANENTE / CONDICIÓN MÉDICA IMPORTANTE'
        ]);
        
        DB::table('seriedads')->insert([
            'nombre' => 'PRODUJO O POROLONGÓ HOSPITALIZACIÓN'
        ]);
        
        //Admin
        $options = [
                'cost' => 12,
                ];
                
        DB:table('usuarios')->insert([
            'name' => 'Admin',
            'email' => 'admin@mednotify.co',
            'password' => password_hash('12345', PASSWORD_BCRYPT, $options)
        ]);
        
    }
}
