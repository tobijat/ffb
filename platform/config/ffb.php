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

    /*
    |--------------------------------------------------------------------------
    | Default game for newly registered users
    |--------------------------------------------------------------------------
    |
    | Maps to web_user_details.user_details_ffb_selected_game (ffb_game.game_id).
    |
    */
    'registration_default_game_id' => (int) env('FFB_REGISTRATION_DEFAULT_GAME_ID', 25),

    /*
    |--------------------------------------------------------------------------
    | Legacy PHP session cookie name (FFB_Session / admin auth)
    |--------------------------------------------------------------------------
    */
    'legacy_session_name' => env('FFB_LEGACY_SESSION_NAME', 'PHPSESSID'),

    /*
    |--------------------------------------------------------------------------
    | Terms PDF (registration TOS link)
    |--------------------------------------------------------------------------
    |
    | Served from platform/public/resource/Registrierung.pdf.
    |
    */
    'registration_tos_url' => (static function (): string {
        $override = env('FFB_REGISTRATION_TOS_URL');
        if (is_string($override) && $override !== '') {
            return $override;
        }

        $path = parse_url((string) env('APP_URL', 'http://localhost/platform/public'), PHP_URL_PATH);
        $home = (! is_string($path) || $path === '' || $path === '/')
            ? '/platform/public'
            : rtrim($path, '/');

        return $home.'/resource/Registrierung.pdf';
    })(),

    /*
    |--------------------------------------------------------------------------
    | Google reCAPTCHA v2
    |--------------------------------------------------------------------------
    |
    | Enable explicitly with FFB_RECAPTCHA_ENABLED=true plus site/secret keys.
    | Leave disabled on localhost (Mailpit/log mailer). Legacy reCAPTCHA v1
    | keys will not work — create v2 "I'm not a robot" checkbox keys.
    |
    */
    'recaptcha' => [
        'enabled' => filter_var(env('FFB_RECAPTCHA_ENABLED', false), FILTER_VALIDATE_BOOLEAN),
        'site_key' => env('FFB_RECAPTCHA_PUBLICKEY', env('RECAPTCHA_SITE_KEY', '')),
        'secret_key' => env('FFB_RECAPTCHA_PRIVATEKEY', env('RECAPTCHA_SECRET_KEY', '')),
    ],
];
