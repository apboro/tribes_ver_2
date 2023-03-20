<?php

namespace Tests\Feature\Api\v3;

use App\Models\Administrator;
use App\Models\User;
use Tests\TestCase;

class ApiManagerSendNewPaswwordTest extends TestCase
{
    private $url = [
        'send_new_password' => 'api/v3/manager/users/send-new-password',
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
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ],
        ],
        'user_not_exists'=>[
            'expected_status' => 404,
            'expected_structure' => [
                'message',
            ],
        ],
        'send_new_password_success'=>[
            'expected_status' => 200,
            'expected_structure' => [
                'message',
            ],
        ]
    ];

    public function test_send_new_password_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['send_new_password'].'/test');

        $response->assertStatus($this->data['empty_data']['expected_status'])
            ->assertJsonStructure($this->data['empty_data']['expected_structure']);
    }


    public function test_send_new_password_not_admin()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['send_new_password']."/test");

        $response->assertStatus($this->data['not_admin']['expected_status'])
            ->assertJsonStructure($this->data['not_admin']['expected_structure']);
    }

    public function test_send_new_password_not_valid_id()
    {
        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['send_new_password']."/test");


        $response->assertStatus($this->data['not_valid_id']['expected_status'])
            ->assertJsonStructure($this->data['not_valid_id']['expected_structure']);
    }

    public function test_send_new_password_not_exists()
    {

        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['send_new_password']."/99999999");

        $response->assertStatus($this->data['user_not_exists']['expected_status'])
            ->assertJsonStructure($this->data['user_not_exists']['expected_structure']);
    }


    public function test_send_new_password_success()
    {
        $user = User::factory()->create();

        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['send_new_password']."/".$user->id);

        $user_after = User::where('id','=',$user->id)->first();

        $this->assertNotEquals($user_after->password,$user->password);
        $response->assertStatus($this->data['send_new_password_success']['expected_status'])
            ->assertJsonStructure($this->data['send_new_password_success']['expected_structure']);
    }




}
