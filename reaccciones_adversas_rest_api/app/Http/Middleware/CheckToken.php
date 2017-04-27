<?php

namespace App\Http\Middleware;

use Closure;
use App\Usuario;
use App\Token;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $answer = new \stdClass();
        $now_date = date(DATE_ATOM);
        
        if($request->header('token')){
            $token = $request->header('token');
            try{
                $user = Usuario::where('token', $token)->firstOrFail();
                $token_date_str = $user->token_generation_date;
                $token_date = strtotime($token_date_str);
                $now = strtotime($now_date);
                $time_alive = round(abs($token_date - $now) / 60); //60 segundos en un minuto :O
                
                if($time_alive < Token::MINUTES_TOKEN_EXPIRATION){
                    return $next($request);   
                } else {
                    $answer->error = "token expired please log-in again";
                    return response(json_encode($answer), 400);
                }
                
            } catch (\Exception $e){
                $answer->error = "in-valid token";
                return response(json_encode($answer), 400);   
            }
        }
        $answer->error = "Un-authorized";
        return response($answer, 400);
    }
}
