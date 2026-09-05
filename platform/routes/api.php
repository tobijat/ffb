<?php

use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\LineupController;
use App\Http\Controllers\StartController;
use App\Http\Middleware\ResolveFfbUser;
use Illuminate\Support\Facades\Route;

Route::get('/start', [StartController::class, 'data']);

Route::middleware([ResolveFfbUser::class])->group(function () {
    Route::get('/lineup', [LineupController::class, 'show']);
    Route::post('/lineup', [LineupController::class, 'store']);

    Route::get('/dashboard', [DashboardController::class, 'show']);
    Route::post('/game/select', [DashboardController::class, 'selectGame']);
    Route::post('/poll/vote', [DashboardController::class, 'votePoll']);
});
