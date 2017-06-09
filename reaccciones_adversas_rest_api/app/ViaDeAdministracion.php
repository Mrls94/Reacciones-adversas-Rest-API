<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ViaDeAdministracion extends Model {
    
    protected $table = 'via_de_administracions';
    
    protected $hidden = ['created_at', 'updated_at'];
    
    public function informacion_medicamento(){
        return $this->hasMany('App\InformacionMedicamento', 'id_via_de_administracion');
    }
}