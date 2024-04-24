<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group([

    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});


Route::group([

    'middleware' => ['api', 'jwt.auth'],
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'user'

], function ($router) {

    Route::get('index', 'UserController@index')->middleware('auth:api', 'checkUserRole:super');
    Route::get('show/{id}', 'UserController@show')->middleware('auth:api', 'CheckUserOrRole:super');
    Route::post('store', 'UserController@store');
    Route::put('update/{id}', 'UserController@update')->middleware('auth:api', 'CheckUserOrRole:super');
    Route::delete('delete/{id}', 'UserController@delete')->middleware('auth:api', 'checkUserRole:super');
});

Route::group([

    'middleware' => ['api', 'jwt.auth'],
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'author'

], function ($router) {

    Route::get('index', 'AuthorController@index');
    Route::get('show/{id}', 'AuthorController@show');
    Route::post('store', 'AuthorController@store')->middleware('auth:api', 'checkUserRole:super');
    Route::put('update/{id}', 'AuthorController@update')->middleware('auth:api', 'checkUserRole:super');
    Route::delete('delete/{id}', 'AuthorController@delete')->middleware('auth:api', 'checkUserRole:super');
});

Route::group([

    'middleware' => ['api', 'jwt.auth'],
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'book'

], function ($router) {

    Route::get('index', 'BookController@index');
    Route::get('show/{id}', 'BookController@show');
    Route::post('store', 'BookController@store')->middleware('auth:api', 'checkUserRole:super');
    Route::put('update/{id}', 'BookController@update')->middleware('auth:api', 'checkUserRole:super');
    Route::delete('delete/{id}', 'BookController@delete')->middleware('auth:api', 'checkUserRole:super');
});