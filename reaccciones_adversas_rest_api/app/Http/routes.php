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

//Por ahora el único endpoint sin middleware es login -- middleware => check_token para el resto
Route::post('User/new', ['uses' => 'UsuariosController@create', 'middleware' => 'check_token']);
Route::get('hello', ['uses' => 'UsuariosController@hello', 'middleware' => 'check_token']);
