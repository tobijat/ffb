<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'wikimedia' => [
        'sparql_url' => env('WIKIDATA_SPARQL_URL', 'https://query.wikidata.org/sparql'),
        'commons_api_url' => env('WIKIMEDIA_COMMONS_API_URL', 'https://commons.wikimedia.org/w/api.php'),
        'user_agent' => env('WIKIMEDIA_USER_AGENT', 'SoccerSportsfan'),
        'thumbnail_width' => (int) env('WIKIMEDIA_THUMBNAIL_WIDTH', 200),
        'timeout' => (int) env('WIKIMEDIA_HTTP_TIMEOUT', 30),
        'connect_timeout' => (int) env('WIKIMEDIA_CONNECT_TIMEOUT', 3),
        // Soft wall-clock budget for one resolve call (must stay under PHP max_execution_time).
        'time_budget' => (int) env('WIKIMEDIA_TIME_BUDGET', 20),
        'debug' => (bool) env('WIKIMEDIA_DEBUG', false),
        // PHP on Windows often has empty curl.cainfo; point at a Mozilla CA bundle if present.
        'ca_bundle' => env('WIKIMEDIA_CA_BUNDLE', storage_path('certs/cacert.pem')),
    ],

    'uefa' => [
        'base_url' => env('UEFA_COMP_API_URL', 'https://comp.uefa.com/v2'),
        'timeout' => (int) env('UEFA_COMP_HTTP_TIMEOUT', 20),
        'connect_timeout' => (int) env('UEFA_COMP_CONNECT_TIMEOUT', 5),
        'page_limit' => (int) env('UEFA_COMP_PAGE_LIMIT', 500),
        'max_pages' => (int) env('UEFA_COMP_MAX_PAGES', 40),
        // PHP on Windows often has empty curl.cainfo; reuse the same Mozilla CA bundle as Wikimedia.
        'ca_bundle' => env('UEFA_COMP_CA_BUNDLE', storage_path('certs/cacert.pem')),
        /*
         * Auto-Kader (UEFA) presets. round_orders = UEFA orderInCompetition values.
         * Nations League: League phase only (1).
         * WM 2026: tournament rounds without European qualifying groups/play-off semis (3–9).
         */
        'competitions' => [
            'nations_league_2027' => [
                'label' => 'Nations League 2026/2027 (Ligaphase)',
                'competition_id' => 2014,
                'season_year' => 2027,
                'round_orders' => [1],
            ],
            'wm_2026' => [
                'label' => 'WM 2026 (Endrunde)',
                'competition_id' => 17,
                'season_year' => 2026,
                'round_orders' => [3, 4, 5, 6, 7, 8, 9],
            ],
        ],
    ],

];
