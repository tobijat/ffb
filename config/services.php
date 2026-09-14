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
        'debug' => (bool) env('WIKIMEDIA_DEBUG', false),
        // PHP on Windows often has empty curl.cainfo; point at a Mozilla CA bundle if present.
        'ca_bundle' => env('WIKIMEDIA_CA_BUNDLE', storage_path('certs/cacert.pem')),
    ],

];
