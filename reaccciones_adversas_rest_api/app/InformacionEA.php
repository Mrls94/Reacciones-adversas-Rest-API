<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InformacionEA extends Model {
    
    protected $table = 'informacion_eas';
    
    public function seriedad(){
        return $this->belongsTo('App\Seriedad', 'id_seriedad');
    }
    
    public function desenlace_ea(){
        return $this->belongsTo('App\DesenlaceEA', 'id_desenlace_ea');
    }
    
    public function diagnostico(){
        return $this->belongsTo('App\Diagnostico', 'id_diagnostico');
    }
    
    public function report(){
        return $this->belongsTo('App\Report', 'id_report');
    }
}