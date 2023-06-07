<?php

namespace Tests\Feature\Api\v3;

use Database\Seeders\TelegramUserListSeeder;
use Database\Seeders\TelegramUsersCommunitySeeder;
use Database\Seeders\ViolationSeeder;
use Tests\TestCase;

class ApiTelegramModerationStatisticTest extends TestCase
{
    private $url = [
        'message_statistic' => 'api/v3/statistic/moderation/users',
        'message_statistic_charts' => 'api/v3/statistic/moderation/charts',
        'message_statistic_export' => "api/v3/statistic/moderation/export"
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

        $seeder_instance = new TelegramUserListSeeder();
        $seeder_instance->run();

        $seeder_instance = new ViolationSeeder();
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
        ])->get($this->url['message_statistic_charts'] . '?period=year');

        $response->assertStatus($this->data['success']['expected_status'])
            ->assertJsonStructure($this->data['success']['expected_structure']);
    }
}
