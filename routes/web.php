<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('data')->group(function () {
    Route::get('/', 'App\Http\Controllers\DataController@index');
    Route::post('/get_grafic', 'App\Http\Controllers\DataController@get_grafic');   
});
