<?php

use App\Http\Controllers\AccountPageController;
use App\Http\Controllers\Admin\AdminCenterController;
use App\Http\Controllers\Admin\AdminLeagueController;
use App\Http\Controllers\Admin\AdminMatchController;
use App\Http\Controllers\Admin\AdminMatchroundController;
use App\Http\Controllers\Admin\AdminNewsController;
use App\Http\Controllers\Admin\AdminPlayerController;
use App\Http\Controllers\Admin\AdminSquadController;
use App\Http\Controllers\Admin\AdminTeamController;
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
    Route::post('/admin/games/{game}/select', [AdminCenterController::class, 'selectGame'])->name('admin.games.select');

    Route::get('/admin/leagues', [AdminLeagueController::class, 'show'])->name('admin.leagues');
    Route::post('/admin/leagues', [AdminLeagueController::class, 'store'])->name('admin.leagues.store');
    Route::get('/admin/leagues/{game}/edit', [AdminLeagueController::class, 'edit'])->name('admin.leagues.edit');
    Route::put('/admin/leagues/{game}', [AdminLeagueController::class, 'update'])->name('admin.leagues.update');
    Route::delete('/admin/leagues/{game}', [AdminLeagueController::class, 'destroy'])->name('admin.leagues.destroy');

    Route::get('/admin/matchrounds', [AdminMatchroundController::class, 'show'])->name('admin.matchrounds');
    Route::post('/admin/matchrounds', [AdminMatchroundController::class, 'store'])->name('admin.matchrounds.store');
    Route::get('/admin/matchrounds/{matchround}/edit', [AdminMatchroundController::class, 'edit'])->name('admin.matchrounds.edit');
    Route::put('/admin/matchrounds/{matchround}', [AdminMatchroundController::class, 'update'])->name('admin.matchrounds.update');
    Route::delete('/admin/matchrounds/{matchround}', [AdminMatchroundController::class, 'destroy'])->name('admin.matchrounds.destroy');

    Route::get('/admin/matches', [AdminMatchController::class, 'show'])->name('admin.matches');
    Route::post('/admin/matches', [AdminMatchController::class, 'store'])->name('admin.matches.store');
    Route::get('/admin/matches/{match}/edit', [AdminMatchController::class, 'edit'])->name('admin.matches.edit');
    Route::put('/admin/matches/{match}', [AdminMatchController::class, 'update'])->name('admin.matches.update');
    Route::delete('/admin/matches/{match}', [AdminMatchController::class, 'destroy'])->name('admin.matches.destroy');

    Route::get('/admin/teams', [AdminTeamController::class, 'show'])->name('admin.teams');
    Route::post('/admin/teams', [AdminTeamController::class, 'store'])->name('admin.teams.store');
    Route::get('/admin/teams/{team}/edit', [AdminTeamController::class, 'edit'])->name('admin.teams.edit');
    Route::put('/admin/teams/{team}', [AdminTeamController::class, 'update'])->name('admin.teams.update');
    Route::delete('/admin/teams/{team}', [AdminTeamController::class, 'destroy'])->name('admin.teams.destroy');

    Route::get('/admin/players', [AdminPlayerController::class, 'show'])->name('admin.players');
    Route::get('/admin/players/search', [AdminPlayerController::class, 'search'])->name('admin.players.search');
    Route::post('/admin/players', [AdminPlayerController::class, 'store'])->name('admin.players.store');
    Route::post('/admin/players/batch-update', [AdminPlayerController::class, 'batchUpdate'])->name('admin.players.batchUpdate');
    Route::get('/admin/players/{player}/edit', [AdminPlayerController::class, 'edit'])->name('admin.players.edit');
    Route::put('/admin/players/{player}', [AdminPlayerController::class, 'update'])->name('admin.players.update');
    Route::delete('/admin/players/{player}', [AdminPlayerController::class, 'destroy'])->name('admin.players.destroy');

    Route::get('/admin/squad', [AdminSquadController::class, 'show'])->name('admin.squad');
    Route::post('/admin/squad', [AdminSquadController::class, 'store'])->name('admin.squad.store');
    Route::post('/admin/squad/batch-update', [AdminSquadController::class, 'batchUpdate'])->name('admin.squad.batchUpdate');
    Route::put('/admin/squad/{playerteam}', [AdminSquadController::class, 'update'])->name('admin.squad.update');
    Route::delete('/admin/squad/{playerteam}', [AdminSquadController::class, 'destroy'])->name('admin.squad.destroy');

    Route::get('/admin/news', [AdminNewsController::class, 'show'])->name('admin.news');
    Route::post('/admin/news', [AdminNewsController::class, 'store'])->name('admin.news.store');
    Route::get('/admin/news/{news}/edit', [AdminNewsController::class, 'edit'])->name('admin.news.edit');
    Route::put('/admin/news/{news}', [AdminNewsController::class, 'update'])->name('admin.news.update');
    Route::delete('/admin/news/{news}', [AdminNewsController::class, 'destroy'])->name('admin.news.destroy');
});
