<?php

namespace Tests\Feature\Api\v3;

use App\Models\User;
use Tests\TestCase;

class ApiAssignDetachTelegramTest extends TestCase
{
    private $assign_url = '/api/v3/profile/assign/telegram';
    private $detach_url = '/api/v3/profile/detach/telegram';

    private $data = [
        'empty_data'=>[
            'id'=>'',
            'first_name'=>'',
            'username'=>'',
            'auth_date'=>'',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'payload',
                'errors'
            ]
        ],
        'success'=>[
            'id'=>100500,
            'first_name'=>'test',
            'username'=>'test',
            'auth_date'=>15648987,
            'expected_status' => 200,
            'expected_structure' => [
                'message',
                'payload',
            ]
        ]
    ];

    public function test_assign_telegram_account_empty_request()
    {
        $user = User::factory()->create();

        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->post($this->assign_url, $this->data['empty_data']);


        $response->assertStatus($this->data['empty_data']['expected_status'])
            ->assertJsonStructure($this->data['empty_data']['expected_structure']);
    }

    public function test_assign_telegram_account_success()
    {
        $user = User::factory()->create();

        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->post($this->assign_url, $this->data['success']);

        $response->assertStatus($this->data['success']['expected_status'])
            ->assertJsonStructure($this->data['success']['expected_structure']);
    }

    public function test_detach_telegram_account_success()
    {
        //TODO write after TZ
    }
}

