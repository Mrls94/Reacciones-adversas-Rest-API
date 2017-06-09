<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model {
    
    protected $table = 'reports';
    
    public function usuario(){
        return $this->belongsTo('App\Usuario', 'id_usuario');
    }
    
    public function informacion_eas(){
        return $this->hasMany('App\InformacionEA', 'id_report');
    }
    
    public function informacion_medicamentos(){
        return $this->hasMany('App\InformacionMedicamento', 'id_report');
    }
    
    public function otros_diagnosticos(){
        return $this->belongsToMany('App\Diagnostico', 'report_diagnosticos', 'id_report', 'id_diagnostico');
    }
}