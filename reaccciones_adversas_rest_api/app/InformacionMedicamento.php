<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InformacionMedicamento extends Model {
    
    protected $table = 'informacion_medicamentos';
    
    public function medicamento(){
        return $this->belongsTo('App\Medicamento', 'id_medicamento');
    }
    
    public function via_de_administracion(){
        return $this->belongsTo('App\ViaDeAdministracion', 'id_via_de_administracion');
    }
    
    public function mecanismo_causa_ra(){
        return $this->belongsTo('App\MecanismoCausaRA', 'id_mecanismo_causa_ra');
    }
    
    public function report(){
        return $this->belongsTo('App\Report', 'id_report');
    }
}