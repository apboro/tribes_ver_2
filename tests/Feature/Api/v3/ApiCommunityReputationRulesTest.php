<?php

namespace Tests\Feature\Api\v3;

use App\Models\CommunityReputationRules;
use Tests\TestCase;

class ApiCommunityReputationRulesTest extends TestCase
{
    private $url ='api/v3/chats/rate';

    private $data = [
        'error_not_auth' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ]
        ],
        'validation_error' => [
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ]
        ],
        'success_list'=>[
            'expected_status' => 200,
            'expected_structure' => [
                'data'=>[[
                    'name',
                    'who_can_rate',

                    'rate_period',
                    'rate_member_period',
                    'rate_reset_period',

                    'notify_about_rate_change',
                    'notify_type',
                    'notify_period',
                    'notify_content_chat',
                    'notify_content_user',

                    'public_rate_in_chat',
                    'type_public_rate_in_chat',
                    'rows_public_rate_in_chat',
                    'text_public_rate_in_chat',
                    'period_public_rate_in_chat',

                    'count_for_new',
                    'start_count_for_new',
                    'count_reaction',
                    ]
                ]
            ]
        ],
        'success'=>[
            'expected_status' => 200,
            'expected_structure' => [
                'data'=>[
                    'name',
                    'who_can_rate',

                    'rate_period',
                    'rate_member_period',
                    'rate_reset_period',

                    'notify_about_rate_change',
                    'notify_type',
                    'notify_period',
                    'notify_content_chat',
                    'notify_content_user',

                    'public_rate_in_chat',
                    'type_public_rate_in_chat',
                    'rows_public_rate_in_chat',
                    'text_public_rate_in_chat',
                    'period_public_rate_in_chat',

                    'count_for_new',
                    'start_count_for_new',
                    'count_reaction',
                ]
            ]
        ]
    ];

    public function test_rate_create_error_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->url);

        $response->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);
    }

    public function test_rate_create_error_empty_data()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token
        ])->post($this->url,$this->data['validation_error']);

        $response->assertStatus($this->data['validation_error']['expected_status'])
            ->assertJsonStructure($this->data['validation_error']['expected_structure']);
    }

    public function test_rate_create_success()
    {
        $this->data['success']['data'] = [
            'name'=>'test',
            'who_can_rate'=>'all',
            'rate_period' => 100,
            'rate_member_period' => 100,
            'rate_reset_period' => 1000,

            'notify_about_rate_change' => false,
            'notify_type' => 'all',
            'notify_period' => 1,
            'notify_content_chat' => 'test test test',
            'notify_content_user' => 'test test test',

            'public_rate_in_chat' => false,
            'type_public_rate_in_chat' => 1,
            'rows_public_rate_in_chat' => 10,
            'text_public_rate_in_chat' => 'test 123',
            'period_public_rate_in_chat' => 12,

            'count_for_new' => 1,
            'start_count_for_new' => 10,
            'count_reaction' => 1,
            'keyword_rate_up'=>[
                'test +1',
                'test +2'
            ],
            'keyword_rate_down'=>[
                'test -1',
                'test -2'
            ],
            'community_ids'=>[
                $this->custom_community->id
            ]
        ];
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token
        ])->post($this->url,$this->data['success']['data']);

        $this->assertDatabaseHas('community_reputation_rules',['user_id'=>$this->custom_user->id]);
        $response->assertStatus($this->data['success']['expected_status'])
            ->assertJsonStructure($this->data['success']['expected_structure']);
    }


    public function test_rate_update_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->put($this->url."/123");

        $response->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);
    }

    public function test_rate_update_not_exists()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token
        ])->put($this->url."/999999");

        $response->assertStatus($this->data['validation_error']['expected_status'])
            ->assertJsonStructure($this->data['validation_error']['expected_structure']);
    }


    public function test_rate_update_empty_data()
    {

        $community_reputation_rule = CommunityReputationRules::create([
            'name'=>'test2',
            'user_id'=>$this->custom_user->id,
            'who_can_rate'=>'all',
            'rate_period' => 100,
            'rate_member_period' => 100,
            'rate_reset_period' => 1000,

            'notify_about_rate_change' => false,
            'notify_type' => 'all',
            'notify_period' => 1,
            'notify_content_chat' => 'test test test',
            'notify_content_user' => 'test test test',

            'public_rate_in_chat' => false,
            'type_public_rate_in_chat' => 1,
            'rows_public_rate_in_chat' => 10,
            'text_public_rate_in_chat' => 'test 123',
            'period_public_rate_in_chat' => 12,

            'count_for_new' => 1,
            'start_count_for_new' => 10,
            'count_reaction' => 1,
        ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token
        ])->put($this->url."/".$community_reputation_rule->id);

        $response->assertStatus($this->data['validation_error']['expected_status'])
            ->assertJsonStructure($this->data['validation_error']['expected_structure']);
    }

    public function test_rate_update_success()
    {
        $community_reputation_rule = CommunityReputationRules::create([
            'name'=>'test2',
            'user_id'=>$this->custom_user->id,
            'who_can_rate'=>'all',
            'rate_period' => 100,
            'rate_member_period' => 100,
            'rate_reset_period' => 1000,

            'notify_about_rate_change' => false,
            'notify_type' => 'all',
            'notify_period' => 1,
            'notify_content_chat' => 'test test test',
            'notify_content_user' => 'test test test',

            'public_rate_in_chat' => false,
            'type_public_rate_in_chat' => 1,
            'rows_public_rate_in_chat' => 10,
            'text_public_rate_in_chat' => 'test 123',
            'period_public_rate_in_chat' => 12,

            'count_for_new' => 1,
            'start_count_for_new' => 10,
            'count_reaction' => 1,
        ]);

        $this->data['success']['data'] = [
            'name'=>'test2333',
            'user_id'=>$this->custom_user->id,
            'who_can_rate'=>'all',
            'rate_period' => 100,
            'rate_member_period' => 100,
            'rate_reset_period' => 1000,

            'notify_about_rate_change' => false,
            'notify_type' => 'all',
            'notify_period' => 1,
            'notify_content_chat' => 'test test test',
            'notify_content_user' => 'test test test',

            'public_rate_in_chat' => false,
            'type_public_rate_in_chat' => 1,
            'rows_public_rate_in_chat' => 10,
            'text_public_rate_in_chat' => 'test 123',
            'period_public_rate_in_chat' => 12,

            'count_for_new' => 1,
            'start_count_for_new' => 10,
            'count_reaction' => 1,
            'keyword_rate_up'=>[
                'test +1',
                'test +2'
            ],
            'keyword_rate_down'=>[
                'test -1',
                'test -2'
            ],
            'community_ids'=>[
                $this->custom_community->id
            ]
        ];
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token
        ])->put($this->url."/".$community_reputation_rule->id,
                    $this->data['success']['data']
        );

        $this->assertDatabaseHas('community_reputation_rules',['user_id'=>$this->custom_user->id]);
        $response->assertStatus($this->data['success']['expected_status'])
            ->assertJsonStructure($this->data['success']['expected_structure']);
    }

    public function test_rate_show_error_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url."/123");

        $response->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);
    }


    public function test_rate_show_not_exists()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token
        ])->get($this->url."/99999");

        $response->assertStatus($this->data['validation_error']['expected_status'])
            ->assertJsonStructure($this->data['validation_error']['expected_structure']);
    }

    public function test_rate_show_success()
    {

        $community_reputation_rule = CommunityReputationRules::create([
            'name'=>'test2',
            'user_id'=>$this->custom_user->id,
            'who_can_rate'=>'all',
            'rate_period' => 100,
            'rate_member_period' => 100,
            'rate_reset_period' => 1000,

            'notify_about_rate_change' => false,
            'notify_type' => 'all',
            'notify_period' => 1,
            'notify_content_chat' => 'test test test',
            'notify_content_user' => 'test test test',

            'public_rate_in_chat' => false,
            'type_public_rate_in_chat' => 1,
            'rows_public_rate_in_chat' => 10,
            'text_public_rate_in_chat' => 'test 123',
            'period_public_rate_in_chat' => 12,

            'count_for_new' => 1,
            'start_count_for_new' => 10,
            'count_reaction' => 1,
        ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token
        ])->get($this->url."/".$community_reputation_rule->id);

        $response->assertStatus($this->data['success']['expected_status'])
            ->assertJsonStructure($this->data['success']['expected_structure']);
    }


    public function test_rate_show_list_error_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url);

        $response->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);
    }


    public function test_rate_show_list_success()
    {
        for($i=0;$i<10;$i++){
             CommunityReputationRules::create([
                'name'=>'test2',
                'user_id'=>$this->custom_user->id,
                'who_can_rate'=>'all',
                'rate_period' => 100,
                'rate_member_period' => 100,
                'rate_reset_period' => 1000,

                'notify_about_rate_change' => false,
                'notify_type' => 'all',
                'notify_period' => 1,
                'notify_content_chat' => 'test test test',
                'notify_content_user' => 'test test test',

                'public_rate_in_chat' => false,
                'type_public_rate_in_chat' => 1,
                'rows_public_rate_in_chat' => 10,
                'text_public_rate_in_chat' => 'test 123',
                'period_public_rate_in_chat' => 12,

                'count_for_new' => 1,
                'start_count_for_new' => 10,
                'count_reaction' => 1,
            ]);
        }
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token
        ])->get($this->url);

        $response->assertStatus($this->data['success_list']['expected_status'])
            ->assertJsonStructure($this->data['success_list']['expected_structure']);
    }
}
