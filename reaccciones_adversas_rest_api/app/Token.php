<?php

namespace App;

use App\Usuario;

class Token {
    
    public static function generate_token(){
        $flag = true;
        while($flag){
            $token = md5(uniqid(rand(), true));
            
            try{
                $user = Usuario::where('token', $token)->firstOrFail();
            } catch (\Exception $e){
                $flag = false;
                return $token;
            }
        }
    }
    
    public static function is_admin_token($token){
        try{
            $user = Usuario::where('token', $token)->firstOrFail();
            return $user->role == 'admin';
        } catch (\Exception $e) {
            return false;
        }
    }
}