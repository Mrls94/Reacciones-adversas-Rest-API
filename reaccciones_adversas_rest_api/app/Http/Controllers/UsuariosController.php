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
    public function active_user(Request $request, $id){
        $admin_user = Usuario::get_user_by_token($request->header('token'));
        $usuario = Usuario::find($id);
        $answer = new \stdClass();
        if($usuario->id == $admin_user->id){
            $answer->error = "No se puede desactivar a usted mismo";
            return response(json_encode($answer), 400);
        }
        $usuario->active = !$usuario->active;
        if($usuario->active){
            $answer->result="Activado exitosamente";   
        } else {
            $answer->result="Desactivado exitosamente";   
        }
        $usuario->save();
        
        return response(json_encode($answer, JSON_UNESCAPED_UNICODE), 200);
    }
    public function logout(Request $request){
        $answer=new \stdClass();
        if ($request->header('token')){
            $token = $request->header('token');
            $usuario = Usuario::get_user_by_token($token);
            if($usuario!=null){
                $usuario->token_generation_date=date(DATE_ATOM, mktime(0, 0, 0, 7, 1, 2000));
                try{
                    $usuario->save();
                    $answer->result="Has cerrado la session satisfactoriamente";
                    return response(json_encode($answer), 200);
                }catch(\Exception $e){
                    $answer->result=$e->getMessage();
                    return response(json_encode($answer), 400);
                }
            }
        }
    }
    public function get_users(Request $request){
        $answer= new \stdClass();
        if(is_null($request->term)){
            $usuarios=Usuario::all();
        } else {
            $usuarios = Usuario::whereRaw('LOWER(name) LIKE ? OR LOWER(email) LIKE ?',
                array('%' . $request->term . '%', '%' . $request->term . '%'))->get();
        }
        $answer->lista=$usuarios;
        return response(json_encode($answer, JSON_UNESCAPED_UNICODE), 200);
    }
    
    public function get_token_status(Request $request){
        $answer = new \stdClass();
        if ($request->header('token')){
            $token = $request->header('token');
            $usuario = Usuario::get_user_by_token($token);
            
            if($usuario != null){
                $now_date = date(DATE_ATOM);
                $token_date_str = $usuario->token_generation_date;
                $token_date = strtotime($token_date_str);
                $now = strtotime($now_date);
                $time_alive = round(abs($token_date - $now) / 60);
                
                if($time_alive < Token::MINUTES_TOKEN_EXPIRATION){
                    $answer->status = "Token valido";
                    $answer->Minutes_to_live = Token::MINUTES_TOKEN_EXPIRATION - $time_alive;
                    return response(json_encode($answer), 200);
                } else {
                    $answer->status = "Token expirado";
                    return response(json_encode($answer), 200);
                }
            } else {
                $answer->status = "Token invalido";
                return response(json_encode($answer), 200);
            }
        }
    }
    
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
                    //$url = Helper::rooturl . "register/" . $token;
                    $url = "https://ra-front-end-luisllach.c9users.io/html/changePassword.html/" . $token;
                    
                    /*\Mail::send('reset_password_email', ['usuario' => $usuario, 'url' => $url ], function($message) use ($usuario)
                    {
                        $message->from('reacciones.adversas.correo@gmail.com', 'Reacciones Adversas');
                        $message->subject('Ha cambiado su contraseña');
                        $message->to($usuario->email);//->cc('bar@example.com');
                    });*/
                    
                    $answer->result = "Ha cambiado la contraseña exitosamente";
                    return response(json_encode($answer), 200);
                    
                } catch (\Exception $e){
                    $answer->result = "Contraseña no guardada";
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
    
    public function update_user(Request $request, $id){
        $answer=new \stdClass();
        $user= Usuario::find($id);
        if($request->name) $user->name=$request->name;
        if($request->email) $user->email=$request->email;
        if($request->role) $user->role=$request->role;
        try{
            $user->save();
            $answer->result="Ha actualizado el usuario exitosamente";
            return response(json_encode($answer), 200);
        }catch(\Exception $e){
            $answer->result=$e->getMessage();
            return response(json_encode($answer), 400);
        }
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
                $message->from('reacciones.adversas.correo@gmail.com', 'MED Notify');
                $message->subject('Bienvenido a MED Notify - Sistema de Reacciones Adversas');
                $message->to($usuario->email);//->cc('bar@example.com');
            });
            
            $answer->result = "Ha creado el usuario exitosamente";
            $answer->password = $password;
            $status = 200;
        } catch (\Exception $e) {
            $answer->result = "Error";
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
            $answer->result = "No existe ese correo";
            //$answer->message = $e->getMessage();
            return response(json_encode($answer), 404);
        }
        
        if(!$user->active){
            $answer->result = "Usuario no activo";
            return response(json_encode($answer), 400);
        }
        
        if($user->verify_password($password)){
            $token = Token::generate_token();
            $user->token = $token;
            $user->token_generation_date = date(DATE_ATOM);
            $user->last_log_in_date = date(DATE_ATOM);
            $user->save();
            $answer->result = "Login exitoso";
            $answer->token = $token;
            $answer->usuario = $user;
            return response(json_encode($answer), 200);
        } else {
            $answer->result = "La contraseña ingresada es incorrecta";
            return response(json_encode($answer), 404);
        }
        
        
        
    }
    
    public function reset_password(Request $request){
        $answer = new \stdClass();
        
        if($request->email){
            $email = $request->email;
            
            try{
                $usuario = Usuario::where('email', $email)->firstOrFail();
                if(!$usuario->active){
                    $answer->result = "El usuario no es activo. Contactar a un administrador.";
                    return response(json_encode($answer), 400);
                }else{
                    $token=$usuario->token;
                    $url = Helper::rooturl . "register/" . $token;
                     \Mail::send('reset_password_email', ['usuario' => $usuario, 'url' => $url], function($message) use ($usuario)
                            {
                                $message->from('reacciones.adversas.correo@gmail.com', 'MED Notify');
                                $message->subject('Cambio de contraseña');
                                $message->to($usuario->email);
                            });
                    $answer->result = "Se ha enviado el correo exitosamente";
                    $answer->email = $url;
                    return response(json_encode($answer), 200);
                }
                
                
            } catch (\Exception $e){
                $answer->result = "Correo no encontrado";
                return response(json_encode($answer), 400);
            }
            
        } else{
            $answer->result = "Por favor ingrese un correo";
            return response(json_encode($answer), 400);
        }
    }
    
    public function hello(Request $request){
        $text = $request->text ? $request->text : "hello";
        return $text;
    }
    
    public function send_reset_password_mail(Request $request, $id){
        $answer = new \stdClass();
        $usuario = Usuario::find($id);
        $token = $usuario->token;
        $url = Helper::rooturl . "register/" . $token;
         \Mail::send('reset_password_email', ['usuario' => $usuario, 'url' => $url], function($message) use ($usuario)
                {
                    $message->from('reacciones.adversas.correo@gmail.com', 'MED Notify');
                    $message->subject('Cambio de contraseña');
                    $message->to($usuario->email);
                });
        $answer->result = "Ha enviado el correo exitosamente";
        $answer->email = $url;
        return response(json_encode($answer), 200);
    }
    
    public function refresh_token(Request $request){
        $usuario = Usuario::get_user_by_token($request->header('token'));
        $usuario->refresh_token();
        $answer = new \stdClass();
        $answer->usuario = $usuario;
        return response(json_encode($answer), 200);
    }
    
    protected function get_usuarios_model(){
        return new Usuario();
    }
}
