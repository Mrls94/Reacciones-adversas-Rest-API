<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Usuario;
use App\Token;

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
        //return json_encode($usuario);
        
    }
    
    public function login(Request $request){
        $email = $request->email;
        $password = $request->password;
        $answer = new \stdClass();
        try{
            $user = Usuario::where('email', $email)->firstOrFail();   
        } catch (\Exception $e){
            $answer->result = "No such email";
            $answer->message = $e->getMessage();
            return response(json_encode($answer), 404);
        }
        
        
        if($user->verify_password($password)){
            $token = Token::generate_token();
            $user->token = $token;
            $user->token_generation_date = date(DATE_ATOM);
            $user->save();
            $answer->result = "Success";
            $answer->token = $token;
            return response(json_encode($answer), 200);
        } else {
            $answer->result = "Wrong Password";
            return response(json_encode($answer), 404);
        }
        
        
        
    }
    
    public function hello(){
        return 'Hello';
    }
    
    
    protected function get_usuarios_model(){
        return new Usuario();
    }
}
