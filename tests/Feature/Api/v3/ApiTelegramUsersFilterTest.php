<?php

namespace Tests\Feature\Api\v3;

use Database\Seeders\TelegramUsersCommunitySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class ApiTelegramUsersFilterTest extends TestCase
{
    private $url = [
        'filter_telegram_users' => 'api/v3/user/community-users/filter',
    ];

    private $data = [
        'error_not_auth' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ],
        ],
        'validation_error' => [
            'accession_date_from'=>'',
            'accession_date_to'=>'',
            'name'=>'',
            'community_id'=>'',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ],
        ],
        'get_list_success' => [
            'expected_status' => 200,
            'expected_structure' => [
                'data' => [
                    [
                        'telegram_id' ,
                        'name',
                        'last_name',
                        'user_name',
                        'accession_date',
                        'communities'=>[]
                    ],
                ],
            ],
        ],
    ];
/*
    public function test_filter_list_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->url['filter_telegram_users']);

        $response->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);
    }

    public function test_filter_list_date_from_error()
    {
        $this->data['validation_error']['accession_date_from']='test';

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['filter_telegram_users'],$this->data['validation_error']);

        $response->assertStatus($this->data['validation_error']['expected_status'])
            ->assertJsonStructure($this->data['validation_error']['expected_structure']);
    }

    public function test_filter_list_date_to_error()
    {
        $this->data['validation_error']['accession_date_to']='test';
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['filter_telegram_users'],$this->data['validation_error']);

        $response->assertStatus($this->data['validation_error']['expected_status'])
            ->assertJsonStructure($this->data['validation_error']['expected_structure']);
    }

    public function test_filter_list_community_id_error()
    {
        $this->data['validation_error']['community_id']='test';
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['filter_telegram_users'],$this->data['validation_error']);

        $response->assertStatus($this->data['validation_error']['expected_status'])
            ->assertJsonStructure($this->data['validation_error']['expected_structure']);
    }

    public function test_filter_list_by_name_success()
    {
        $random_name = Str::random(20);
        $this->createTelegramUserForTest(['user_name'=>$random_name]);

        $this->data['get_list_success']['name'] = substr($random_name,0,10);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['filter_telegram_users'],$this->data['get_list_success']);

        $response->assertStatus($this->data['get_list_success']['expected_status'])
            ->assertJsonStructure($this->data['get_list_success']['expected_structure']);
    }
*/
    public function test_filter_list_by_date_success()
    {

        $this->createUserForTest();
        $seeder_instance = new TelegramUsersCommunitySeeder($this->custom_user->id);
        $seeder_instance->run();

        $random_name = Str::random(20);
        $this->createTelegramUserForTest(['user_name'=>$random_name]);

        /*$this->data['get_list_success']['accession_date_from'] = '2023-01-01';
        $this->data['get_list_success']['accession_date_to'] = '2022-01-01';*/
        //$this->data['get_list_success']['name'] = 'test';
        $this->data['get_list_success']['name'] = 'Нелли';

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['filter_telegram_users'],$this->data['get_list_success']);
        dd($response->json());
        $response->assertStatus($this->data['get_list_success']['expected_status'])
            ->assertJsonStructure($this->data['get_list_success']['expected_structure']);
    }
}
