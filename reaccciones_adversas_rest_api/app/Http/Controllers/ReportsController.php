<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Usuario;
use App\Token;
use App\Helper;
use App\Report;
use App\DesenlaceEA;
use App\Diagnostico;
use App\InformacionEA;
use App\InformacionMedicamento;
use App\MecanismoCausaRA;
use App\Medicamento;
use App\Seriedad;
use App\ViaDeAdministracion;
use App\ReportDiagnostico;

class ReportsController extends Controller {
    public function get_reports_formulario_setup(Request $request){
        $answer = new \stdClass();
        //$answer->medicamentos = Medicamento::all();
        //$answer->diagnosticos = Diagnostico::all();
        $answer->via_de_administracions = ViaDeAdministracion::all();
        $answer->mecanismo_causa_ras = MecanismoCausaRA::all();
        $answer->desenlace_eas = DesenlaceEA::all();
        $answer->seriedads = Seriedad::all();
        
        return response(json_encode($answer, JSON_UNESCAPED_UNICODE), 200);
    }
    
    public function get_paciente(Request $request){
        $answer= new \stdClass();
        if($request->header('token')){
            $tipo_id_paciente=$request->tipo_id_paciente;
            $num_id_paciente=$request->num_id_paciente;
            $path="https://api.myjson.com/bins/q408d";
            $json = json_decode(file_get_contents($path), true);
            foreach($json as $item) {
                if($item["tipo_id_paciente"] == $tipo_id_paciente && $item["num_id_paciente"] == $num_id_paciente) {
                    $answer->result=$item;
                    return response(json_encode($answer,JSON_UNESCAPED_UNICODE),200);
                }
            }
            $answer->result="No existe el paciente";
            return response(json_encode($answer,JSON_UNESCAPED_UNICODE),400);
        }else{
            $answer->result="error";
            return response(json_encode($answer,JSON_UNESCAPED_UNICODE),400);
        }
    }
    
    public function create_report(Request $request){
        $answer = new \stdClass();
        $error = array();
        if ($request->header('token')){
            $token = $request->header('token');
            $usuario = Usuario::get_user_by_token($token);
            if ($usuario!=null){
                $error = self::validate_entries($request);
                if (count($error) > 0){
                    $answer->errors = $error;
                    return response(json_encode($answer, JSON_UNESCAPED_UNICODE), 402);
                } else {
                    
                    $report = new Report;
                    $report->id_usuario = $usuario->id;
                    $report->num_id_paciente = $request->num_id_paciente;
                    $report->tipo_id_paciente = $request->tipo_id_paciente;
                    if ($request->lugar) $report->lugar = $request->lugar;
                    if ($request->cama) $report->cama = $request->cama;
                    if ($request->mas_informacion_diagnosticos) $report->mas_informacion_diagnosticos = $request->mas_informacion_diagnosticos;
                    if ($request->efecto_adverso) $report->efecto_adverso = $request->efecto_adverso;
                    $report->save();
                    
                    $informacion_ea = new InformacionEA;
                    $informacion_ea->fecha_inicio = $request->fecha_inicio_efecto_adverso;
                    $informacion_ea->id_diagnostico = $request->id_diagnostico;
                    if ($request->descripcion) $informacion_ea->descripcion = $request->descripcion;
                    if ($request->id_desenlace_ea) $informacion_ea->id_desenlace_ea = $request->id_desenlace_eas;
                    $informacion_ea->id_seriedad = $request->id_seriedad;
                    $informacion_ea->despues_de_administrar_farmaco = $request->despues_de_administrar_farmaco;
                    if(!is_null($request->desaparecio_a_suspension_farmaco)) $informacion_ea->desaparecio_a_suspension_farmaco = $request->desaparecio_a_suspension_farmaco;
                    $informacion_ea->misma_reaccion_a_farmaco = $request->misma_reaccion_a_farmaco;
                    if ($request->ampliar_informacion) $informacion_ea->ampliar_informacion = $request->ampliar_informacion;
                    if ($request->otros_factores) $informacion_ea->otros_factores = $request->otros_factores;
                    $informacion_ea->id_report = $report->id;
                    
                    try{
                        $informacion_ea->save();
                    } catch(\Exception $e){
                        $report->delete();
                        $answer->error = $e->getMessage();
                        return response(json_encode($answer, JSON_UNESCAPED_UNICODE), 402);
                    }
                    
                    $medicamentos = $request->medicamentos;
                    
                    foreach ($medicamentos as $medicamento){
                        $informacion_medicamento = new InformacionMedicamento;
                        $informacion_medicamento->id_medicamento = $medicamento["id_medicamento"];
                        $informacion_medicamento->dosis = $medicamento["dosis"];
                        $informacion_medicamento->unidad_de_medida = $medicamento["unidad_medida"];
                        $informacion_medicamento->id_via_de_administracion = $medicamento["id_via_de_administracion"];
                        $informacion_medicamento->fecha_inicio_medicamento = $medicamento["fecha_inicio_medicamento"];
                        if(array_key_exists("fecha_finalizacion_medicamento", $medicamento)){
                            $informacion_medicamento->fecha_finalizacion_medicamento = $medicamento["fecha_finalizacion_medicamento"];
                        }
                        if (array_key_exists("id_mecanismo_causa_ra", $medicamento)){
                            $informacion_medicamento->id_mecanismo_causa_ra = $medicamento["id_mecanismo_causa_ra"];   
                        }
                        $informacion_medicamento->id_report = $report->id;
                        
                        try{
                            $informacion_medicamento->save();
                        } catch (\Exception $e){
                            
                            $report->informacion_medicamentos()->delete();
                            $report->informacion_eas()->delete();
                            $report->delete();
                            
                            $answer->error = $e->getMessage();
                            return response(json_encode($answer, JSON_UNESCAPED_UNICODE), 402);
                        }
                    }
                    
                    if($request->otros_diagnosticos){
                        $otros_diagnosticos = $request->otros_diagnosticos;
                        foreach($otros_diagnosticos as $id_diagnostico){
                            $rd = new ReportDiagnostico;
                            $rd->id_report = $report->id;
                            $rd->id_diagnostico = $id_diagnostico;
                            
                            try{ $rd->save(); }
                            catch (\Exception $e){
                            $report->otros_diagnosticos->delete();
                            $report->informacion_medicamentos->delete();
                            $report->informacion_eas->delete();
                            $report->delete();
                            
                            $answer->error = $e->getMessage();
                            return response(json_encode($answer, JSON_UNESCAPED_UNICODE), 402);
                            }
                        }
                    }
                    
                    $answer->result = "Se ha creado el reporte exitosamente";
                    self::send_reports_email($report);
                    return response(json_encode($answer, JSON_UNESCAPED_UNICODE), 200);
                }
            }
        }
    }
    
