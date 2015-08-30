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

Route::get('/edit/payment/{id}', 'EditController@paymentIndex');
Route::post('/edit/payment/{id}', 'EditController@paymentPostIndex');

Route::get('/edit/income/{id}', 'EditController@incomeIndex');
Route::post('/edit/income/{id}', 'EditController@incomePostIndex');

Route::controllers([
   'password' => 'Auth\PasswordController',
]);