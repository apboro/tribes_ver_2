<?php

namespace Tests\Feature\Api\v3;

use App\Models\User;
use Tests\TestCase;

class ApiAssignDetachTelegramTest extends TestCase
{
    private $assign_url = '/api/v3/user/telegram/assign';
    private $detach_url = '/api/v3/user/telegram/detach';

    private $data = [
        'empty_data' => [
            'id' => '',
            'first_name' => '',
            'username' => '',
            'auth_date' => '',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'payload',
                'errors'
            ]
        ],
    ];

    public function test_assign_telegram_account_empty_request()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->assign_url, $this->data['empty_data']);


        $response->assertStatus($this->data['empty_data']['expected_status'])
            ->assertJsonStructure($this->data['empty_data']['expected_structure']);
    }

    public function test_assign_telegram_account_success()
    {
        $data = [
            'id' => rand(1000000, 9000000),
            'first_name' => 'test',
            'username' => 'test',
            'auth_date' => 15648987,
            'expected_status' => 200,
            'expected_structure' => [
                'message',
                'payload',
            ]
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->assign_url, $data);

        $response->assertStatus($data['expected_status'])
            ->assertJsonStructure($data['expected_structure']);
    }

    public function test_detach_telegram_account_success()
    {
        $data['telegram_id'] = $this->custom_telegram_user->telegram_id;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->detach_url, $data);

        $response->assertStatus(200);
    }
}

