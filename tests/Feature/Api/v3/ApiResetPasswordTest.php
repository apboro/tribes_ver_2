<?php

namespace Tests\Feature\Api\v3;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ApiResetPasswordTest extends TestCase
{
    use WithFaker;

    private $url = 'api/v3/reset-password';
    private $data = [
        'empty_data' => [
            'email' => '',
            'password' => '',
            'password_confirmation',
            'token' => '',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'payload',
                'errors'
            ]
        ],
        'not_valid_email' => [
            'email' => 'test',
            'password' => '123456',
            'password_confirmation' => '123456',
            'token' => '123',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'payload',
                'errors'
            ]
        ],
        'empty_password' => [
            'email' => 'test@test.com',
            'password' => '',
            'password_confirmation' => '123456',
            'token' => '123',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'payload',
                'errors'
            ]
        ],
        'error_password_confirmation' => [
            'email' => 'test',
            'password' => '123456',
            'password_confirmation' => '',
            'token' => '123',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'payload',
                'errors'
            ]
        ],
        'user_dosent_exists' => [
            'email' => 'test',
            'password' => '123456',
            'password_confirmation' => '123456',
            'token' => '123',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'payload',
                'errors'
            ]
        ],
        'invalid_token' => [
            'email' => '',
            'password' => '123456',
            'password_confirmation' => '123456',
            'token' => '123',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'payload',
                'errors'
            ]
        ],
        'success'=>[
            'email' => '',
            'password' => '123456',
            'password_confirmation' => '123456',
            'token' => '',
            'expected_status' => 200,
            'expected_structure' => [
                'message',
                'payload'=>[
                    'token'
                ],
            ]
        ]
    ];

    public function test_reset_password_error()
    {
        $response = $this->post($this->url,
            $this->data['empty_data']
        );
        $response->assertStatus($this->data['empty_data']['expected_status'])
            ->assertJsonStructure($this->data['empty_data']['expected_structure']);
    }

    public function test_reset_password_error_email_not_valid()
    {
        $response = $this->post($this->url, $this->data['not_valid_email']);
        $response->assertStatus($this->data['not_valid_email']['expected_status'])
            ->assertJsonStructure($this->data['empty_data']['expected_structure']);
    }

    public function test_reset_password_error_empty_password()
    {
        $response = $this->post($this->url, $this->data['empty_password']);
        $response->assertStatus($this->data['empty_password']['expected_status'])
            ->assertJsonStructure($this->data['empty_data']['expected_structure']);
    }

    public function test_reset_password_error_password_confirmation()
    {
        $user = User::factory()->create();
        $this->data['error_password_confirmation']['email'] = $user->email;
        $response = $this->post($this->url, $this->data['error_password_confirmation']);
        $response->assertStatus($this->data['error_password_confirmation']['expected_status'])
            ->assertJsonStructure($this->data['empty_data']['expected_structure']);
    }

    public function test_reset_password_error_user_dosent_exists()
    {
        $this->data['user_dosent_exists']['email'] = $this->faker->unique()->safeEmail();
        $response = $this->post($this->url, $this->data);
        $response->assertStatus($this->data['user_dosent_exists']['expected_status'])
            ->assertJsonStructure($this->data['empty_data']['expected_structure']);
    }

    public function test_reset_password_error_invalid_token()
    {
        $user = User::factory()->create();
        $this->data['invalid_token']['email'] = $user->email;
        $response = $this->post($this->url, $this->data['invalid_token']);
        $response->assertStatus($this->data['invalid_token']['expected_status'])
            ->assertJsonStructure($this->data['invalid_token']['expected_structure']);
    }

    public function test_reset_password_success()
    {
        $user = User::create([
            'name'=>'test',
            'email'=>$this->faker->unique()->safeEmail(),
            'password'=>bcrypt('123456789'),
            'phone_confirmed'=>false
        ]);

        $token = Password::broker()->createToken($user);
        $this->data['success']['email'] = $user->email;
        $this->data['success']['token'] = $token;

        $response = $this->post($this->url, $this->data['success']);

        $updated_data = User::where('email','=',$user->email)->first();

        $this->assertTrue(Hash::check($this->data['success']['password'], $updated_data->password));

        $response->assertStatus($this->data['success']['expected_status'])
            ->assertJsonStructure($this->data['success']['expected_structure']);

    }

}
