<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID', 'Ov23liqvXj58m8HXCUAQ'),
        'client_secret' => env('GITHUB_CLIENT_SECRET', '8d12910a357e3372a2ef6fa879076499eb5c09e1'),
        'redirect' => env('GITHUB_REDIRECT_URI', 'http://localhost:8000/login/github/callback'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID', '953448223113-aeftrgsnjll8dqgp0mt50lebghd7bqq3.apps.googleusercontent.com'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET', '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'),
        'redirect' => env('GOOGLE_REDIRECT_URI', 'http://localhost:8000/login/google/callback'),
    ],

];
