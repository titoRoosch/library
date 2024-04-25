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


Route::prefix('auth')->middleware('api')->namespace('App\Http\Controllers')->group(function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

Route::prefix('user')->middleware('api')->namespace('App\Http\Controllers')->group(function () {
    Route::get('index', 'UserController@index')->middleware('jwt.auth', 'checkUserRole:super');
    Route::get('show/{id}', 'UserController@show')->middleware('jwt.auth', 'CheckUserOrRole:super');
    Route::post('store', 'UserController@store');
    Route::put('update/{id}', 'UserController@update')->middleware('jwt.auth', 'CheckUserOrRole:super');
    Route::delete('delete/{id}', 'UserController@delete')->middleware('jwt.auth', 'checkUserRole:super');
});

Route::prefix('author')->middleware(['api', 'jwt.auth'])->namespace('App\Http\Controllers')->group(function () {
    Route::get('index', 'AuthorController@index');
    Route::get('show/{id}', 'AuthorController@show');
    Route::post('store', 'AuthorController@store')->middleware('checkUserRole:super');
    Route::put('update/{id}', 'AuthorController@update')->middleware('checkUserRole:super');
    Route::delete('delete/{id}', 'AuthorController@delete')->middleware('checkUserRole:super');
});

Route::prefix('book')->middleware(['api', 'jwt.auth'])->namespace('App\Http\Controllers')->group(function () {
    Route::get('index', 'BookController@index');
    Route::get('show/{id}', 'BookController@show');
    Route::post('store', 'BookController@store')->middleware('checkUserRole:super');
    Route::put('update/{id}', 'BookController@update')->middleware('checkUserRole:super');
    Route::delete('delete/{id}', 'BookController@delete')->middleware('checkUserRole:super');
});


Route::prefix('rent')->middleware(['api', 'jwt.auth'])->namespace('App\Http\Controllers')->group(function () {
    Route::get('index', 'RentController@index')->middleware('checkUserRole:super');
    Route::get('show/{id}', 'RentController@show')->middleware('jwt.auth');
    Route::post('store', 'RentController@store')->middleware('jwt.auth');
    Route::put('update/{id}', 'RentController@update')->middleware('jwt.auth', 'CheckUserOrRole:super');
    Route::delete('delete/{id}', 'RentController@delete')->middleware('jwt.auth', 'CheckUserOrRole:super');
});