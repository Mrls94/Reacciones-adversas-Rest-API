<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Usuario;
use App\Token;
use App\Helper;

class UsuariosController extends Controller
{
    //
    public function change_user_password(Request $request){
        $answer = new \stdClass();
        if ($request->header('token')){
            $token = $request->header('token');
            $usuario = Usuario::get_user_by_token($token);
            
            if ($usuario != null){
                $password = $request->password;
                $usuario->password = $usuario->hash_password($password);
                try{
                    $usuario->save();
                    $token = $usuario->token;
                    $url = Helper::rooturl . "register/" . $token;
                    
                    \Mail::send('reset_password_email', ['usuario' => $usuario, 'url' => $url ], function($message) use ($usuario)
                    {
                        $message->from('reacciones.adversas.correo@gmail.com', 'Reacciones Adversas');
                        $message->subject('Ha cambiado su contraseña');
                        $message->to($usuario->email);//->cc('bar@example.com');
                    });
                    
                    $answer->result = "Success";
                    return response(json_encode($answer), 200);
                    
                } catch (\Exception $e){
                    $answer->result = "No se pudo salvar";
                    $answer->message = $e->getMessage();
                    return response(json_encode($answer), 404);
                }
            }
            
            $answer->result = "Usuario no encontrado";
            return response(json_encode($answer), 404);
        } else {
            $answer->result = "No token";
            return response(json_encode($answer), 400);
        }
    }
    
    public function register($token){
        $usuario = Usuario::get_user_by_token($token);
        
        return view('register_user', ['usuario' => $usuario]);
    }
    
    public function create(Request $request){
        $usuario = new Usuario;
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        // En la columna password realmente se guarda el hash del password
        //$usuario->password = $usuario->hash_password($request->password);
        $password = Helper::generateStrongPassword();
        $usuario->password = $usuario->hash_password($password);
        $token = Token::generate_token();
        $usuario->token = $token;
        $usuario->token_generation_date = date(DATE_ATOM);
        if($request->role){
            $usuario->role = $request->role;
        }
        $answer = new \stdClass();
        try {
            $usuario->save();
            $registerurl = Helper::rooturl . "register/" . $token;
            \Mail::send('welcome_email', ['usuario' => $usuario, 'password' => $password, 'registerurl' => $registerurl ], function($message) use ($usuario)
            {
                $message->from('reacciones.adversas.correo@gmail.com', 'Reacciones Adversas');
                $message->subject('Bienvenido a reacciones adversas');
                $message->to($usuario->email);//->cc('bar@example.com');
            });
            
            $answer->result = "Success";
            $answer->password = $password;
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
            //$answer->message = $e->getMessage();
            return response(json_encode($answer), 404);
        }
        
        
        if($user->verify_password($password)){
            $token = Token::generate_token();
            $user->token = $token;
            $user->token_generation_date = date(DATE_ATOM);
            $user->last_log_in_date = date(DATE_ATOM);
            $user->save();
            $answer->result = "Success";
            $answer->token = $token;
            $answer->usario = $user;
            return response(json_encode($answer), 200);
        } else {
            $answer->result = "Wrong Password";
            return response(json_encode($answer), 404);
        }
        
        
        
    }
    
    public function reset_password(Request $request){
        $answer = new \stdClass();
        
        if($request->email){
            $email = $request->email;
            
            try{
                $usuario = Usuario::where('email', $email)->firstOrFail();
                $password = Helper::generateStrongPassword();
                
                $usuario->change_password($password);
                
                $usuario->password_change_date = date(DATE_ATOM);
                $usuario->save();
                
                \Mail::send('reset_password_email', ['usuario' => $usuario, 'password' => $password ], function($message) use ($usuario)
                {
                    $message->from('reacciones.adversas.correo@gmail.com', 'Reacciones Adversas');
                    $message->subject('Ha cambiado su contraseña');
                    $message->to($usuario->email);//->cc('bar@example.com');
                });
                
                $answer->result = "Success";
                return response(json_encode($answer), 200);
                
            } catch (\Exception $e){
                $answer->result = "No such email";
                return response(json_encode($answer), 400);
            }
            
        } else{
            $answer->result = "No email sent";
            return response(json_encode($answer), 400);
        }
    }
    
    public function hello(){
        return 'Hello';
    }
    
    
    protected function get_usuarios_model(){
        return new Usuario();
    }
}
