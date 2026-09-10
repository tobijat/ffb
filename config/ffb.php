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
    | Domain-absolute path to the start page. Do not pass this through
    | redirect('/…') or Laravel will prefix APP_URL again when APP_URL
    | already includes a path.
    |
    */
    'home_path' => (static function (): string {
        $path = parse_url((string) env('APP_URL', 'http://localhost'), PHP_URL_PATH);

        if (! is_string($path) || $path === '' || $path === '/') {
            return '/';
        }

        return rtrim($path, '/').'/';
    })(),

    /*
    |--------------------------------------------------------------------------
    | Image filesystem root (profiles, flags, …)
    |--------------------------------------------------------------------------
    |
    | Absolute path to images/ffb under the Laravel public directory. Profile
    | uploads write here so /images/ffb/… URLs keep working.
    |
    */
    'legacy_images_path' => env(
        'FFB_LEGACY_IMAGES_PATH',
        public_path('images'.DIRECTORY_SEPARATOR.'ffb')
    ),

    /*
    |--------------------------------------------------------------------------
    | Default game for newly registered users
    |--------------------------------------------------------------------------
    |
    | Maps to web_user_details.user_details_ffb_selected_league (ffb_league.league_id).
    |
    */
    'registration_default_league_id' => (int) env('FFB_REGISTRATION_DEFAULT_LEAGUE_ID', 25),

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
    | Served from public/resource/Registrierung.pdf.
    |
    */
    'registration_tos_url' => (static function (): string {
        $override = env('FFB_REGISTRATION_TOS_URL');
        if (is_string($override) && $override !== '') {
            return $override;
        }

        $path = parse_url((string) env('APP_URL', 'http://localhost'), PHP_URL_PATH);
        $home = (! is_string($path) || $path === '' || $path === '/')
            ? ''
            : rtrim($path, '/');

        return $home.'/resource/Registrierung.pdf';
    })(),

    /*
    |--------------------------------------------------------------------------
    | Admin / bulk mailservice (port of legacy FFB_Mail + area_config)
    |--------------------------------------------------------------------------
    */
    'mail' => [
        'subject_prefix' => env('FFB_MAIL_SUBJECT_PREFIX', 'SoccerSportsfan - '),
        'greez' => env('FFB_MAIL_GREEZ', 'Dein SoccerSportsfan-Team'),
        'url' => env('FFB_MAIL_URL', env('APP_URL', 'http://localhost')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Elo ratings (admin playerprice)
    |--------------------------------------------------------------------------
    */
    'elo' => [
        'url' => env('FFB_ELO_URL', 'http://www.eloratings.net/world.html'),
        'team_map_url' => env('FFB_ELO_TEAM_MAP_URL', 'http://soccer.sportsfan.at/parserfiles/teams/teams.csv'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Outbound HTTP (scrapers / Elo)
    |--------------------------------------------------------------------------
    |
    | Windows PHP often lacks a CA bundle (cURL error 60). Leave verify off in
    | local/testing, or set FFB_HTTP_VERIFY_SSL=false explicitly.
    |
    */
    'http' => [
        'verify_ssl' => filter_var(
            env(
                'FFB_HTTP_VERIFY_SSL',
                ! in_array(env('APP_ENV', 'production'), ['local', 'testing'], true)
            ),
            FILTER_VALIDATE_BOOLEAN
        ),
    ],
];
