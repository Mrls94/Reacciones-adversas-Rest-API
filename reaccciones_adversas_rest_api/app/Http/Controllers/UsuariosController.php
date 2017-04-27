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
        $usuario->token = "this token 3";
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
    
    public function hello(){
        return 'Hello';
    }
    
    
    protected function get_usuarios_model(){
        return new Usuario();
    }
}
