<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\StartController;
use Illuminate\Support\Facades\Route;

/*
| New FFB features land here. Legacy pretty-URLs stay on the root index.php.
| Mounted under /platform/public via Apache (see repo-root .htaccess).
*/

Route::get('/', [StartController::class, 'show'])->name('start');

Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])->name('logout');
