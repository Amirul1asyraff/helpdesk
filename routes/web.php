<?php

use App\Models\Project;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SlaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ResponseController;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//route for user


// Route::get('/User/{user}', [UserController::class, 'show'])->name('user.show');
// Route::get('/User/create', [UserController::class, 'create'])->name('user.create');
// Route::get('/user/{user}/edit', [UserController::class,'edit'])->name('user.edit');
// Route::post('/user', [UserController::class, 'store'])->name('user.store');
// Route::put('/user/{user}', [UserController::class, 'update'])->name('user.update');
// Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');
Route::resource('users', UserController::class);
Route::resource('tickets', TicketController::class);
Route::resource('projects', ProjectController::class);
Route::resource('slas', SlaController::class);
Route::resource('responses', ResponseController::class);
