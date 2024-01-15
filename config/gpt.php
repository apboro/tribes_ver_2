<?php

return [
    'apiKey' => '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',
    'useProxy' => true,
    'proxy' => 'RYGqZmxT:3NbTTf8n@46.3.66.248:60752',
    'options' => [
        'model' => 'gpt-3.5-turbo',
        'max_tokens' => 500,
        'temperature' => 0.1,
        'top_p' => 1,
        'presence_penalty' => 0,
        'frequency_penalty' => 0,
        'n' => 1,
        'stream' => false,
        ],
    'themesIntervalHours' => 24, // За сколько последних часов берем сообщения для получения тем
    'waitBetweenRequests' => 21, // Задержка между запросами в секундах
    ];