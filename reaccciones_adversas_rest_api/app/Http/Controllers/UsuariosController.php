<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Usuario;

class UsuariosController extends Controller
{
    //
    public function create(Request $request){
        $usuarios_model = self::get_usuarios_model();
        
        $usuario = new Usuario;
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->password = $usuario->hash_password($request->password);
        if($request->role){
            $usuario->role = $request->role;
        }
        $answer = new \stdClass();
        try {
            $usuario->save();   
            $answer->result = "Success";
            $status = 200;
        } catch (\Exception $e) {
            $answer->result = "Failed";
            $answer->message = $e->getMessage();
            $status = 400;
        }
        return response(json_encode($answer), $status);
        return json_encode($usuario);
        
    }
    
    public function login(Request $request){
        $usuarios_model = self::get_usuarios_model();
        $email = $request->email;
        $password = $request->password;
    }
    
    public function hello(){
        return 'Hello';
    }
    
    
    protected function get_usuarios_model(){
        return new Usuario();
    }
}
