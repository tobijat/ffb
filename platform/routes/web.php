<?php

use App\Http\Controllers\AccountPageController;
use App\Http\Controllers\Admin\AdminCenterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BestteamPageController;
use App\Http\Controllers\HelpPageController;
use App\Http\Controllers\LineupPageController;
use App\Http\Controllers\MailUnsubscribeController;
use App\Http\Controllers\MyteamPageController;
use App\Http\Controllers\ReferencePageController;
use App\Http\Controllers\RegistrationPageController;
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

Route::get('/registration', [RegistrationPageController::class, 'show'])->name('registration');
Route::post('/registration', [RegistrationPageController::class, 'store'])->name('registration.store');
Route::get('/registration/activate', [RegistrationPageController::class, 'activate'])->name('registration.activate');
Route::get('/registration/activate-email', [RegistrationPageController::class, 'activateEmail'])->name('registration.activate-email');
Route::post('/registration/password', [RegistrationPageController::class, 'requestPasswordReset'])->name('registration.password');

Route::get('/password/reset/{user}', [RegistrationPageController::class, 'showPasswordReset'])
    ->middleware('signed')
    ->name('password.reset');
Route::post('/password/reset/{user}', [RegistrationPageController::class, 'updatePasswordReset'])
    ->middleware('signed')
    ->name('password.reset.update');

Route::get('/mailservice/cancel', [MailUnsubscribeController::class, 'cancel'])->name('mailservice.cancel');

Route::get('/userscore', [UserscorePageController::class, 'show'])->name('userscore');
Route::get('/myteam', [MyteamPageController::class, 'show'])->name('myteam');
Route::get('/bestteam', [BestteamPageController::class, 'show'])->name('bestteam');
Route::get('/lineup', [LineupPageController::class, 'show'])->name('lineup');
Route::get('/help', [HelpPageController::class, 'show'])->name('help');
Route::get('/reference', [ReferencePageController::class, 'show'])->name('reference');

Route::get('/account', [AccountPageController::class, 'show'])->name('account');
Route::post('/account', [AccountPageController::class, 'update'])->name('account.update');
Route::get('/profile', [AccountPageController::class, 'showProfile'])->name('profile');
Route::post('/profile', [AccountPageController::class, 'updateProfile'])->name('profile.update');

Route::middleware('ffb.admin')->group(function () {
    Route::get('/admin', [AdminCenterController::class, 'show'])->name('admin.center');
});
