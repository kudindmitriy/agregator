<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('logs', 'LogController@index');
    Route::get('/user', 'LoginController@getAuthenticatedUser');
});

Route::post('/register', 'LoginController@register');
Route::post('/login', 'LoginController@login');
Route::post('/logout', 'LoginController@logout');
