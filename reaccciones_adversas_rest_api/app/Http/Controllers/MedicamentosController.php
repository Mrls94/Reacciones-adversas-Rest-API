<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Medicamento;
use App\Token;
use App\Helper;

class MedicamentosController extends Controller {
    
    public function get_medicamentos(Request $request){
        $answer = new \stdClass();
        if(is_null($request->term)){
            $medicamentos = Medicamento::all();
        } else {
            $medicamentos = Medicamento::whereRaw('LOWER(medNombreGenerico) LIKE ? OR LOWER(medFormaFarmaceutica) LIKE ?',
                array('%' . $request->term . '%', '%' . $request->term . '%'))->get();
        }
        
        $answer->lista = $medicamentos;
        return response(json_encode($answer, JSON_UNESCAPED_UNICODE), 200);
    }
    
    public function update_medicamentos(Request $request){
        $rows = explode("\r\n", $request->getContent());
        $headers = explode(';', $rows[0]);
        $medCodigo_index = array_search('medCodigo', $headers);
        $array = array();
        if ($medCodigo_index != false || $medCodigo_index == '0') {
            foreach ($rows as $index => $row){
                if ($index == 0){
                    continue;
                }
                $values = explode(';', $row);
                try {
                    $medicamento = Medicamento::where('medCodigo', $values[$medCodigo_index])->firstOrFail();
                }
                catch (\Exception $e){
                 $medicamento = new Medicamento;   
                }
                foreach($values as $key => $value){
                    try{
                        $medicamento->$headers[$key] = $value;    
                    } catch (\Exception $e){
                        
                    }
                    
                }
                
                $array[$index] = $medicamento;
                $medicamento->save();
            }
            return response(json_encode($array, JSON_UNESCAPED_UNICODE), 200);
            
               
        } else {
            $answer = new \stdClass();
            $answer->error = "No esta presente medCodigo";
            return response(json_encode($answer) ,400);
        }
    }
    
}