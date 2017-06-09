<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DesenlaceEA extends Model {
    
    protected $table = 'desenlace_eas';
    
    protected $hidden = ['created_at', 'updated_at'];
    
    public function informacion_eas(){
        return $this->hasMany('App\InformacionEA', 'id_desenlace_ea');
    }
}