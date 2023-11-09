<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('data')->group(function () {
    Route::get('/', 'App\Http\Controllers\DataController@index');
    Route::post('/get_grafic', 'App\Http\Controllers\DataController@get_grafic');   
});

Route::get('/login', 'App\Http\Controllers\AuthController@index')->name('login');
Route::post('/login', 'App\Http\Controllers\AuthController@store')->name('loginpost');
Route::get('/logout', 'App\Http\Controllers\AuthController@logout')->name('logout');

Route::get('/home', function () {
    echo "anda sudah login";
})->middleware('auth');

Route::get('/homeuser2', function () {
    echo "anda sudah login dari table user2";
})->middleware('auth:web2');
