<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seriedad extends Model {
    
    protected $table = 'seriedads';
    
    protected $hidden = ['created_at', 'updated_at'];
    
    public function informacion_eas(){
        return $this->hasMany('App\InformacionEA', 'id_seriedad');
    }
}