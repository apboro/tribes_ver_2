<?php

namespace Tests\Feature\Api\v3;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiUserLoginTest extends TestCase
{
    use WithFaker;

    private $url = 'api/v3/user/login';

    private $data = [
        'empty_email' => [
            'email' => '',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ],
        ],
        'email_not_valid' => [
            'email' => 'test',
            'password' => '123456',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ],
        ],
        'empty_password' => [
            'email' => 'test@test.com',
            'password' => '',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ],
        ],
        'bad_credential' => [
            'email' => '',
            'password' => 'test',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ],
        ],
        'success' => [
            'email' => '',
            'password' => '',
            'expected_status' => 200,
            'expected_structure' => [
            ],
        ],
    ];

    public function test_auth_login_error()
    {
        $response = $this->post($this->url, $this->data['empty_email']);

        $response
            ->assertStatus($this->data['empty_email']['expected_status'])
            ->assertJsonStructure($this->data['empty_email']['expected_structure']);
    }

    public function test_auth_login_error_method()
    {
        $response = $this->get($this->url);

        $response->assertStatus(405);
    }

    public function test_auth_email_not_valid()
    {
        $response = $this->post($this->url, $this->data['email_not_valid']);
        $response
            ->assertStatus($this->data['email_not_valid']['expected_status'])
            ->assertJsonStructure($this->data['email_not_valid']['expected_structure']);
    }

    public function test_auth_empty_password()
    {
        $response = $this->post($this->url, $this->data['empty_password']);

        $response
            ->assertStatus($this->data['empty_password']['expected_status'])
            ->assertJsonStructure($this->data['empty_password']['expected_structure']);
    }

    public function test_auth_login_bad_credential()
    {
        $this->data['bad_credential']['email'] = $this->custom_user->email;
        $response = $this->post($this->url, $this->data['bad_credential']);

        $response
            ->assertStatus($this->data['bad_credential']['expected_status'])
            ->assertJsonStructure($this->data['bad_credential']['expected_structure']);
    }

    public function test_auth_login_success()
    {
        $password = '123456';
        $this->createUserForTest(
            [
                'password'=> $password
            ]
        );


        $this->data['success']['email'] = $this->custom_user->email;
        $this->data['success']['password'] = $password;

        $response = $this->post($this->url, $this->data['success']);

        $response
            ->assertStatus($this->data['success']['expected_status'])
            ->assertJsonStructure($this->data['success']['expected_structure']);
    }

    public function test_auth_logout()
    {

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post('api/v3/user/logout');

        $response->assertStatus(200);
    }

}
