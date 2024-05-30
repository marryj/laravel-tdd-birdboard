<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post("projects", 'App\Http\Controllers\ProjectsController@store')->name('projects.post')->middleware('auth');

Route::get("projects", 'App\Http\Controllers\ProjectsController@index')->name('projects.index');
Route::get("projects/{project}", 'App\Http\Controllers\ProjectsController@show')->name('projects.show');


//\App\Models\Project::factory()->count(5)->create();
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
