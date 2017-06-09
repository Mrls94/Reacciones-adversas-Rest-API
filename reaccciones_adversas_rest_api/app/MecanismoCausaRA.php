<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MecanismoCausaRA extends Model {
    
    protected $table = 'mecanismo_causa_ras';
    
    protected $hidden = ['created_at', 'updated_at'];
    
    public function informacion_medicamento(){
        return $this->hasMany('App\InformacionMedicamento', 'id_mecanismo_causa_ra');
    }
}