    public static function validate_entries($request){
        $error = array();
        
        if (!$request->num_id_paciente)  $error[] = "Numero de identificación del paciente no fue ingresada";
        if (!$request->tipo_id_paciente)  $error[] = "Tipo de identificación del paciente no fue ingresada";
        if ($request->medicamentos){
            $medicamentos = $request->medicamentos;
            
            foreach ($medicamentos as $key => $med){
                if (!array_key_exists("id_medicamento", $med))  $error[] = "No ingreso medicamento";
                if (!array_key_exists("dosis", $med))  $error[] = "No ingreso dosis del medicamento #" . $key;
                if (!array_key_exists("unidad_medida" ,$med))  $error[] = "No ingreso la unidad de medida del medicamento #" . $key;
                if (!array_key_exists("id_via_de_administracion", $med))  $error[] = "No ingreso via de administración del medicamento #" . $key;
                if (!array_key_exists("fecha_inicio_medicamento", $med))  $error[] = "No ingreso fecha de inicio del medicamento #" . $key;
            }
        } else {
            $error[] = "No ingreso lista de medicamentos";
        }
        
        if (!$request->fecha_inicio_efecto_adverso)  $error[] = "Fecha de inicio del efecto adverso no fue ingresada";
        //if (!$request->id_diagnostico)  $error[] = "Efecto adverso no fue ingresado";
        if (!$request->id_seriedad)  $error[] = "Seriedad no fue ingresada";
        if(is_null($request->despues_de_administrar_farmaco)) $error[] = "El efecto adverso comenzo despues de administar el farmaco?";
        if(is_null($request->misma_reaccion_a_farmaco)) $error[] = "Paciente ha tendio reacciones similares al medicamento en el pasado?";
        
        return $error;
    }
    
    public function get_reports(Request $request){
        //$reports = Report::all();
        
        $to_date = is_null($request->to_date) ? date(DATE_ATOM) : $request->to_date . " 23:59:00";
        $seconds_in_one_month = 60 * 60 * 24 * 30;
        $from_date = is_null($request->from_date) ? date(DATE_ATOM, (time() - $seconds_in_one_month)) : $request->from_date;
        
        $answer = new \stdClass();
        $answer->error = "from_date - " . $from_date . " | to_date - " . $to_date;
        //return response(json_encode($answer), 400);
        
        $reports = Report::whereBetween('created_at', [$from_date, $to_date])->get();
        
        $report_ids;
        
        if(!is_null($request->id_medicamento)){
            $reports = Report::join('informacion_medicamentos', 'reports.id', '=', 'informacion_medicamentos.id_report')
            ->where('informacion_medicamentos.id_medicamento', '=', $request->id_medicamento)
            ->whereBetween('reports.created_at', [$from_date, $to_date])->select('reports.id')->get();
            
            $report_ids = array();
            foreach ($reports as $r) $report_ids[] = $r->id;
            
            $reports = Report::whereIn('id', $report_ids)->get();
        }
        
        
        $answer = new \stdClass();
        // $answer->new_reports = $new_reports;
        //$answer->reports = $reports;
        //$answer->report_ids = $report_ids;
        // $answer->to_date = $to_date;
        // $answer->from_date = $from_date;
        
       // return response(json_encode($answer, JSON_UNESCAPED_UNICODE), 200);
        
        $serialized_reports = self::serialize_reports($reports);
        return response(json_encode($serialized_reports, JSON_UNESCAPED_UNICODE), 200);
    }
    
