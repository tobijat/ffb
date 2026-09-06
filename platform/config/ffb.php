<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Allow impersonation headers / query params
    |--------------------------------------------------------------------------
    |
    | When false (recommended for production), API auth only accepts the
    | Laravel session key ffb_user_id set by POST /login.
    |
    | When true, also accepts X-FFB-User-Id / user_id / userteam_user_id for
    | local smoke tests and automated suites.
    |
    */
    'allow_user_id_header' => (bool) env(
        'FFB_ALLOW_USER_ID_HEADER',
        in_array(env('APP_ENV', 'production'), ['local', 'testing'], true)
    ),

    /*
    |--------------------------------------------------------------------------
    | Public home path (browser Location / window.location)
    |--------------------------------------------------------------------------
    |
    | Domain-absolute path to the platform start page. Must include the
    | /platform/public prefix when APP_URL is under that mount — do not pass
    | this through redirect('/…') or Laravel will prefix APP_URL again.
    |
    */
    'home_path' => (static function (): string {
        $path = parse_url((string) env('APP_URL', 'http://localhost/platform/public'), PHP_URL_PATH);

        if (! is_string($path) || $path === '' || $path === '/') {
            return '/platform/public/';
        }

        return rtrim($path, '/').'/';
    })(),

    /*
    |--------------------------------------------------------------------------
    | Legacy image filesystem root (profiles, flags, …)
    |--------------------------------------------------------------------------
    |
    | Absolute path to images/ffb under the legacy document root. Profile
    | uploads write here so existing /images/ffb/… URLs keep working.
    |
    */
    'legacy_images_path' => env(
        'FFB_LEGACY_IMAGES_PATH',
        dirname(base_path()).DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'ffb'
    ),
];
