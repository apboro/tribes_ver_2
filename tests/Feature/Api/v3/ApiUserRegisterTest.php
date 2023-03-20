<?php

namespace Tests\Feature\Api\v3;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiUserRegisterTest extends TestCase
{
    use WithFaker;

    private $url = 'api/v3/user/register';

    private $data = [
        'empty_data'=>[
            'email'=>'',
            'name'=>'',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ]
        ],
        'empty_email'=>[
            'email'=>'',
            'name'=>'test',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ]
        ],
        'invalid_email'=>[
            'email'=>'test',
            'name'=>'',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ]
        ],
        'email_already_exists'=>[
            'email'=>'',
            'name'=>'test',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ]
        ],
        'name_length_error'=>[
            'email'=>'',
            'name'=>'test',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ]
        ],
        'success'=>[
            'email'=>'',
            'name'=>'test',
            'expected_status' => 200,
            'expected_structure' => [
                'data'=>[
                    'token'
                ],
            ]
        ]
    ];

    public function test_register_error_empty_request(){
        $response = $this->post($this->url,$this->data['empty_data']);
        $response->assertStatus($this->data['empty_data']['expected_status'])
            ->assertJsonStructure($this->data['empty_data']['expected_structure']);
    }

    public function test_register_error_empty_email(){
        $response = $this->post($this->url,$this->data['empty_email']);
        $response->assertStatus($this->data['empty_email']['expected_status'])
            ->assertJsonStructure($this->data['empty_email']['expected_structure']);
    }

    public function test_register_error_invalid_email(){
        $response = $this->post($this->url,$this->data['invalid_email']);
        $response->assertStatus($this->data['invalid_email']['expected_status'])
            ->assertJsonStructure($this->data['invalid_email']['expected_structure']);
    }

    public function test_register_email_already_exists()
    {
        $this->data['email_already_exists']['email'] = $this->custom_user->email;
        $response = $this->post($this->url,$this->data['email_already_exists']);
        $response->assertStatus($this->data['email_already_exists']['expected_status'])
            ->assertJsonStructure($this->data['email_already_exists']['expected_structure']);
    }

    public function test_register_name_length_error()
    {
        $str = $this->faker->regexify('[A-Za-z]{50}');
        $this->data['name_length_error']['name'] = $str;
        $this->data['name_length_error']['email'] = $this->faker->unique()->safeEmail();
        $response = $this->post($this->url,$this->data['name_length_error']);
        $response->assertStatus($this->data['name_length_error']['expected_status'])
            ->assertJsonStructure($this->data['name_length_error']['expected_structure']);
    }

    public function test_register_success()
    {
        $this->data['success']['email'] = $this->faker->unique()->safeEmail();
        $response = $this->post($this->url,$this->data['success']);

        $response->assertStatus($this->data['success']['expected_status'])
            ->assertJsonStructure($this->data['success']['expected_structure']);
    }
}

