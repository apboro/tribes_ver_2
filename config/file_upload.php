<?php
return [
    'course' => [
        'image_handler' => [
            'handler' => \App\Services\File\Handlers\ImageHandler::class,
            'path' => 'image',
        ],
        'video_handler' => [
            'handler' => \App\Services\File\Handlers\VideoHandler::class,
            'path' => null,
        ],
        'audio_handler' => [
            'handler' => \App\Services\File\Handlers\AudioHandler::class,
            'path' => 'audio',
        ],
    ],
    'donate' => [
        'image_handler' => [
            'handler' => \App\Services\File\Handlers\ImageHandler::class,
            'path' => 'image',
            'procedure' => ['crop', 'watermark'],
        ],
    ],
    'tariff' => [
        'image_handler' => [
            'handler' => \App\Services\File\Handlers\ImageHandler::class,
            'path' => 'image',
            'procedure' => ['crop'],
        ],
    ],
];