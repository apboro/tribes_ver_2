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
    'tariff' => [
        'image_handler' => [
            'handler' => \App\Services\File\Handlers\ImageHandler::class,
            'path' => 'image',

        ],
    ],

    'donate' => [
        'image_handler' => [
            'crop' => [
                'handler' => \App\Services\File\Handlers\ImageHandler::class,
                'path' => 'image',
            ],
            'resize' => [
                'handler' => \App\Services\File\Handlers\ImageHandler::class,
                'path' => 'image',
            ],
            'default' => [
                'handler' => \App\Services\File\Handlers\ImageHandler::class,
                'path' => 'image',
            ],
        ],
    ],

    'user' => [
        'image_handler' => [

        ],
        'avatar_handler' => [

        ],
    ]
];