    public function serialize_reports($reports){
        $serialized_reports = array();
        
        foreach($reports as $report){
            $new_report = new \stdClass();
            
            $new_report->id = $report->id;
            $new_report->usuario = $report->usuario;
            $new_report->num_id_paciente = $report->num_id_paciente;
            $new_report->tipo_id_paciente = $report->tipo_id_paciente;
            $new_report->created_at = $report->created_at;
            
            $new_report->informacion_eas = array();
            foreach($report->informacion_eas as $ieas){
                $new_ieas = new \stdClass();
                
                $new_ieas = $ieas;
                $new_ieas->diagnostico = $ieas->diagnostico;
                $new_ieas->desenlace_ea = $ieas->desenlace_ea;
                $new_ieas->seriedad = $ieas->seriedad;
                
                $new_report->informacion_eas[] = $new_ieas;
            }
            
            $new_report->informacion_medicamentos = array();
            foreach($report->informacion_medicamentos as $imed){
                $new_imed = new \stdClass();
                
                $new_imed = $imed;
                $new_imed->medicamento = $imed->medicamento;
                $new_imed->via_de_administracion = $imed->via_de_administracion;
                $new_imed->mecanismo_causa_ra = $imed->mecanismo_causa_ra;
                
                $new_report->informacion_medicamentos[] = $new_imed;
            }
            
            $serialized_reports[] = $new_report;
        }
        
        return $serialized_reports;
    }
    
    public function get_reports_by_med(Request $request){
        
        $to_date = is_null($request->to_date) ? date(DATE_ATOM) : $request->to_date . " 23:59:00";
        $seconds_in_one_month = 60 * 60 * 24 * 30;
        $from_date = is_null($request->from_date) ? date(DATE_ATOM, (time() - $seconds_in_one_month)) : $request->from_date;
        
        $reports = Report::whereBetween('created_at', [$from_date, $to_date])->get();
        $frequencies = new \stdClass();
        
        foreach($reports as $r){
            foreach($r->informacion_medicamentos as $med){
                $id = $med->id_medicamento;
                if(property_exists($frequencies, $id)){
                    $frequencies->$id->frequency = $frequencies->$id->frequency + 1;
                } else {
                    $frequencies->$id = new \stdClass();
                    $frequencies->$id->frequency = 1;
                    $frequencies->$id->medicamento = $med->medicamento;
                }
            }
        }
        return response(json_encode($frequencies, JSON_UNESCAPED_UNICODE), 200);
    }
    
    public function get_reports_by_diagnostico(Request $request){
        
        $to_date = is_null($request->to_date) ? date(DATE_ATOM) : $request->to_date . " 23:59:00";
        $seconds_in_one_month = 60 * 60 * 24 * 30;
        $from_date = is_null($request->from_date) ? date(DATE_ATOM, (time() - $seconds_in_one_month)) : $request->from_date;
        
        $reports = Report::whereBetween('created_at', [$from_date, $to_date])->get();
        $frequencies = new \stdClass();
        
        foreach($reports as $r){
            foreach($r->informacion_eas as $ea){
                $id = $ea->id_diagnostico;
                if(property_exists($frequencies, $id)){
                    $frequencies->$id->frequency = $frequencies->$id->frequency + 1;
                } else {
                    $frequencies->$id = new \stdClass();
                    $frequencies->$id->frequency = 1;
                    $frequencies->$id->diagnostico = $ea->diagnostico;
                }
            }
            foreach($r->otros_diagnosticos as $diagnostico){
                $id = $diagnostico->id;
                if(property_exists($frequencies, $id)){
                    $frequencies->$id->frequency = $frequencies->$id->frequency + 1;
                } else {
                    $frequencies->$id = new \stdClass();
                    $frequencies->$id->frequency = 1;
                    $frequencies->$id->diagnostico = $diagnostico;
                }
            }
            
        }
        return response(json_encode($frequencies, JSON_UNESCAPED_UNICODE), 200);
    }
    
    public function send_reports_email($report){
        $admin_emails = Usuario::get_admin_emails();
        
        \Mail::send('new_report_email', ['report' => $report], function($message) use ($admin_emails)
                {
                    $message->from('reacciones.adversas.correo@gmail.com', 'MED Notify');
                    $message->subject('Se ha generado un nuevo reporte');
                    $message->to($admin_emails);
                });
    }
}