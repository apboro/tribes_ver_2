<?php

namespace Tests\Feature\Api\v3;

use App\Models\Community;
use App\Models\TelegramConnection;
use App\Models\TelegramUser;
use Tests\TestCase;

class ApiCommunityTest extends TestCase
{
    private $url = [
        'show_community' => 'api/v3/communities',
        'create_community' => 'api/v3/communities',
        'get_list' => 'api/v3/communities',
    ];

    private $data = [
        'error_not_auth' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ],
        ],
        'show_community_error_type' => [
            'id' => 'test',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'payload',
                'errors' => [
                    'id',
                ],
            ],
        ],
        'show_community_not_auth_user' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
                'payload',
            ],
        ],
        'show_community_success' => [
            'expected_status' => 200,
            'expected_structure' => [
                'message',
                'payload',
                'data' => [
                    'id',
                    'title',
                    'image',
                    'description',
                    'hash',
                    'balance',
                    'donate',
                    'type',
                ],
            ],
        ],
        'add_community_not_auth_user' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
                'payload',
            ],
        ],
        'add_community_empty_hash' => [
            'hash' => '',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'payload',
                'errors',
            ],
        ],
        'not_respond_telegram' => [
            'hash' => '',
            'expected_status' => 400,
            'expected_structure' => [
                'message',
                'payload',
            ],
        ],
        'add_community_success' => [
            'hash' => '',
            'expected_status' => 200,
            'expected_structure' => [
                'message',
                'payload',
                'data' => [
                    'id',
                    'title',
                    'image',
                    'description',
                ],
            ],
        ],
        'get_list_error_not_auth' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ],
        ],
        'get_list_success' => [
            'expected_status' => 200,
            'expected_structure' => [
                'message',
                'list' => [
                    [
                        'id',
                        'connection_id',
                        'owner',
                        'title',
                        'image',
                        'description',
                        'created_at',
                        'updated_at',
                        'hash',
                        'balance',
                        'project_id',
                        'donate',
                    ],
                ],
            ],
        ],
    ];

    public function test_show_community_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['show_community'] . "/99999999999");
        $response->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);
    }

    public function test_show_community_error_type()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['show_community'] . '/' . $this->data['show_community_error_type']['id']);
        $response->assertStatus($this->data['show_community_error_type']['expected_status'])
            ->assertJsonStructure($this->data['show_community_error_type']['expected_structure']);
    }

    public function test_show_community_success()
    {
        $telegramm_connection = TelegramConnection::create([
            'user_id' => $this->custom_user->id,
            'telegram_user_id' => rand(700000000, 799999999),
            'chat_id' => "-" . rand(700000000, 799999999),
            'chat_title' => $this->faker->text(80),
            'chat_type' => 'channel',
            'isAdministrator' => true,
            'botStatus' => 'administrator',
            'isActive' => array_rand([true, false]),
            'isChannel' => true,
            'isGroup' => false,
        ]);
        $community = Community::create([
            'owner' => $this->custom_user->id,
            'title' => 'test title',
            'connection_id' => $telegramm_connection->id,
        ]);
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['show_community'] . '/' . $community->id);
        $response->assertStatus($this->data['show_community_success']['expected_status'])
            ->assertJsonStructure($this->data['show_community_success']['expected_structure']);
    }

    public function test_show_community_not_authorize_user()
    {
        $telegramm_connection = TelegramConnection::create([
            'user_id' => $this->custom_user->id,
            'telegram_user_id' => rand(700000000, 799999999),
            'chat_id' => "-" . rand(700000000, 799999999),
            'chat_title' => $this->faker->text(80),
            'chat_type' => 'channel',
            'isAdministrator' => true,
            'botStatus' => 'administrator',
            'isActive' => array_rand([true, false]),
            'isChannel' => true,
            'isGroup' => false,
        ]);
        $community = Community::create([
            'owner' => $this->custom_user->id,
            'title' => 'test title',
            'connection_id' => $telegramm_connection->id,
        ]);
        $this->createUserForTest();
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['show_community'] . '/' . $community->id);

        $response->assertStatus($this->data['show_community_not_auth_user']['expected_status'])
            ->assertJsonStructure($this->data['show_community_not_auth_user']['expected_structure']);
    }

    public function test_add_community_not_auth_user()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->url['create_community']);
        $response->assertStatus($this->data['add_community_not_auth_user']['expected_status'])
            ->assertJsonStructure($this->data['add_community_not_auth_user']['expected_structure']);
    }

    public function test_add_community_empty_hash()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['create_community'], $this->data['add_community_empty_hash']);
        $response->assertStatus($this->data['add_community_empty_hash']['expected_status'])
            ->assertJsonStructure($this->data['add_community_empty_hash']['expected_structure']);
    }

    public function test_add_community_not_respond_telegram()
    {
        $telegramm_connection = TelegramConnection::create([
            'user_id' => $this->custom_user->id,
            'telegram_user_id' => rand(700000000, 799999999),
            'chat_id' => "-" . rand(700000000, 799999999),
            'chat_title' => $this->faker->text(80),
            'chat_type' => 'channel',
            'isAdministrator' => true,
            'botStatus' => 'administrator',
            'isActive' => array_rand([true, false]),
            'isChannel' => true,
            'isGroup' => false,
            'hash' => md5('testhash'),
            'status' => 'init',
        ]);
        $this->data['not_respond_telegram']['hash'] = $telegramm_connection->hash;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['create_community'], $this->data['not_respond_telegram']);
        $response->assertStatus($this->data['not_respond_telegram']['expected_status'])
            ->assertJsonStructure($this->data['not_respond_telegram']['expected_structure']);
    }

    public function test_add_community_success()
    {
        $this->createUserForTest();
        $telegramm_connection = TelegramConnection::create([
            'user_id' => $this->custom_user->id,
            'telegram_user_id' => rand(700000000, 799999999),
            'chat_id' => "-" . rand(700000000, 799999999),
            'chat_title' => $this->faker->text(80),
            'chat_type' => 'channel',
            'isAdministrator' => true,
            'botStatus' => 'administrator',
            'isActive' => array_rand([true, false]),
            'isChannel' => true,
            'isGroup' => false,
            'hash' => md5('testhash' . time()),
            'status' => 'connected',
        ]);

        $this->data['add_community_success']['hash'] = $telegramm_connection->hash;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['create_community'], $this->data['add_community_success']);

        $telegramm_connection_after = TelegramConnection::where('id', '=', $telegramm_connection->id)->first();

        $this->assertEquals($telegramm_connection_after->status, 'completed');
        $response->assertStatus($this->data['add_community_success']['expected_status'])
            ->assertJsonStructure($this->data['add_community_success']['expected_structure']);
    }

    public function test_get_list_error_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['get_list']);
        $response->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);
    }

    public function test_get_list_success()
    {
        $this->createUserForTest();
        $telegram_user_id = rand(700000000, 799999999);
        $telegram_user = TelegramUser::create([
            'user_id' => $this->custom_user->id,
            'telegram_id' => $telegram_user_id,
        ]);
        for ($z = 0; $z < 3; $z++) {
            $telegramm_connection = TelegramConnection::create([
                'user_id' => $this->custom_user->id,
                'telegram_user_id' => $telegram_user_id,
                'chat_id' => "-" . rand(700000000, 799999999),
                'chat_title' => $this->faker->text(80),
                'chat_type' => 'channel',
                'isAdministrator' => true,
                'botStatus' => 'administrator',
                'isActive' => array_rand([true, false]),
                'isChannel' => true,
                'isGroup' => false,
                'hash' => md5('testhash' . time()),
                'status' => 'connected',
            ]);

            $community = Community::create([
                'owner' => $this->custom_user->id,
                'title' => $telegramm_connection->chat_title,
                'connection_id' => $telegramm_connection->id,
                'image' => '',
            ]);
        }

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['get_list']);

        $response->assertStatus($this->data['get_list_success']['expected_status'])
            ->assertJsonStructure($this->data['get_list_success']['expected_structure']);
    }

}
