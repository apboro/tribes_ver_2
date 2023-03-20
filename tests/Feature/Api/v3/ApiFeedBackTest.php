<?php

namespace Tests\Feature\Api\v3;

use Tests\TestCase;

class ApiFeedBackTest extends TestCase
{
    private $url = [
        'create_feed_back' => 'api/v3/feed-back',
    ];

    private $data = [
        'error_not_auth' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ]
        ],
        'error_empty_data' => [
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ]
        ],
        'email_not_valid'=>[
            'fb_email'=>'test',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ]
        ],
        'empty_name'=>[
            'fb_email'=>'',
            'fb_message'=>'test message',
            'fb_name'=>'',
            'fb_phone'=>'123456',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ]
        ],
        'empty_phone'=>[
            'fb_email'=>'',
            'fb_message'=>'test message',
            'fb_name'=>'test',
            'fb_phone'=>'',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ]
        ],
        'create_success'=>[
            'fb_email'=>'',
            'fb_message'=>'test message',
            'fb_name'=>'test',
            'fb_phone'=>'123456',
            'expected_status' => 200,
            'expected_structure' => [
            ]
        ]
    ];

    public function test_feed_back_create_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->url['create_feed_back']);

        $response->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);
    }

    public function test_feed_back_create_empty_data()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token
        ])->post($this->url['create_feed_back'],$this->data['error_empty_data']);

        $response->assertStatus($this->data['error_empty_data']['expected_status'])
            ->assertJsonStructure($this->data['error_empty_data']['expected_structure']);
    }

    public function test_feed_back_create_email_not_valied()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token
        ])->post($this->url['create_feed_back'],$this->data['email_not_valid']);
        $response->assertStatus($this->data['email_not_valid']['expected_status'])
            ->assertJsonStructure($this->data['email_not_valid']['expected_structure']);
    }

    public function test_feed_back_empty_name()
    {
        $this->data['empty_name']['fb_email'] = $this->custom_user->email;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token
        ])->post($this->url['create_feed_back'],$this->data['empty_name']);

        $response->assertStatus($this->data['empty_name']['expected_status'])
            ->assertJsonStructure($this->data['empty_name']['expected_structure']);
    }

    public function test_feed_back_empty_phone()
    {
        $this->data['empty_name']['fb_email'] = $this->custom_user->email;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token
        ])->post($this->url['create_feed_back'],$this->data['empty_phone']);
        $response->assertStatus($this->data['empty_phone']['expected_status'])
            ->assertJsonStructure($this->data['empty_phone']['expected_structure']);
    }

    public function test_feed_back_create_success()
    {
        $this->data['create_success']['fb_email'] = $this->custom_user->email;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token
        ])->post($this->url['create_feed_back'],$this->data['create_success']);
        $response->assertStatus($this->data['create_success']['expected_status'])
            ->assertJsonStructure($this->data['create_success']['expected_structure']);
    }

}
