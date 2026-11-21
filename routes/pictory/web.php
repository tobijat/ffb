<?php

use App\Http\Controllers\Pictory\PictoryAuthController;
use App\Http\Controllers\Pictory\PictoryHomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PictoryHomeController::class, 'show'])->name('pictory.home');

Route::get('/login', [PictoryAuthController::class, 'showLogin'])->name('pictory.login');
Route::post('/login', [PictoryAuthController::class, 'login'])->name('pictory.login.store');
Route::post('/logout', [PictoryAuthController::class, 'logout'])->name('pictory.logout');

Route::get('/registration', [PictoryAuthController::class, 'showRegister'])->name('pictory.register');
Route::post('/registration', [PictoryAuthController::class, 'register'])->name('pictory.register.store');
Route::get('/registration/activate', [PictoryAuthController::class, 'activate'])->name('pictory.activate');
