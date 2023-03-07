<?php

namespace Tests\Feature\Api\v3;

use App\Models\Administrator;
use Tests\TestCase;

class ApiManagerUserListTest extends TestCase
{

    private $url = [
        'user_list' => 'api/v3/manager/users',
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
        'user_list_success'=>[
            'expected_status' => 200,
            'expected_structure' => [
                'message',
                'payload',
                'list'=>[
                    [
                    'id',
                    'name',
                    'email',
                    'phone',
                    'phone_confirmed',
                    'is_blocked',
                    'locale',
                    'role_index',
                    'created_at',
                    'updated_at'
                    ]
                ]
            ],
        ]
    ];


    public function test_user_list_data_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['user_list']);

        $response->assertStatus($this->data['empty_data']['expected_status'])
            ->assertJsonStructure($this->data['empty_data']['expected_structure']);
    }


    public function test_user_list_data_not_admin()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['user_list']);

        $response->assertStatus($this->data['not_admin']['expected_status'])
            ->assertJsonStructure($this->data['not_admin']['expected_structure']);
    }




    public function test_user_list_success()
    {

        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['user_list']);

        $response->assertStatus($this->data['user_list_success']['expected_status'])
            ->assertJsonStructure($this->data['user_list_success']['expected_structure']);
    }



}
