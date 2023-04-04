<?php

namespace Tests\Feature\Api\v3;

use App\Services\TelegramMainBotService;
use Database\Seeders\TelegramUsersCommunitySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiCommunityTelegramUsersTest extends TestCase
{
    use RefreshDatabase;

    private $url = [
        'get_list' => 'api/v3/user/community-users',
        'delete_telegram_user' => 'api/v3/user/community-users/detach',
        'detach_telegram_user' => 'api/v3/user/community-users/detach_all',
        'add_to_list' => 'api/v3/user/community-users',
    ];

    private $data = [
        'error_not_auth' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ],
        ],
        'get_list_success' => [
            'expected_status' => 200,
            'expected_structure' => [
                'data' => [
                    [
                        'telegram_id',
                        'name',
                        'last_name',
                        'user_name',
                        'accession_date',
                        'communities' => []
                    ],
                ],
            ],
        ],
        'detach_user_empty_telegram_user_id' => [
            'telegram_id' => '',
            'community_id' => '123',
            'expected_status' => 422,
            'expected_structure' => [
                'code'
            ],
        ],
        'detach_user_empty_community_id' => [
            'telegram_id' => '123',
            'community_id' => '',
            'expected_status' => 422,
            'expected_structure' => [
                'code'
            ],
        ],
        'detach_user_not_valid_community_id' => [
            'telegram_id' => '123',
            'community_id' => 'test',
            'expected_status' => 422,
            'expected_structure' => [
                'code'
            ],
        ],
        'detach_user_not_valid_telegram_user_id' => [
            'telegram_id' => 'test',
            'community_id' => '123',
            'expected_status' => 422,
            'expected_structure' => [
                'code'
            ],
        ],
        'detach_user_success' => [
            'telegram_id' => '',
            'community_id' => '',
            'expected_status' => 200,
            'expected_structure' => [
                'code'
            ],
        ],
        'delete_user_empty_telegram_user_id' => [
            'telegram_id' => '',
            'expected_status' => 422,
            'expected_structure' => [
                'code'
            ],
        ],
        'delete_user_not_valid_telegram_user_id' => [
            'telegram_id' => 'test',
            'expected_status' => 422,
            'expected_structure' => [
                'code'
            ],
        ],
        'delete_user_success' => [
            'telegram_id' => '',
            'expected_status' => 200,
            'expected_structure' => [
                'code'
            ],
        ]

    ];


    public function test_get_list_not_auth()
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
        $seeder_instance = new TelegramUsersCommunitySeeder($this->custom_user->id);
        $seeder_instance->run();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['get_list']);

        $response->assertStatus($this->data['get_list_success']['expected_status'])
            ->assertJsonStructure($this->data['get_list_success']['expected_structure']);
    }


    public function test_detach_user_not_auth()
    {


        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->url['detach_telegram_user']);

        $response->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);
    }

    public function test_detach_user_empty_user_id()
    {


        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post(
            $this->url['detach_telegram_user'],
            $this->data['detach_user_empty_telegram_user_id']);

        $response->assertStatus($this->data['detach_user_empty_telegram_user_id']['expected_status'])
            ->assertJsonStructure($this->data['detach_user_empty_telegram_user_id']['expected_structure']);
    }

    public function test_detach_user_empty_community_id()
    {

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post(
            $this->url['detach_telegram_user'],
            $this->data['detach_user_empty_community_id']);

        $response->assertStatus($this->data['detach_user_empty_community_id']['expected_status'])
            ->assertJsonStructure($this->data['detach_user_empty_community_id']['expected_structure']);
    }


    public function test_detach_user_not_valid_user_id()
    {

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post(
            $this->url['detach_telegram_user'],
            $this->data['detach_user_not_valid_telegram_user_id']);

        $response->assertStatus($this->data['detach_user_not_valid_telegram_user_id']['expected_status'])
            ->assertJsonStructure($this->data['detach_user_not_valid_telegram_user_id']['expected_structure']);
    }

    public function test_detach_user_not_valid_community_id()
    {

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post(
            $this->url['detach_telegram_user'],
            $this->data['detach_user_not_valid_community_id']);

        $response->assertStatus($this->data['detach_user_not_valid_community_id']['expected_status'])
            ->assertJsonStructure($this->data['detach_user_not_valid_community_id']['expected_structure']);
    }

    public function test_detach_user_success()
    {
        $this->createUserForTest();
        $this->createCommunityForTest();
        $this->createTelegramUserForTest();

        $this->custom_telegram_user->communities()->attach($this->custom_community);
        $this->data['detach_user_success']['telegram_id'] = $this->custom_telegram_user->telegram_id;
        $this->data['detach_user_success']['community_id'] = $this->custom_community->id;
        $mockTelegramBot = $this->mock(TelegramMainBotService::class);
        $mockTelegramBot->shouldReceive('kickUser')
            ->once()
            ->andReturn(true);
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post(
            $this->url['detach_telegram_user'],
            $this->data['detach_user_success']);

        $response->assertStatus($this->data['detach_user_success']['expected_status'])
            ->assertJsonStructure($this->data['detach_user_success']['expected_structure']);
    }

    public function test_delete_user_not_auth()
    {

        $response = $this->withHeaders([
            'Accept' => 'application/json',

        ])->post(
            $this->url['delete_telegram_user'],
            $this->data['error_not_auth']);

        $response->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);
    }

    public function test_delete_user_empty_user_id()
    {


        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post(
            $this->url['delete_telegram_user'],
            $this->data['delete_user_empty_telegram_user_id']);

        $response->assertStatus($this->data['delete_user_empty_telegram_user_id']['expected_status'])
            ->assertJsonStructure($this->data['delete_user_empty_telegram_user_id']['expected_structure']);
    }

    public function test_delete_user_not_valid_telegram_user_id()
    {

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post(
            $this->url['delete_telegram_user'],
            $this->data['delete_user_not_valid_telegram_user_id']);

        $response->assertStatus($this->data['delete_user_not_valid_telegram_user_id']['expected_status'])
            ->assertJsonStructure($this->data['delete_user_not_valid_telegram_user_id']['expected_structure']);
    }


    public function test_delete_user_success()
    {
        $this->createUserForTest();
        $this->createCommunityForTest();
        $this->createTelegramUserForTest();

        $this->custom_telegram_user->communities()->attach($this->custom_community);

        $this->data['delete_user_success']['telegram_id'] = $this->custom_telegram_user->telegram_id;
        $this->data['delete_user_success']['community_id'] = $this->custom_community->id;
        $mockTelegramBot = $this->mock(TelegramMainBotService::class);
        $mockTelegramBot->shouldReceive('kickUser')
            ->once()
            ->andReturn(true);
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post(
            $this->url['delete_telegram_user'],
            $this->data['delete_user_success']);

        $response->assertStatus($this->data['delete_user_success']['expected_status'])
            ->assertJsonStructure($this->data['delete_user_success']['expected_structure']);
    }

    public function test_add_to_list()
    {
        $this->createUserForTest();
        $this->createCommunityForTest();
        $this->createTelegramUserForTest();

        $this->custom_telegram_user->communities()->attach($this->custom_community);

        $this->data['delete_user_success']['community_ids'] = [$this->custom_community->id];
        $this->data['delete_user_success']['banned'] = true;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->put(
            $this->url['add_to_list'] . "/" . $this->custom_telegram_user->telegram_id,
            $this->data['delete_user_success']);
        $this->assertDatabaseHas('telegram_user_lists', ['telegram_id' => $this->custom_telegram_user->telegram_id, 'type' => 4]);
        $response->assertStatus($this->data['delete_user_success']['expected_status'])
            ->assertJsonStructure($this->data['delete_user_success']['expected_structure']);
    }

}
