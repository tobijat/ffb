<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function (): void {
            Route::get('/health', function () {
                return response()->json([
                    'status' => 'ok',
                    'app' => 'ffb-platform',
                    'laravel' => app()->version(),
                ]);
            });

            $pictoryHosts = app(\App\Services\AppHostService::class)
                ->hostsForService(\App\Services\AppHostService::SERVICE_PICTORY);

            if ($pictoryHosts !== []) {
                $pattern = implode('|', array_map(
                    static fn (string $host): string => preg_quote($host, '/'),
                    $pictoryHosts,
                ));

                // Single route group so route('pictory.*') uses the current request host.
                Route::domain('{pictoryDomain}')
                    ->where(['pictoryDomain' => $pattern])
                    ->middleware('web')
                    ->group(base_path('routes/pictory/web.php'));
            }
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Platform APIs share the Laravel session cookie with the start/dashboard UI.
        $middleware->api(prepend: [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \App\Http\Middleware\ResolveAppService::class,
        ]);

        $middleware->web(prepend: [
            \App\Http\Middleware\ResolveAppService::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\TouchFfbUserLastAction::class,
        ]);

        $middleware->redirectGuestsTo(function (Request $request) {
            if (app(\App\Services\AppHostService::class)->isPictory($request)) {
                return route('pictory.login');
            }

            $destination = $request->is('account')
                ? 'profile?tab=account'
                : trim($request->path(), '/');

            return route('start', ['destination' => $destination]);
        });

        $middleware->redirectUsersTo(function (Request $request) {
            if (app(\App\Services\AppHostService::class)->isPictory($request)) {
                return route('pictory.home');
            }

            return route('profile', ['tab' => 'account']);
        });

        $middleware->alias([
            'ffb.admin' => \App\Http\Middleware\EnsureFfbAdmin::class,
            'ffb.active' => \App\Http\Middleware\EnsureActiveFfbUser::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson() || $request->is('health'),
        );

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 401,
                    'error' => 'Du wurdest ausgeloggt. Bitte Seite neu laden um dich erneut einzuloggen.',
                ], 401);
            }
        });

        $exceptions->render(function (InvalidSignatureException $e, Request $request) {
            if ($request->is('password/reset/*')) {
                return redirect()->route('start')->with(
                    'account_message',
                    '<strong>Es sind Fehler aufgetreten:</strong><br>Der Link ist ungültig oder abgelaufen.'
                );
            }
        });
    })->create();
