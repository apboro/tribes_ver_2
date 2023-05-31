<?php

namespace Tests\Feature\Api\v3;

use App\Models\Community;
use Database\Seeders\TelegramMessageSeeder;
use Database\Seeders\TelegramUsersCommunitySeeder;
use Tests\TestCase;

class ApiTelegramMessageStatisticTest extends TestCase
{
    private $url = [
        'message_statistic' => 'api/v3/statistic/messages/users',
        'message_statistic_charts' => 'api/v3/statistic/messages/charts',
        'message_statistic_export' => "api/v3/statistic/messages/export"
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
        'success' => [
            'expected_status' => 200,
            'expected_structure' => [
            ],
        ],
    ];

    public function setUp(): void
    {
        parent::setUp();

        $seeder_instance = new TelegramUsersCommunitySeeder($this->custom_user->id);
        $seeder_instance->run();

        $seeder_instance = new TelegramMessageSeeder($this->custom_user->id);
        $seeder_instance->run();
    }


    public function test_get_statistic_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['message_statistic']);


        $response->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);
    }


    public function test_get_statistic_success()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token
        ])->get($this->url['message_statistic']);
        $response->assertStatus($this->data['success']['expected_status'])
            ->assertJsonStructure($this->data['success']['expected_structure']);
    }


    public function test_get_statistic_charts_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['message_statistic_charts']);


        $response->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);
    }


    public function test_get_statistic_charts_success()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token
        ])->get($this->url['message_statistic_charts']);

        $response->assertStatus($this->data['success']['expected_status'])
            ->assertJsonStructure($this->data['success']['expected_structure']);
    }

    public function test_get_statistic_charts_hours_error()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token
        ])->get($this->url['message_statistic_charts'] . "?period=hour");

        $response->assertStatus($this->data['validation_error']['expected_status'])
            ->assertJsonStructure($this->data['validation_error']['expected_structure']);
    }

    public function test_get_message_statistic_charts_week_success()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token
        ])->get($this->url['message_statistic_charts']);

        $response->assertStatus($this->data['success']['expected_status'])
            ->assertJsonStructure($this->data['success']['expected_structure']);
    }

    public function test_get_message_statistic_success()
    {
        $communty = Community::where('owner', $this->custom_user->id)->first();

        $params = [
            'period' => 'week',
            'community_ids' => [$communty->id],
        ];
        $firstserialize = serialize($params);


        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token
        ])->get($this->url['message_statistic_charts'] . "?" . urlencode($firstserialize));

        $response->assertStatus($this->data['success']['expected_status'])
            ->assertJsonStructure($this->data['success']['expected_structure']);
    }


}
