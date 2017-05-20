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

Route::post('login', ['uses' => 'UsuariosController@login']);
Route::post('User/resetpassword', ['uses' => 'UsuariosController@reset_password']);
Route::get('register/{token}', ['uses' => 'UsuariosController@register']);
//change_password no tiene middleware para que no caduque
//el token usado en el correo al registar el usuario
Route::post('User/change_password', ['uses' => 'UsuariosController@change_user_password']);

//Endpoints con middleware -- middleware => check_token
Route::post('User/new', ['uses' => 'UsuariosController@create', 'middleware' => 'check_token']);
Route::get('hello', ['uses' => 'UsuariosController@hello', 'middleware' => 'check_token']);
