<?php

use App\Http\Controllers\AccountPageController;
use App\Http\Controllers\Admin\AdminAwardsController;
use App\Http\Controllers\Admin\AdminCenterController;
use App\Http\Controllers\Admin\AdminDbCleanupController;
use App\Http\Controllers\Admin\AdminExtremeteamController;
use App\Http\Controllers\Admin\AdminLeagueController;
use App\Http\Controllers\Admin\AdminMailserviceController;
use App\Http\Controllers\Admin\AdminMatchController;
use App\Http\Controllers\Admin\AdminMatchdataController;
use App\Http\Controllers\Admin\AdminMatchroundController;
use App\Http\Controllers\Admin\AdminNewsController;
use App\Http\Controllers\Admin\AdminPlayerController;
use App\Http\Controllers\Admin\AdminPlayerpriceController;
use App\Http\Controllers\Admin\AdminScoreController;
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
| FFB application routes. Document root is public/.
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

Route::redirect('/admin/matchpoints/config', '/admin/score', 301);
Route::redirect('/admin/matchpoints', '/admin/matchdata', 301);

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
    Route::post('/admin/leagues/{league}/select', [AdminCenterController::class, 'selectLeague'])->name('admin.leagues.select');

    Route::get('/admin/leagues', [AdminLeagueController::class, 'show'])->name('admin.leagues');
    Route::post('/admin/leagues', [AdminLeagueController::class, 'store'])->name('admin.leagues.store');
    Route::get('/admin/leagues/{league}/edit', [AdminLeagueController::class, 'edit'])->name('admin.leagues.edit');
    Route::put('/admin/leagues/{league}', [AdminLeagueController::class, 'update'])->name('admin.leagues.update');
    Route::delete('/admin/leagues/{league}', [AdminLeagueController::class, 'destroy'])->name('admin.leagues.destroy');

    Route::get('/admin/matchrounds', [AdminMatchroundController::class, 'show'])->name('admin.matchrounds');
    Route::post('/admin/matchrounds', [AdminMatchroundController::class, 'store'])->name('admin.matchrounds.store');
    Route::get('/admin/matchrounds/{matchround}/edit', [AdminMatchroundController::class, 'edit'])->name('admin.matchrounds.edit');
    Route::put('/admin/matchrounds/{matchround}', [AdminMatchroundController::class, 'update'])->name('admin.matchrounds.update');
    Route::delete('/admin/matchrounds/{matchround}', [AdminMatchroundController::class, 'destroy'])->name('admin.matchrounds.destroy');

    Route::get('/admin/matches', [AdminMatchController::class, 'show'])->name('admin.matches');
    Route::post('/admin/matches', [AdminMatchController::class, 'store'])->name('admin.matches.store');
    Route::post('/admin/matches/auto/analyze', [AdminMatchController::class, 'analyzeAuto'])->name('admin.matches.auto.analyze');
    Route::post('/admin/matches/auto', [AdminMatchController::class, 'storeAuto'])->name('admin.matches.auto.store');
    Route::get('/admin/matches/{match}/edit', [AdminMatchController::class, 'edit'])->name('admin.matches.edit');
    Route::put('/admin/matches/{match}', [AdminMatchController::class, 'update'])->name('admin.matches.update');
    Route::delete('/admin/matches/{match}', [AdminMatchController::class, 'destroy'])->name('admin.matches.destroy');

    Route::get('/admin/teams', [AdminTeamController::class, 'show'])->name('admin.teams');
    Route::post('/admin/teams', [AdminTeamController::class, 'store'])->name('admin.teams.store');
    Route::post('/admin/teams/auto/analyze', [AdminTeamController::class, 'analyzeAuto'])->name('admin.teams.auto.analyze');
    Route::post('/admin/teams/auto', [AdminTeamController::class, 'storeAuto'])->name('admin.teams.auto.store');
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
    Route::post('/admin/squad/auto/analyze', [AdminSquadController::class, 'analyzeAuto'])->name('admin.squad.auto.analyze');
    Route::post('/admin/squad/auto', [AdminSquadController::class, 'storeAuto'])->name('admin.squad.auto.store');
    Route::post('/admin/squad/auto-uefa/analyze', [AdminSquadController::class, 'analyzeAutoUefa'])->name('admin.squad.auto-uefa.analyze');
    Route::post('/admin/squad/auto-uefa', [AdminSquadController::class, 'storeAutoUefa'])->name('admin.squad.auto-uefa.store');
    Route::post('/admin/squad/images/check', [AdminSquadController::class, 'checkImages'])->name('admin.squad.images.check');
    Route::post('/admin/squad/images/apply', [AdminSquadController::class, 'applyImages'])->name('admin.squad.images.apply');
    Route::post('/admin/squad/batch-update', [AdminSquadController::class, 'batchUpdate'])->name('admin.squad.batchUpdate');
    Route::put('/admin/squad/{playerteam}', [AdminSquadController::class, 'update'])->name('admin.squad.update');
    Route::delete('/admin/squad/{playerteam}', [AdminSquadController::class, 'destroy'])->name('admin.squad.destroy');

    Route::get('/admin/db-cleanup', [AdminDbCleanupController::class, 'show'])->name('admin.dbCleanup');

    Route::get('/admin/matchdata', [AdminMatchdataController::class, 'show'])->name('admin.matchdata');
    Route::get('/admin/matchdata/rounds', [AdminMatchdataController::class, 'rounds'])->name('admin.matchdata.rounds');
    Route::get('/admin/matchdata/rounds/{round}/matches', [AdminMatchdataController::class, 'matches'])->name('admin.matchdata.matches');
    Route::get('/admin/matchdata/rounds/{round}/most-wanted', [AdminMatchdataController::class, 'mostWanted'])->name('admin.matchdata.mostWanted');
    Route::get('/admin/matchdata/matches/{match}/teams/{team}/players', [AdminMatchdataController::class, 'players'])->name('admin.matchdata.players');
    Route::post('/admin/matchdata/matches/{match}/result', [AdminMatchdataController::class, 'setResult'])->name('admin.matchdata.setResult');
    Route::post('/admin/matchdata/matches/{match}/players/{playerteam}', [AdminMatchdataController::class, 'savePlayer'])->name('admin.matchdata.savePlayer');
    Route::post('/admin/matchdata/matches/{match}/scrape', [AdminMatchdataController::class, 'scrape'])->name('admin.matchdata.scrape');
    Route::match(['get', 'post'], '/admin/matchdata/wf-proxy', [AdminMatchdataController::class, 'wfProxy'])->name('admin.matchdata.wfProxy');

    Route::get('/admin/score', [AdminScoreController::class, 'show'])->name('admin.score');
    Route::post('/admin/score/userteam-scores', [AdminScoreController::class, 'setUserteamScores'])->name('admin.score.setUserteamScores');
    Route::post('/admin/score/user-scores', [AdminScoreController::class, 'setUserScores'])->name('admin.score.setUserScores');

    Route::get('/admin/playerprice', [AdminPlayerpriceController::class, 'show'])->name('admin.playerprice');
    Route::post('/admin/playerprice/matchround-performance/preview', [AdminPlayerpriceController::class, 'previewMatchroundPerformance'])->name('admin.playerprice.previewMatchroundPerformance');
    Route::post('/admin/playerprice/matchround-performance/save', [AdminPlayerpriceController::class, 'saveMatchroundPerformance'])->name('admin.playerprice.saveMatchroundPerformance');
    Route::post('/admin/playerprice/recent-performance/preview', [AdminPlayerpriceController::class, 'previewRecentPerformance'])->name('admin.playerprice.previewRecentPerformance');
    Route::post('/admin/playerprice/recent-performance/save', [AdminPlayerpriceController::class, 'saveRecentPerformance'])->name('admin.playerprice.saveRecentPerformance');
    Route::post('/admin/playerprice/elo-team-prices/preview', [AdminPlayerpriceController::class, 'previewEloTeamPrices'])->name('admin.playerprice.previewEloTeamPrices');
    Route::post('/admin/playerprice/elo-team-prices/save', [AdminPlayerpriceController::class, 'saveEloTeamPrices'])->name('admin.playerprice.saveEloTeamPrices');

    Route::get('/admin/extremeteam', [AdminExtremeteamController::class, 'show'])->name('admin.extremeteam');
    Route::post('/admin/extremeteam/populate', [AdminExtremeteamController::class, 'populate'])->name('admin.extremeteam.populate');

    Route::get('/admin/mailservice', [AdminMailserviceController::class, 'show'])->name('admin.mailservice');
    Route::get('/admin/mailservice/matchrounds', [AdminMailserviceController::class, 'matchrounds'])->name('admin.mailservice.matchrounds');
    Route::get('/admin/mailservice/users', [AdminMailserviceController::class, 'users'])->name('admin.mailservice.users');
    Route::get('/admin/mailservice/mails/{mail}', [AdminMailserviceController::class, 'mail'])->name('admin.mailservice.mail');
    Route::post('/admin/mailservice/send', [AdminMailserviceController::class, 'send'])->name('admin.mailservice.send');

    Route::get('/admin/awards', [AdminAwardsController::class, 'show'])->name('admin.awards');
    Route::post('/admin/awards/groups', [AdminAwardsController::class, 'createGroup'])->name('admin.awards.createGroup');
    Route::get('/admin/awards/groups/{group}', [AdminAwardsController::class, 'group'])->name('admin.awards.group');
    Route::post('/admin/awards/groups/update', [AdminAwardsController::class, 'updateGroup'])->name('admin.awards.updateGroup');
    Route::post('/admin/awards/defines', [AdminAwardsController::class, 'createDefine'])->name('admin.awards.createDefine');
    Route::post('/admin/awards/defines/update', [AdminAwardsController::class, 'updateDefine'])->name('admin.awards.updateDefine');
    Route::get('/admin/awards/defines/{define}/finished', [AdminAwardsController::class, 'finished'])->name('admin.awards.finished');
    Route::post('/admin/awards/defines/{define}/calculate', [AdminAwardsController::class, 'calculateDefine'])->name('admin.awards.calculateDefine');
    Route::post('/admin/awards/calculate-all', [AdminAwardsController::class, 'calculateAll'])->name('admin.awards.calculateAll');
    Route::delete('/admin/awards/finished/{finished}', [AdminAwardsController::class, 'deleteFinished'])->name('admin.awards.deleteFinished');

    Route::get('/admin/news', [AdminNewsController::class, 'show'])->name('admin.news');
    Route::post('/admin/news', [AdminNewsController::class, 'store'])->name('admin.news.store');
    Route::get('/admin/news/{news}/edit', [AdminNewsController::class, 'edit'])->name('admin.news.edit');
    Route::put('/admin/news/{news}', [AdminNewsController::class, 'update'])->name('admin.news.update');
    Route::delete('/admin/news/{news}', [AdminNewsController::class, 'destroy'])->name('admin.news.destroy');
});
