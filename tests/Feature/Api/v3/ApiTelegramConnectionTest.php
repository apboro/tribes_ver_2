<?php

namespace Tests\Feature\Api\v3;

use App\Models\TelegramConnection;
use App\Models\TelegramUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiTelegramConnectionTest extends TestCase
{

    private $url = [
        'create_connection' => 'api/v3/telegram-connections',
        'get_connection' => 'api/v3/telegram-connections/get-telegram-connection',
    ];

    private $data = [
        'error_not_auth' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ]
        ],
        'error_empty_platform' => [
            'platform' => '',
            'type' => 'test',
            'expected_status' => 422,
            'expected_structure' => [
                'errors',
                'message',
                'payload',
            ]
        ],
        'error_empty_type' => [
            'platform' => 'test',
            'type' => '',
            'expected_status' => 422,
            'expected_structure' => [
                'errors',
                'message',
                'payload',
            ]
        ],
        'error_telegram_account_not_exists' => [
            'platform' => 'Telegram',
            'type' => 'group',
            'expected_status' => 400,
            'expected_structure' => [
                'message',
            ]
        ],
        'create_telegram_connection_success' => [
            'platform' => 'Telegram',
            'type' => 'group',
            'expected_status' => 200,
            'expected_structure' => [
                'message',
            ]
        ],
        'get_telegram_connection_by_hash_empty_hash' => [
            'hash' => '',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'payload',
                'errors'
            ]
        ],
        'get_telegram_connection_success' => [
            'hash' => '',
            'expected_status' => 200,
            'expected_structure' => [
                'message',
                'payload',
                'data' => [
                    'telegram_connection' => [
                        'id',
                        'user_id',
                        'hash',
                        'status'
                    ]
                ]
            ]
        ]
    ];

    public function test_create_telegram_connection_error_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->url['create_connection']);

        $response->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);
    }

    public function test_create_telegram_empty_platform()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token
        ])->post($this->url['create_connection'], $this->data['error_empty_platform']);
        $response->assertStatus($this->data['error_empty_platform']['expected_status'])
            ->assertJsonStructure($this->data['error_empty_platform']['expected_structure']);
    }

    public function test_create_telegram_empty_type()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token
        ])->post($this->url['create_connection'], $this->data['error_empty_type']);

        $response->assertStatus($this->data['error_empty_type']['expected_status'])
            ->assertJsonStructure($this->data['error_empty_type']['expected_structure']);
    }


    public function test_create_telegram_connection_account_not_exists()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token
        ])->post($this->url['create_connection'], $this->data['error_telegram_account_not_exists']);
        $response->assertStatus($this->data['error_telegram_account_not_exists']['expected_status'])
            ->assertJsonStructure($this->data['error_telegram_account_not_exists']['expected_structure']);
    }

    public function test_create_telegram_connection_success()
    {
        $this->createUserForTest();
        TelegramUser::create([
            'telegram_id' => rand(1000000, 9999999999999),
            'user_id' => $this->custom_user->id,
            'auth_date' => time(),
            'first_name' => config('telegram_bot.bot.botName'),
            'user_name' => config('telegram_bot.bot.botFullName'),
        ]);
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token
        ])->post($this->url['create_connection'], $this->data['create_telegram_connection_success']);

        $response->assertStatus($this->data['create_telegram_connection_success']['expected_status'])
            ->assertJsonStructure($this->data['create_telegram_connection_success']['expected_structure']);
    }

    public function test_get_telegram_connection_by_hash_empty_hash()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token
        ])->post($this->url['get_connection'], $this->data['get_telegram_connection_by_hash_empty_hash']);

        $response->assertStatus($this->data['get_telegram_connection_by_hash_empty_hash']['expected_status'])
            ->assertJsonStructure($this->data['get_telegram_connection_by_hash_empty_hash']['expected_structure']);
    }

    public function test_get_telegram_connection_success()
    {
        $telegram_user = TelegramUser::create([
            'telegram_id' => rand(1000000, 9999999999999),
            'user_id' => $this->custom_user->id,
            'auth_date' => time(),
            'first_name' => config('telegram_bot.bot.botName'),
            'user_name' => config('telegram_bot.bot.botFullName'),
        ]);
        $hash = md5('test123' . time());
        TelegramConnection::create([
            'user_id' => $this->custom_user->id,
            'telegram_user_id' => $telegram_user->id,
            'chat_id' => '-100' . rand(100000, 9999999),
            'chat_title' => null,
            'chat_type' => 'comment',
            'isGroup' => true,
            'is_there_userbot' => false,
            'status' => 'init',
            'hash' => $hash
        ]);

        $this->data['get_telegram_connection_success']['hash'] = $hash;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token
        ])->post($this->url['get_connection'], $this->data['get_telegram_connection_success']);
        $response->assertStatus($this->data['get_telegram_connection_success']['expected_status'])
            ->assertJsonStructure($this->data['get_telegram_connection_success']['expected_structure']);
    }
}
