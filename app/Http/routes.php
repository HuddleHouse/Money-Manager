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
Route::get('/home', 'HomeController@index');

Route::get('/options', 'SettingsController@index');
Route::post('/options', 'SettingsController@postIndex');

// Authentication routes...
Route::get('/login', 'Auth\AuthController@getLogin');
Route::post('/login', 'Auth\AuthController@postLogin');
Route::get('/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('/register', 'Auth\AuthController@getRegister');
Route::post('/register', 'Auth\AuthController@postRegister');

Route::get('/edit/{id}', 'EditController@index');
Route::post('/edit/{id}', 'EditController@postIndex');
Route::get('/edit/{id}/delete', 'EditController@deleteTransaction');

Route::get('/edit/payment/{type}/{id}', 'EditController@paymentIndex');
Route::post('/edit/payment/{type}/{id}', 'EditController@paymentPostIndex');
Route::get('/edit/payment/{type}/{id}/delete', 'EditController@paymentDelete');

Route::get('/edit/income/{id}', 'EditController@incomeIndex');
Route::post('/edit/income/{id}', 'EditController@incomePostIndex');
Route::get('/edit/income/{id}/delete', 'EditController@incomeDelete');

Route::get('/edit/bank/{id}', 'EditController@bankIndex');
Route::post('/edit/bank/{id}', 'EditController@bankPostIndex');
Route::get('/edit/bank/{id}/delete', 'EditController@bankDelete');

Route::get('/edit/cc/{id}', 'EditController@ccIndex');
Route::post('/edit/cc/{id}', 'EditController@ccPostIndex');
Route::get('/edit/cc/{id}/delete', 'EditController@ccDelete');

Route::post('/view', 'PastController@index');

Route::get('/view-all', 'ViewAllController@index');

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

