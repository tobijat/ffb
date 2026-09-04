<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Allow impersonation headers / query params
    |--------------------------------------------------------------------------
    |
    | When false (recommended for production), API auth only accepts the legacy
    | PHP session cookie (PHPSESSID → $_SESSION['user_id']).
    |
    | When true, also accepts X-FFB-User-Id / user_id / userteam_user_id for
    | local smoke tests and automated suites.
    |
    */
    'allow_user_id_header' => (bool) env(
        'FFB_ALLOW_USER_ID_HEADER',
        in_array(env('APP_ENV', 'production'), ['local', 'testing'], true)
    ),
];
