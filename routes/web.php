<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth:web'], function (){
    Route::post("projects", 'App\Http\Controllers\ProjectsController@store')->name('projects.post');
    Route::get("projects", 'App\Http\Controllers\ProjectsController@index')->name('projects.index');
    Route::get("projects/create", 'App\Http\Controllers\ProjectsController@create')->name('projects.create');
    Route::get("projects/{project}", 'App\Http\Controllers\ProjectsController@show')->name('projects.show');

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

Auth::routes();