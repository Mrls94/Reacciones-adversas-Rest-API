<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('hello', ['uses' => 'UsuariosController@hello']);
Route::post('login', ['uses' => 'UsuariosController@login']);
Route::post('User/resetpassword', ['uses' => 'UsuariosController@reset_password']);
Route::get('register/{token}', ['uses' => 'UsuariosController@register']);
//change_password no tiene middleware para que no caduque
//el token usado en el correo al registar el usuario
Route::post('User/change_password', ['uses' => 'UsuariosController@change_user_password']);


//Endpoints con middleware -- middleware => check_token
Route::get('refresh_token', ['uses'=> 'UsuariosController@refresh_token','middleware' => 'check_token']);
Route::get('Users/{id}/reset_password/', ['uses'=> 'UsuariosController@send_reset_password_mail','middleware' => 'check_token']);
Route::post('Users/{id}', ['uses'=> 'UsuariosController@update_user','middleware' => 'check_token']);
Route::delete('login',['uses'=> 'UsuariosController@logout','middleware' => 'check_token']);
Route::delete('Users/{id}', ['uses'=> 'UsuariosController@active_user','middleware' => 'check_token']);
Route::get('Users',['uses'=> 'UsuariosController@get_users','middleware' => 'check_token']);
Route::post('User/new', ['uses' => 'UsuariosController@create', 'middleware' => 'check_token']);
Route::get('User/token_status', ['uses' => 'UsuariosController@get_token_status', 'middleware' => 'check_token']);
Route::get('Medicamentos', ['uses' => 'MedicamentosController@get_medicamentos', 'middleware' => 'check_token']);
Route::post('Medicamentos', ['uses' => 'MedicamentosController@update_medicamentos', 'middleware' => 'check_token']);
Route::get('Diagnosticos', ['uses' => 'DiagnosticosController@get_diagnosticos', 'middleware' => 'check_token']);
Route::post('Diagnosticos', ['uses' => 'DiagnosticosController@update_diagnosticos', 'middleware' => 'check_token']);
Route::get('Reports/formulario_setup', ['uses' => 'ReportsController@get_reports_formulario_setup', 'middleware' => 'check_token']);
Route::post('Reports', ['uses' => 'ReportsController@create_report', 'middleware' => 'check_token']);
Route::get('Reports', ['uses' => 'ReportsController@get_reports', 'middleware' => 'check_token']);
Route::get('Reports/medicamentos', ['uses' => 'ReportsController@get_reports_by_med', 'middleware' => 'check_token']);
Route::get('Reports/diagnosticos', ['uses' => 'ReportsController@get_reports_by_diagnostico', 'middleware' => 'check_token']);
Route::get('Reports/paciente', ['uses' => 'ReportsController@get_paciente', 'middleware' => 'check_token']);
