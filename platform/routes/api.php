<?php

use App\Http\Controllers\Api\BestteamController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\LineupController;
use App\Http\Controllers\Api\MyteamController;
use App\Http\Controllers\Api\PopupController;
use App\Http\Controllers\Api\UserscoreController;
use App\Http\Controllers\StartController;
use App\Http\Middleware\ResolveFfbUser;
use Illuminate\Support\Facades\Route;

Route::get('/start', [StartController::class, 'data']);

Route::middleware([ResolveFfbUser::class])->group(function () {
    Route::get('/lineup', [LineupController::class, 'show']);
    Route::post('/lineup', [LineupController::class, 'store']);
    Route::get('/lineup/options', [LineupController::class, 'options']);
    Route::get('/lineup/matchround', [LineupController::class, 'matchround']);
    Route::get('/lineup/teams/{teamId}/players', [LineupController::class, 'teamPlayers'])
        ->whereNumber('teamId');

    Route::get('/dashboard', [DashboardController::class, 'show']);
    Route::post('/game/select', [DashboardController::class, 'selectGame']);
    Route::post('/poll/vote', [DashboardController::class, 'votePoll']);

    Route::get('/userscore/matchrounds', [UserscoreController::class, 'matchrounds']);
    Route::get('/userscore', [UserscoreController::class, 'overall']);
    Route::get('/userscore/rounds/{matchroundId}', [UserscoreController::class, 'round'])
        ->whereNumber('matchroundId');

    Route::get('/myteam/matchrounds', [MyteamController::class, 'matchrounds']);
    Route::get('/myteam/users', [MyteamController::class, 'users']);
    Route::get('/myteam/team', [MyteamController::class, 'team']);
    Route::get('/myteam/stats/user', [MyteamController::class, 'userStats']);
    Route::get('/myteam/stats/round', [MyteamController::class, 'roundStats']);

    Route::get('/bestteam/matchrounds', [BestteamController::class, 'matchrounds']);
    Route::get('/bestteam/team', [BestteamController::class, 'team']);
    Route::get('/bestteam/stats/round', [BestteamController::class, 'roundStats']);

    Route::get('/popups/user/{userId}', [PopupController::class, 'user'])
        ->whereNumber('userId');
    Route::get('/popups/user/{userId}/awards', [PopupController::class, 'userAwards'])
        ->whereNumber('userId');
    Route::get('/popups/match/{matchId}', [PopupController::class, 'match'])
        ->whereNumber('matchId');
    Route::get('/popups/player/{playerteamId}', [PopupController::class, 'player'])
        ->whereNumber('playerteamId');
    Route::get('/popups/player/{playerteamId}/chart', [PopupController::class, 'playerChart'])
        ->whereNumber('playerteamId');
    Route::get('/popups/player/{playerteamId}/prices', [PopupController::class, 'playerPrices'])
        ->whereNumber('playerteamId');
    Route::get('/popups/player/{playerteamId}/rounds/{matchroundId}', [PopupController::class, 'playerRound'])
        ->whereNumber('playerteamId')
        ->whereNumber('matchroundId');
});
