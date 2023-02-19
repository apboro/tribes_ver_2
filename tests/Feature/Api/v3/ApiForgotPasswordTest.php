<?php

namespace Tests\Feature\Api\v3;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiForgotPasswordTest extends TestCase
{

    use WithFaker;

    private $url = 'api/v3/forgot-password';
    private $data = [
        'empty_email'=>[
            'email'=>'',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'payload',
                'errors'
            ]
        ],
        'email_not_valid'=>[
            'email'=>'test',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'payload',
                'errors'
            ]
        ],
        'email_not_exists'=>[
            'email'=>'',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'payload',
                'errors'
            ]
        ],
        'success'=>[
            'email'=>'',
            'expected_status' => 200,
            'expected_structure' => [
                'message',
                'payload',
            ]
        ],
    ];

    public function test_forgot_password_empty_email()
    {
        $response = $this->post($this->url,$this->data['empty_email']);
        $response->assertStatus($this->data['empty_email']['expected_status'])
            ->assertJsonStructure($this->data['empty_email']['expected_structure']);
    }


    public function test_forgot_password_email_not_valid()
    {
        $response = $this->post($this->url,$this->data['email_not_valid']);
        $response->assertStatus($this->data['email_not_valid']['expected_status'])
            ->assertJsonStructure($this->data['email_not_valid']['expected_structure']);
    }

    public function test_forgot_password_email_not_exists()
    {
        $this->data['email_not_exists']['email'] = $this->faker->unique()->safeEmail();
        $response = $this->post($this->url,$this->data['email_not_exists']);
        $response->assertStatus($this->data['email_not_exists']['expected_status'])
            ->assertJsonStructure($this->data['email_not_exists']['expected_structure']);
    }

    public function test_forgot_password_success()
    {
        $user = User::factory()->create();
        $this->data['success']['email'] = $user->email;
        $response = $this->post($this->url,$this->data['success']);
        $response->assertStatus($this->data['success']['expected_status'])
            ->assertJsonStructure($this->data['success']['expected_structure']);
    }

}
