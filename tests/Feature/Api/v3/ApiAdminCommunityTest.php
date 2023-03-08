<?php

namespace Tests\Feature\Api\v3;

use App\Models\Administrator;
use App\Models\Community;
use App\Models\TelegramConnection;
use Tests\TestCase;

class ApiAdminCommunityTest extends TestCase
{
    private $url = [
        'communities_show' => 'api/v3/manager/communities',
        'communities_list' => 'api/v3/manager/communities',
        'communities_export'=>'api/v3/manager/export/communities',
    ];


    private $data = [
        'empty_data' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ],
        ],
        'not_admin' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ],
        ],
        'not_valid_id' => [
            'id' => 'test',
            'message' => 'test',
            'expected_status' => 422,
            'expected_structure' => [
                'errors',
                'message',
                'payload'
            ],
        ],
        'community_not_exists' => [
            'id' => 9999999,
            'message' => 'test',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'payload'
            ],
        ],
        'communities_show_success' => [
            'expected_status' => 200,
            'expected_structure' => [
                'message',
                'payload',
                'data' => [
                    "id",
                    "title",
                    "owner_name",
                    "owner_id",
                    "telegram",
                    "created_at",
                    "followers",
                    "balance",
                ]
            ],
        ],
        'communities_list_success' => [
            'expected_status' => 200,
            'expected_structure' => [
                'message',
                'payload',
                'list' => [[
                    "id",
                    "title",
                    "owner_name",
                    "owner_id",
                    "telegram",
                    "created_at",
                    "followers",
                    "balance",
                ]
                ]
            ],
        ]
    ];

    public function test_communities_show_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['communities_show']);

        $response->assertStatus($this->data['empty_data']['expected_status'])
            ->assertJsonStructure($this->data['empty_data']['expected_structure']);
    }


    public function test_communities_show_not_admin()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['communities_show']);

        $response->assertStatus($this->data['not_admin']['expected_status'])
            ->assertJsonStructure($this->data['not_admin']['expected_structure']);
    }


    public function test_communities_show_not_valid_id()
    {
        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['communities_show'] . "/test");

        $response->assertStatus($this->data['not_valid_id']['expected_status'])
            ->assertJsonStructure($this->data['not_valid_id']['expected_structure']);
    }

    public function test_communities_show_not_exists_id()
    {
        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['communities_show'] . "/99999999");

        $response->assertStatus($this->data['community_not_exists']['expected_status'])
            ->assertJsonStructure($this->data['community_not_exists']['expected_structure']);
    }

    public function test_communities_show_success()
    {
        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

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
        ])->get($this->url['communities_show'] . "/" . $community->id);

        $response->assertStatus($this->data['communities_show_success']['expected_status'])
            ->assertJsonStructure($this->data['communities_show_success']['expected_structure']);
    }

    public function test_communities_list_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['communities_list']);

        $response->assertStatus($this->data['empty_data']['expected_status'])
            ->assertJsonStructure($this->data['empty_data']['expected_structure']);
    }


    public function test_communities_list_not_admin()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['communities_list']);

        $response->assertStatus($this->data['not_admin']['expected_status'])
            ->assertJsonStructure($this->data['not_admin']['expected_structure']);
    }


    public function test_communities_list_success()
    {
        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();
        for ($z = 0; $z < 30; $z++) {
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

            Community::create([
                'owner' => $this->custom_user->id,
                'title' => 'test title',
                'connection_id' => $telegramm_connection->id,
            ]);
        }


        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['communities_list'] . "?page=2");

        $response->assertStatus($this->data['communities_list_success']['expected_status'])
            ->assertJsonStructure($this->data['communities_list_success']['expected_structure']);
    }

    public function test_communities_export_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['communities_export']);

        $response->assertStatus($this->data['empty_data']['expected_status'])
            ->assertJsonStructure($this->data['empty_data']['expected_structure']);
    }

    public function test_communities_export_not_admin()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['communities_export']);

        $response->assertStatus($this->data['not_admin']['expected_status'])
            ->assertJsonStructure($this->data['not_admin']['expected_structure']);
    }

    public function test_communities_export_success()
    {
        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['communities_export'])->assertOk();

        $this->assertNotEmpty($response->headers->get('content-disposition'));
        $response->assertDownload();
    }

}
