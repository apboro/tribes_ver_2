<?php
return [
    'bot' => [
        'botName' => env('TELEGRAM_BOT_FIRST_NAME','UnitBot'),
        'botFullName' => '@' . env('TELEGRAM_BOT_NAME', 'Unit_for_factory_bot'),
        'botId' => env('TELEGRAM_BOT_ID'),
        'token' => env('TELEGRAM_BOT_TOKEN'),
        'marketName' => env('MARKET_NAME', ''),
        'promoName' => env('PROMO_NAME', ''),
    ],

    'bot1' => [
        'botName' => 'bot1',
        'botFullName' => '@bot',
        'botId' => 5006138689,
        'token' => env('TELEGRAM_BOT_TOKEN1', ''),
    ]
];