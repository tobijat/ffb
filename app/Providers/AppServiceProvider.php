<?php

namespace App\Providers;

use App\Models\UserDetails;
use App\Services\FfbAuth;
use App\Services\LeagueBrand;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('partials.brand', function ($view): void {
            $data = $view->getData();
            if (array_key_exists('brandLeague', $data)) {
                return;
            }

            try {
                $userId = app(FfbAuth::class)->userId(request());
                if ($userId <= 0) {
                    $view->with('brandLeague', null);

                    return;
                }

                $leagueId = (int) (UserDetails::query()
                    ->whereKey($userId)
                    ->value('user_details_ffb_selected_league') ?? 0);

                $view->with('brandLeague', app(LeagueBrand::class)->forLeagueId($leagueId));
            } catch (\Throwable) {
                $view->with('brandLeague', null);
            }
        });
    }
}
