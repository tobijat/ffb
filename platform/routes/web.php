<?php

use App\Http\Controllers\AccountPageController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BestteamPageController;
use App\Http\Controllers\MyteamPageController;
use App\Http\Controllers\StartController;
use App\Http\Controllers\UserscorePageController;
use Illuminate\Support\Facades\Route;

/*
| New FFB features land here. Legacy pretty-URLs stay on the root index.php.
| Mounted under /platform/public via Apache (see repo-root .htaccess).
*/

Route::get('/', [StartController::class, 'show'])->name('start');

Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/userscore', [UserscorePageController::class, 'show'])->name('userscore');
Route::get('/myteam', [MyteamPageController::class, 'show'])->name('myteam');
Route::get('/bestteam', [BestteamPageController::class, 'show'])->name('bestteam');

Route::get('/account', [AccountPageController::class, 'show'])->name('account');
Route::post('/account', [AccountPageController::class, 'update'])->name('account.update');
Route::get('/profile', [AccountPageController::class, 'showProfile'])->name('profile');
Route::post('/profile', [AccountPageController::class, 'updateProfile'])->name('profile.update');
