<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post("projects", 'App\Http\Controllers\ProjectsController@store')->name('projects.post');

Route::get("projects", 'App\Http\Controllers\ProjectsController@index')->name('projects.index');
