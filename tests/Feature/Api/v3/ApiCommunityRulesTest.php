<?php

namespace Tests\Feature\Api\v3;

use App\Models\CommunityRule;
use Illuminate\Support\Str;
use Tests\TestCase;

class ApiCommunityRulesTest extends TestCase
{
    private $url = 'api/v3/chats/rules';

    private $data = [
        'error_not_auth' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ],
        ],
        'validation_error' => [
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ]
        ],
        'show_success' => [
            'expected_status' => 200,
            'expected_structure' => [
                'data' => [
                    "id",
                    "user_id",
                    "name",
                    "content",
                    "warning",
                    "max_violation_times",
                    "action",
                    "warning_file",
                    "communities",
                    "restricted_words"
                ],
            ],
        ],
        'show_list_success' => [
            'expected_status' => 200,
            'expected_structure' => [
                'data' => [[
                    "id",
                    "user_id",
                    "name",
                    "content",
                    "warning",
                    "max_violation_times",
                    "action",
                    "warning_file",
                    "communities",
                    "restricted_words"
                ],
                ]
            ],
        ],
    ];


    public function test_get_community_rule_not_auth()
    {
        $community_rule = CommunityRule::create([
            'user_id' => $this->custom_user->id,
            'name' => 'test',
            'content' => 'test',
            'warning' => 'test',
            'max_violation_times' => 1,
            'action' => '1',
        ]);
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url . "/" . $community_rule->id);

        $response->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);
    }

    public function test_get_community_rule_id_error()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url . "/test");

        $response->assertStatus($this->data['validation_error']['expected_status'])
            ->assertJsonStructure($this->data['validation_error']['expected_structure']);
    }

    public function test_get_community_rule_success()
    {
        $community_rule = CommunityRule::create([
            'user_id' => $this->custom_user->id,
            'name' => 'test',
            'content' => 'test',
            'warning' => 'test',
            'max_violation_times' => 1,
            'action' => '1',
        ]);
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url . "/" . $community_rule->id);

        $response->assertStatus($this->data['show_success']['expected_status'])
            ->assertJsonStructure($this->data['show_success']['expected_structure']);
    }


    public function test_get_community_rule_list_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url);

        $response->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);
    }

    public function test_get_community_rule_list_success()
    {
        for ($i = 0; $i < 3; $i++) {
            CommunityRule::create([
                'user_id' => $this->custom_user->id,
                'name' => 'test' . $i,
                'content' => 'test' . $i,
                'warning' => 'test' . $i,
                'max_violation_times' => 1,
                'action' => '1',
            ]);
        }
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url);

        $response->assertStatus($this->data['show_list_success']['expected_status'])
            ->assertJsonStructure($this->data['show_list_success']['expected_structure']);
    }

    public function test_add_community_rule_not_auth()
    {

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->url);

        $response->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);
    }


    public function test_add_community_rule_empty_name()
    {

        $add_array = [
            'name' => '',
            'content' => 'test',
            'warning' => 'test',
            'max_violation_times' => 1,
            'action' => '1',
            'restricted_words' => [
                'test1',
                'test2'
            ]
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url, $add_array);

        $response->assertStatus($this->data['validation_error']['expected_status'])
            ->assertJsonStructure($this->data['validation_error']['expected_structure']);
    }


    public function test_add_community_rule_success()
    {


        $add_array = [
            'name' => 'testtest',
            'content' => 'test',
            'warning' => 'test',
            'max_violation_times' => 1,
            'action' => '1',
            'restricted_words' => [
                'test1',
                'test2'
            ],
            'community_ids' => [
                $this->custom_community->id
            ]
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url, $add_array);

        $response->assertStatus($this->data['show_success']['expected_status'])
            ->assertJsonStructure($this->data['show_success']['expected_structure']);
        $this->assertDatabaseHas('community_rules', ['name' => 'testtest']);
        $this->assertDatabaseHas('restricted_words', ['word' => 'test1']);
        $this->assertDatabaseHas('restricted_words', ['word' => 'test2']);
        $this->assertDatabaseHas('communities', ['community_rule_id' => $response->json()['data']['id']]);
    }


    public function test_edit_community_rule_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->patch($this->url . "/123");

        $response->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);
    }


    public function test_edit_community_rule_success()
    {
        $community_rule = CommunityRule::create([
            'user_id' => $this->custom_user->id,
            'name' => 'test',
            'content' => 'test',
            'warning' => 'test',
            'max_violation_times' => 1,
            'action' => '1',
        ]);

        $name = Str::random(10);
        $edit_array = [
            'name' => $name,
            'content' => 'test',
            'warning' => 'test',
            'max_violation_times' => 1,
            'action' => '1',
            'restricted_words' => [
                'test1',
                'test2'
            ],
            'community_ids' => [
                $this->custom_community->id
            ]
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->patch($this->url . "/" . $community_rule->id, $edit_array);

        $response->assertStatus($this->data['show_success']['expected_status'])
            ->assertJsonStructure($this->data['show_success']['expected_structure']);
        $this->assertDatabaseHas('community_rules', ['name' => $name]);
        $this->assertDatabaseHas('restricted_words', ['word' => 'test1']);
        $this->assertDatabaseHas('restricted_words', ['word' => 'test2']);
        $this->assertDatabaseHas('communities', ['community_rule_id' => $community_rule->id]);
    }


}
