<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medicamento extends Model {
    
    protected $table = 'medicamentos';
    
    protected $hidden = ['created_at', 'updated_at'];
    
    public function informacion_medicamento(){
        return $this->hasMany('App\InformacionMedicamento', 'id_medicamento');
    }
}