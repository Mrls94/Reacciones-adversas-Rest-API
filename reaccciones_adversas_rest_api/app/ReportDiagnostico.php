<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportDiagnostico extends Model {
    
    protected $table = 'report_diagnosticos';
    
    protected $hidden = ['created_at', 'updated_at'];
    
    public function report(){
        return $this->belongsTo('App\Report', 'id_report');
    }
    
    public function diagnostico(){
        return $this->belongsTo('App\Diagnostico', 'id_diagnostico');
    }
}