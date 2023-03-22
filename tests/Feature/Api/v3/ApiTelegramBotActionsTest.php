<?php

namespace Tests\Feature\Api\v3;

use App\Models\Community;
use App\Models\TelegramBotActionTypes;
use Database\Seeders\TelegramBotActionLogSeeder;
use Database\Seeders\TelegramBotActionTypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiTelegramBotActionsTest extends TestCase
{
    private $url = [
        'bot_log' => 'api/v3/user/bot/action-log',
        'bot_log_filter' => 'api/v3/user/bot/action-log/filter',
    ];

    private $data = [
        'error_not_auth' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ],
        ],
        'get_list_success' => [
            'expected_status' => 200,
            'expected_structure' => [
                'data'=>[
                    [
                        "telegram_user",
                        "community",
                        "event",
                        "action",
                        "done_date",
                        "community_tags"
                    ]
                ]
            ],
        ],
        'error_not_valid_data' => [
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ],
        ],
    ];

    public function test_telegram_action_log_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['bot_log']);

        $response->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);
    }


    public function test_telegram_action_log_success()
    {

        $seeder_instance = new TelegramBotActionLogSeeder();
        $seeder_instance->run();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['bot_log'], $this->data['get_list_success']);

        $response->assertStatus($this->data['get_list_success']['expected_status'])
            ->assertJsonStructure($this->data['get_list_success']['expected_structure']);
    }

    public function test_telegram_action_log_list_not_auth(){
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->url['bot_log_filter']);

        $response->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);
    }

    public function test_telegram_action_log_filter_success(){

        $seeder_instance = new TelegramBotActionLogSeeder();
        $seeder_instance->run();

        $this->data['get_list_success']['action_date_from'] = '2023-01-01';
        $this->data['get_list_success']['action_date_to'] = '2023-12-01';

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['bot_log_filter'],$this->data['get_list_success']);

        $response->assertStatus($this->data['get_list_success']['expected_status'])
            ->assertJsonStructure($this->data['get_list_success']['expected_structure']);
    }

    public function test_telegram_action_log_not_valid_date_from(){

        $this->data['error_not_valid_data']['action_date_from'] = 'test';
        $this->data['error_not_valid_data']['action_date_to'] = '2023-12-01';
        $this->data['error_not_valid_data']['community_id'] = '1';
        $this->data['error_not_valid_data']['tags'] = [1,2,3,4];
        $this->data['error_not_valid_data']['user_name'] = 'test';

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['bot_log_filter'],$this->data['error_not_valid_data']);

        $response->assertStatus($this->data['error_not_valid_data']['expected_status'])
            ->assertJsonStructure($this->data['error_not_valid_data']['expected_structure']);
    }

    public function test_telegram_action_log_not_valid_date_to(){

        $this->data['error_not_valid_data']['action_date_from'] = '2023-01-01';
        $this->data['error_not_valid_data']['action_date_to'] = 'test';
        $this->data['error_not_valid_data']['community_id'] = '1';
        $this->data['error_not_valid_data']['tags'] = [1,2,3,4];
        $this->data['error_not_valid_data']['user_name'] = 'test';

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['bot_log_filter'],$this->data['error_not_valid_data']);

        $response->assertStatus($this->data['error_not_valid_data']['expected_status'])
            ->assertJsonStructure($this->data['error_not_valid_data']['expected_structure']);
    }

    public function test_telegram_action_log_not_valid_tags(){

        $this->data['error_not_valid_data']['action_date_from'] = '2023-01-01';
        $this->data['error_not_valid_data']['action_date_to'] = '2023-12-01';
        $this->data['error_not_valid_data']['community_id'] = '1';
        $this->data['error_not_valid_data']['tags'] = 'test';
        $this->data['error_not_valid_data']['user_name'] = 'test';

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['bot_log_filter'],$this->data['error_not_valid_data']);

        $response->assertStatus($this->data['error_not_valid_data']['expected_status'])
            ->assertJsonStructure($this->data['error_not_valid_data']['expected_structure']);
    }

    public function test_telegram_action_log_not_valid_community_id(){

        $this->data['error_not_valid_data']['action_date_from'] = '2023-01-01';
        $this->data['error_not_valid_data']['action_date_to'] = '2023-12-01';
        $this->data['error_not_valid_data']['community_id'] = 'test';
        $this->data['error_not_valid_data']['tags'] = [1,2,3,4];
        $this->data['error_not_valid_data']['user_name'] = 'test';

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['bot_log_filter'],$this->data['error_not_valid_data']);

        $response->assertStatus($this->data['error_not_valid_data']['expected_status'])
            ->assertJsonStructure($this->data['error_not_valid_data']['expected_structure']);
    }

    public function test_telegram_action_log_community_id_success(){



        $seeder_instance = new TelegramBotActionLogSeeder();
        $seeder_instance->run();
        $community = Community::where('owner','=',$this->custom_user->id)->first();
        $this->data['get_list_success']['community_id'] = $community->id;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['bot_log_filter'],$this->data['get_list_success']);

        $response->assertStatus($this->data['get_list_success']['expected_status'])
            ->assertJsonStructure($this->data['get_list_success']['expected_structure']);
    }

    public function test_telegram_action_log_filter_action(){

        $seeder_instance = new TelegramBotActionLogSeeder();
        $seeder_instance->run();
        $community = Community::where('owner','=',$this->custom_user->id)->first();
        $this->data['get_list_success']['event'] = '1234';
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['bot_log_filter'],$this->data['get_list_success']);

        $response->assertStatus($this->data['get_list_success']['expected_status'])
            ->assertJsonStructure($this->data['get_list_success']['expected_structure']);
    }

}
