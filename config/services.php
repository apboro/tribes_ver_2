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

    'webcasterPro' => [
        'client_name' => env('WEBCASTER_CLIENT_NAME', 'fit_univ42'),
        'client_password' => env('WEBCASTER_CLIENT_PASSWORD', '6ybM958cuBXmhkHU')
    ],

    'sms16' => [
//        'token' => env('SMS16_RU_CODE', '65ee35ac84b951da3b08464730f15c05ed0e0acd'),
//        'login' => env('SMS16_LOGIN', 'spodial')
        'token' => env('SMS16_RU_CODE', '266180dfebdd504d9c6175179b8eb1a69e5c9861'),
        'login' => env('SMS16_LOGIN', 'fitbelgorod')
    ],

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'search' => [
        'enabled' => env('ELASTICSEARCH_ENABLED', false),
        'hosts' => explode(',', env('ELASTICSEARCH_HOSTS')),
    ],

];
