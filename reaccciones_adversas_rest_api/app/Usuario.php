<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    // En la columna password realmente se guarda el hash del password
    protected $table = 'usuarios';
    
    public function hash_password($password){
            $options = [
                'cost' => 12,
                ];
        return password_hash($password, PASSWORD_BCRYPT, $options);
    }
    
    public function verify_password($password){
        return password_verify($password, $this->password);
    }
}
