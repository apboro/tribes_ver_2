<?php

namespace Tests\Feature\Api\v3;

use App\Models\TelegramUserList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiTelegramUserWhiteListTest extends TestCase
{
    private $url = [
        'add_white_list' => 'api/v3/user/white-list',
        'delete_from_white_list' => 'api/v3/user/white-list/delete',
    ];

    private $data = [
        'error_not_auth' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ],
        ],
        'not_valid_data' => [
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'errors'
            ],
        ],
        'create_success' => [
            'expected_status' => 200,
            'expected_structure' => []
        ]
    ];


    public function test_white_list_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->url['add_white_list']);

        $response->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);

    }

    public function test_white_list_store_empty_data()
    {

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['add_white_list']);

        $response->assertStatus($this->data['not_valid_data']['expected_status'])
            ->assertJsonStructure($this->data['not_valid_data']['expected_structure']);
    }

    public function test_white_list_store_not_valid_telegram_id()
    {

        $this->data['not_valid_data']['telegram_id'] = 'test';
        $this->data['not_valid_data']['community_ids'] = [1];
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['add_white_list'], $this->data['not_valid_data']);

        $response->assertStatus($this->data['not_valid_data']['expected_status'])
            ->assertJsonStructure($this->data['not_valid_data']['expected_structure']);
    }

    public function test_white_list_store_not_valid_community_ids()
    {

        $this->data['not_valid_data']['telegram_id'] = $this->custom_user->telegramData()[0]->telegram_id;
        $this->data['not_valid_data']['community_ids'] = ['test'];
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['add_white_list'], $this->data['not_valid_data']);

        $response->assertStatus($this->data['not_valid_data']['expected_status'])
            ->assertJsonStructure($this->data['not_valid_data']['expected_structure']);
    }

    public function test_white_list_store_not_valid_is_spamer()
    {

        $this->data['not_valid_data']['telegram_id'] = $this->custom_user->telegramData()[0]->telegram_id;
        $this->data['not_valid_data']['community_ids'] = [1];
        $this->data['not_valid_data']['is_spammer'] = 'test';
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['add_white_list'], $this->data['not_valid_data']);

        $response->assertStatus($this->data['not_valid_data']['expected_status'])
            ->assertJsonStructure($this->data['not_valid_data']['expected_structure']);
    }

    public function test_white_list_store_success()
    {

        $this->data['not_valid_data']['telegram_id'] = $this->custom_user->telegramData()[0]->telegram_id;
        $this->data['not_valid_data']['community_ids'] = [$this->custom_community->id];
        $this->data['not_valid_data']['is_spammer'] = 1;

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['add_white_list'], $this->data['not_valid_data']);

        $response->assertStatus($this->data['create_success']['expected_status'])
            ->assertJsonStructure($this->data['create_success']['expected_structure']);
        $this->assertDatabaseHas('telegram_user_lists', ['telegram_id' => $this->custom_user->telegramData()[0]->telegram_id]);
        $this->assertDatabaseHas('list_community_telegram_user', [
            'telegram_id' => $this->custom_user->telegramData()[0]->telegram_id,
            'community_id' => $this->custom_community->id
        ]);

    }


    public function test_white_list_delete_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->url['delete_from_white_list']);

        $response->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);

    }

    public function test_white_list_delete_empty_data()
    {

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['delete_from_white_list']);

        $response->assertStatus($this->data['not_valid_data']['expected_status'])
            ->assertJsonStructure($this->data['not_valid_data']['expected_structure']);
    }

    public function test_white_list_delete_not_valid_telegram_id()
    {

        $this->data['not_valid_data']['telegram_id'] = 'test';
        $this->data['not_valid_data']['community_ids'] = [1];
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['delete_from_white_list'], $this->data['not_valid_data']);

        $response->assertStatus($this->data['not_valid_data']['expected_status'])
            ->assertJsonStructure($this->data['not_valid_data']['expected_structure']);
    }

    public function test_white_list_delete_not_valid_community_ids()
    {

        $this->data['not_valid_data']['telegram_id'] = $this->custom_user->telegramData()[0]->telegram_id;
        $this->data['not_valid_data']['community_ids'] = ['test'];
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['delete_from_white_list'], $this->data['not_valid_data']);

        $response->assertStatus($this->data['not_valid_data']['expected_status'])
            ->assertJsonStructure($this->data['not_valid_data']['expected_structure']);
    }

    public function test_white_list_delete_success()
    {

        $telegram_black_list = TelegramUserList::create([
            'telegram_id' => $this->custom_user->telegramData()[0]->telegram_id,
            'type' => 1
        ]);

        $telegram_black_list->communities()->syncWithoutDetaching($this->custom_community->id);
        $this->data['not_valid_data']['telegram_id'] = $this->custom_user->telegramData()[0]->telegram_id;
        $this->data['not_valid_data']['community_ids'] = [$this->custom_community->id];
        $this->data['not_valid_data']['is_spammer'] = 1;

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['delete_from_white_list'], $this->data['not_valid_data']);

        $response->assertStatus($this->data['create_success']['expected_status'])
            ->assertJsonStructure($this->data['create_success']['expected_structure']);
        $this->assertDatabaseMissing('telegram_user_lists', ['telegram_id' => $this->custom_user->telegramData()[0]->telegram_id]);
        $this->assertDatabaseMissing('list_community_telegram_user', [
            'telegram_id' => $this->custom_user->telegramData()[0]->telegram_id,
            'community_id' => $this->custom_community->id
        ]);
    }

}
