<?php
return [
    'terminals' => [
        'terminalKey' => env('TINKOFF_TERMINAL_KEY'),
        'secretKey' => env('TINKOFF_SECRET_KEY'),

        'terminalDirect' => env('TINKOFF_TERMINAL_DIRECT'),
        'terminalDirectSecretKey' => env('TINKOFF_TERMINAL_DIRECT_SECRET_KEY'),

        'terminalKeyE2C' => env('TINKOFF_TERMINAL_KEY_E2C'),
        'secretKeyE2C' => env('TINKOFF_SECRET_KEY_E2C'),
    ],
    'urls' => [
        'real_url' => 'https://securepay.tinkoff.ru/v2/',
        'real_e2c_url' => 'https://securepay.tinkoff.ru/e2c/v2/',

        'test_url' => 'https://rest-api-test.tinkoff.ru/v2/',
        'test_e2c_url' => 'https://rest-api-test.tinkoff.ru/e2c/v2/',
    ],
    'billBearerToken' => 't.wPA05wYLQXoolT61maehe1oSfzA0b4NAeAC2CT9SOBybKLSoYJ3p-QxLA3XSbrT_Q9Kr3Er8eTuu2AkfkYV0-w',
    'billDaysActive' => 14,
    'test' => env('TINKOFF_TEST'),
];
