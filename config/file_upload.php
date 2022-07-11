<?php
return [
    'course' => [
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
        'video_handler' => [

        ],

    ],

    'user' => [
        'image_handler' => [

        ],
        'avatar_handler' => [

        ],
    ]
];