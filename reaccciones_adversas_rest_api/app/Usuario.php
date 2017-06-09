<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    // En la columna password realmente se guarda el hash del password
    protected $table = 'usuarios';
    
    protected $hidden = ['password'];
    
    protected $casts = [
        'active' => 'boolean',
    ];
    
    public function reports(){
        return $this->hasMany('App\Report', 'id_usuario');
    }
    
    public function hash_password($password){
            $options = [
                'cost' => 12,
                ];
        return password_hash($password, PASSWORD_BCRYPT, $options);
    }
    
    public function verify_password($password){
        return password_verify($password, $this->password);
    }
    
    public function change_password($new_password){
        $password_hash = $this->hash_password($new_password);
        $this->password = $password_hash;
        $this->save();
    }
    
    public function refresh_token(){
        $this->token_generation_date = date(DATE_ATOM);
        $this->save();
    }
    
    public static function get_user_by_token($token){
        try{
            $user = Usuario::where('token', $token)->firstOrFail();
            return $user;
        } catch (\Exception $e) {
            return null;
        }
    }
    
    public static function get_admin_emails(){
        $admins = Usuario::where('role', 'admin')
                            ->where('active', 1)
                            ->get();
        
        $admin_emails = array();
        foreach($admins as $admin){
            $admin_emails[] = $admin->email;
        }
        
        return $admin_emails;
    }
}
