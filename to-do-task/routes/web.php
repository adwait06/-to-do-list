<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//use App\Http\Controllers\PostController;
use App\Http\Controllers\TaskController;



Route::resource('tasks', TaskController::class);
Route::patch('/tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
Route::get('/tasks/show', [TaskController::class, 'show'])->name('tasks.show');

Route::get('/', function () {
    return view('welcome');
});
