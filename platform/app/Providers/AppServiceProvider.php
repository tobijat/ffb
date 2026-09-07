<?php

namespace App\Providers;

use App\Models\UserDetails;
use App\Services\FfbAuth;
use App\Services\GameBrand;
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
            if (array_key_exists('brandGame', $data)) {
                return;
            }

            try {
                $userId = app(FfbAuth::class)->userId(request());
                if ($userId <= 0) {
                    $view->with('brandGame', null);

                    return;
                }

                $gameId = (int) (UserDetails::query()
                    ->whereKey($userId)
                    ->value('user_details_ffb_selected_game') ?? 0);

                $view->with('brandGame', app(GameBrand::class)->forGameId($gameId));
            } catch (\Throwable) {
                $view->with('brandGame', null);
            }
        });
    }
}
