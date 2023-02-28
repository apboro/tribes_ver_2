<?php

namespace Tests\Feature\Api\v3;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApiUserControllerTest extends TestCase
{

    use WithFaker;

    private $url = [
        'get_user_data' => 'api/v3/user',
        'change_password' => 'api/v3/user/password/change',
    ];

    private $data = [
        'empty_data' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ],
        ],
        'success' => [
            'expected_status' => 200,
            'expected_structure' => [
                'message',
                'payload',
                'data' => [
                    'id',
                    'name',
                    'email',
                ],
            ],
        ],
        'password_change' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ],
        ],
        'empty_password_validation' => [
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'payload',
                'errors',
            ],
        ],
        'empty_confirmation_validation' => [
            'password' => '123456test',
            'password_confirmation' => '',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'payload',
                'errors',
            ],
        ],
        'error_password_validation' => [
            'password' => '12345',
            'password_confirmation' => '12345',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'payload',
                'errors',
            ],
        ],
        'change_password_success' => [
            'password' => 'testpassword123456',
            'password_confirmation' => 'testpassword123456',
            'expected_status' => 200,
            'expected_structure' => [
                'message',
                'payload',
            ],
        ],
    ];

    public function test_get_user_data_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['get_user_data']);

        $response->assertStatus($this->data['empty_data']['expected_status'])
            ->assertJsonStructure($this->data['empty_data']['expected_structure']);
    }

    public function test_success()
    {

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['get_user_data']);

        $response->assertStatus($this->data['success']['expected_status'])
            ->assertJsonStructure($this->data['success']['expected_structure']);
    }

    public function test_change_password_empty_request()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->url['change_password']);

        $response->assertStatus($this->data['empty_data']['expected_status'])
            ->assertJsonStructure($this->data['empty_data']['expected_structure']);
    }

    public function test_change_password_empty_password_validation()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['change_password']);

        $response->assertStatus($this->data['empty_password_validation']['expected_status'])
            ->assertJsonStructure($this->data['empty_password_validation']['expected_structure']);
    }

    public function test_change_password_empty_confirmation_validation()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['change_password'], $this->data['empty_confirmation_validation']);

        $response->assertStatus($this->data['empty_confirmation_validation']['expected_status'])
            ->assertJsonStructure($this->data['empty_confirmation_validation']['expected_structure']);
    }

    public function test_change_password_not_validation()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['change_password'], $this->data['error_password_validation']);

        $response->assertStatus($this->data['error_password_validation']['expected_status'])
            ->assertJsonStructure($this->data['error_password_validation']['expected_structure']);
    }

    public function test_change_password_success()
    {

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['change_password'], $this->data['change_password_success']);

        $user_after = User::where('id', '=', $this->custom_user->id)->first();

        $this->assertTrue(Hash::check($this->data['change_password_success']['password'], $user_after->password));

        $response->assertStatus($this->data['change_password_success']['expected_status'])
            ->assertJsonStructure($this->data['change_password_success']['expected_structure']);
    }

}
