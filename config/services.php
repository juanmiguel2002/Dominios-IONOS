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
    'ionos' => [
        'key' => env('API_KEY'),
    ],

    'plesk' => [
        'servers' => [
            'server1' => [
                'name' => 'Servidor Principal',
                'host' => env('PLESK1_HOST'),
                'username' => env('PLESK1_USERNAME'),
                'password' => env('PLESK1_PASSWORD'),
                'verify_ssl' => env('PLESK1_VERIFY_SSL', true),
            ],
            'server2' => [
                'name' => 'Servidor Secundario',
                'host' => env('PLESK2_HOST'),
                'username' => env('PLESK2_USERNAME'),
                'password' => env('PLESK2_PASSWORD'),
                'verify_ssl' => env('PLESK2_VERIFY_SSL', true),
            ],
        ],
    ]


];
