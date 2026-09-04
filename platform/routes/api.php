<?php

use App\Http\Controllers\Api\LineupController;
use App\Http\Middleware\ResolveFfbUser;
use Illuminate\Support\Facades\Route;

Route::middleware([ResolveFfbUser::class])->group(function () {
    Route::get('/lineup', [LineupController::class, 'show']);
    Route::post('/lineup', [LineupController::class, 'store']);
});
