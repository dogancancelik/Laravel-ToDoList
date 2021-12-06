<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'TaskController@index')->name('home');
Route::get('/task-table', 'TaskController@taskTable')->name('task.table');

Route::post('/', 'TaskController@createTask')->name('create.task');
Route::post('/change-task', 'TaskController@changeTaskStatus')->name('change.task');
Route::post('/delete-task', 'TaskController@deleteTask')->name('delete.task');
Route::post('/edit-task', 'TaskController@editTask')->name('edit.task');
Route::post('/update-task', 'TaskController@updateTask')->name('update.task');
