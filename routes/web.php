<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth:web'], function () {
    Route::post("projects", 'App\Http\Controllers\ProjectsController@store')->name('projects.post');

    Route::get("projects", 'App\Http\Controllers\ProjectsController@index')->name('projects.index');
    Route::get("projects/create", 'App\Http\Controllers\ProjectsController@create')->name('projects.create');
    Route::get("projects/{project}", 'App\Http\Controllers\ProjectsController@show')->name('projects.show');
    Route::get("projects/{project}/edit", 'App\Http\Controllers\ProjectsController@edit');
    Route::patch("projects/{project}", 'App\Http\Controllers\ProjectsController@update');

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::post("projects/{project}/tasks", 'App\Http\Controllers\ProjectTasksController@store');
    Route::patch("projects/{project}/tasks/{task}", 'App\Http\Controllers\ProjectTasksController@update');
});

Auth::routes();