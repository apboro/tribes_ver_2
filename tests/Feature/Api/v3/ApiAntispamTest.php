<?php

namespace Tests\Feature\Api\v3;

use Tests\TestCase;

class ApiAntispamTest extends TestCase
{
    private $url = [
        'add_' => 'api/v3/user/chats',
    ];

    private $data = [
        'error_not_auth' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ],
        ],
        'validation_error' => [
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ],
        ],
    ];

    public function test_store_not_auth()
    {

    }

}
