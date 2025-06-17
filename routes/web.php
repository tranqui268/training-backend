<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/', function(){
    return redirect('/login');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'showDasboard'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/users', [UserController::class, 'getAll'])->name('user');
    Route::get('/user-manager', [UserController::class, 'index']);
    Route::get('/user/{id}',[UserController::class,'getByUserId']);
});
