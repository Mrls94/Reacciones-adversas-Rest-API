<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Diagnostico extends Model {
    
    protected $table = 'diagnosticos';
    
    protected $hidden = ['created_at', 'updated_at'];
    
    public function informacion_eas(){
        return $this->hasMany('App\InformacionEA', 'id_diagnostico');
    }
}