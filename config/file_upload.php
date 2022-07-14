<?php
return [
    'course' => [
        'image_handler' => [
            'handler' => \App\Services\File\Handlers\ImageHandler::class,
            'path' => 'image',
        ],
        'video_handler' => [
            'default' => [
                'handler' => \App\Services\File\Handlers\VideoHandler::class,
                'path' => null,
            ],
        ],
        'audio_handler' => [
            'default' => [
                'handler' => \App\Services\File\Handlers\AudioHandler::class,
                'path' => 'audio',
            ],
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