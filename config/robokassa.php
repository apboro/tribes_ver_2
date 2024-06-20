<?php

return [
    'payment_creation_url' => 'https://auth.robokassa.ru/Merchant/Indexjson.aspx?',
    'payment_redirect_url' => 'https://auth.robokassa.ru/Merchant/Index/',
    'default_merchant_login' => env('ROBOKASSA_MERCHANT_LOGIN', ''),
    'default_first_password' => env('ROBOKASSA_FIRST_PASSWORD', ''),
    'default_second_password' => env('ROBOKASSA_SECOND_PASSWORD', ''),
    'is_test' => env('ROBOKASSA_TEST', false)
];