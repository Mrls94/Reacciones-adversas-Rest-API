<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Diagnostico;
use App\Token;
use App\Helper;

class DiagnosticosController extends Controller {
    public function get_diagnosticos (Request $request){
        $answer = new \stdClass();
        
        if(is_null($request->term)){
            $diagnosticos = Diagnostico::all();    
        } else {
            $diagnosticos = Diagnostico::whereRaw('LOWER(descripcion) LIKE ?', array('%' . $request->term . '%'))->get();
        }
        
        $answer->lista = $diagnosticos; 
        
        return response(json_encode($answer, JSON_UNESCAPED_UNICODE), 200);
    }
    
    function clean_header($header){
        $header = utf8_encode($header);
        $header = str_replace(' ', '_', $header);
        try{
         $header = iconv('UTF-8','ASCII//TRANSLIT', $header);   
        } catch (\Exception $e){
            $answer = new \stdClass();
            $answer->error = "Por favor quite las tildes de los campos";
            return false;
        }
        $header = strtolower($header);
        return $header;
    }
    
    public function update_diagnosticos(Request $request){
        $answer = new \stdClass();
        $rows = explode("\r\n", $request->getContent());
        $dirty_headers = explode(';', $rows[0]);
        
        // $new_headers = array();
        // foreach($dirty_headers as $ds){
        //     $new_headers[] = mb_detect_encoding($ds);
        // }
        
        $headers = array_map(array($this, "clean_header"), $dirty_headers);
        // $answer->error = $request->header('Content-Type');
        // return response(json_encode($answer, JSON_UNESCAPED_UNICODE), 400);
        
        if (in_array(false, $headers)){
            $answer->error = "Por favor quite las tildes de los campos";
            return response(json_encode($answer, JSON_UNESCAPED_UNICODE), 400);   
        }
        $codigo_index = array_search('codigo', $headers);
        
        $array = array();
        if ($codigo_index != false || $codigo_index == '0') {
            foreach ($rows as $index => $row){
                if ($index == 0){
                    continue;
                }
                $values = explode(';', $row);
                try {
                    $diagnostico = Diagnostico::where('codigo', $values[$codigo_index])->firstOrFail();
                }
                catch (\Exception $e){
                 $diagnostico = new diagnostico;   
                }
                foreach($values as $key => $value){
                    try{
                        $diagnostico->$headers[$key] = utf8_encode($value);    
                    } catch (\Exception $e){
                        
                    }
                    
                }
                
                $array[$index] = $diagnostico;
                $diagnostico->save();
            }
            $answer = new \stdClass();
            $answer->result = "Se actualizo correctamente";
            return response(json_encode($answer, JSON_UNESCAPED_UNICODE), 200);
            
               
        } else {
            $answer = new \stdClass();
            $answer->error = "No esta presente codigo";
            return response(json_encode($answer) ,400);
        }
    }
    
}