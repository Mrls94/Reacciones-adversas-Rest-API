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
    public function create(Request $request){
        $usuario = new Usuario;
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        // En la columna password realmente se guarda el hash del password
        //$usuario->password = $usuario->hash_password($request->password);
        $password = Helper::generateStrongPassword();
        $usuario->password = $usuario->hash_password($password);
        if($request->role){
            $usuario->role = $request->role;
        }
        $answer = new \stdClass();
        try {
            $usuario->save();
            
            \Mail::send('welcome_email', ['usuario' => $usuario, 'password' => $password ], function($message) use ($usuario)
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
        
        $data = Usuario::first();
        \Mail::send('welcome', ['data' => $data ], function($message)
        {
            $message->from('us@example.com', 'Laravel');
        
            $message->to('sebasmrls94@gmail.com')->cc('bar@example.com');
        });
        
        /*
        $transport = \Swift_SmtpTransport::newInstance('smtp.google.com', 25,
            'starttls')
            ->setUsername(getenv('MAIL_USERNAME'))
            ->setPassword(getenv('MAIL_PASSWORD'));
         
        $mailer = \Swift_Mailer::newInstance($transport);
        $message = \Swift_Message::newInstance('title', 'message', 'text/html')
            ->setFrom(array('from@example.com' => 'name'))
            ->setTo(array('sebasmrls94@gmail.com' => 'name'));
         
        $mailer->send($message);
        */
        
        return 'Hello';
    }
    
    
    protected function get_usuarios_model(){
        return new Usuario();
    }
}
