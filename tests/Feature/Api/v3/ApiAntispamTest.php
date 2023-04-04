<?php

namespace Tests\Feature\Api\v3;

use App\Models\Antispam;
use Illuminate\Support\Str;
use Tests\TestCase;

class ApiAntispamTest extends TestCase
{
    private $url = [
        'add_antispam_rules' => 'api/v3/antispam',
    ];

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
            ],
        ],
        'success' => [
            'expected_status' => 200,
            'expected_structure' => [
                'message',
            ],
        ],
        'list_success' => [
            'expected_status' => 200,
            'expected_structure' => [
                'data' => [
                    [
                        'name',
                        'del_message_with_link',
                        'ban_user_contain_link',
                        'del_message_with_forward',
                        'ban_user_contain_forward',
                        'work_period'
                    ]
                ],
            ],
        ],
    ];


    public function test_store_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->url['add_antispam_rules']);


        $response->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);
    }

    public function test_store_not_valid_name()
    {
        $this->data['validation_error']['name'] = Str::random(150);
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['add_antispam_rules'], $this->data['validation_error']);


        $response->assertStatus($this->data['validation_error']['expected_status'])
            ->assertJsonStructure($this->data['validation_error']['expected_structure']);
    }


    public function test_store_empty_name()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['add_antispam_rules'], $this->data['validation_error']);


        $response->assertStatus($this->data['validation_error']['expected_status'])
            ->assertJsonStructure($this->data['validation_error']['expected_structure']);
    }

    public function test_store_success()
    {
        $this->data['success']['name'] = Str::random(50);
        $this->data['success']['del_message_with_link'] = true;
        $this->data['success']['ban_user_contain_link'] = true;
        $this->data['success']['del_message_with_forward'] = false;
        $this->data['success']['ban_user_contain_forward'] = true;
        $this->data['success']['work_period'] = 1000;
        $this->data['success']['community_ids'][] = $this->custom_community->id;
        $this->createCommunityForTest();
        $this->data['success']['community_ids'][] = $this->custom_community->id;

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['add_antispam_rules'], $this->data['success']);

        $this->assertDatabaseHas('antispams',
            [
                'name' => $this->data['success']['name'],
                'ban_user_contain_link' => true,
                'ban_user_contain_forward' => false
            ]);
        $response->assertStatus($this->data['success']['expected_status'])
            ->assertJsonStructure($this->data['success']['expected_structure']);
    }

    public function test_edit_success()
    {
        $antispam = Antispam::create([
            'owner' => $this->custom_user->id,
            'name' => Str::random(50),
            'del_message_with_link' => false,
            'ban_user_contain_link' => false,
            'del_message_with_forward' => false,
            'ban_user_contain_forward' => false,
            'work_period' => 100
        ]);
        $this->data['success']['name'] = Str::random(70);
        $this->data['success']['del_message_with_link'] = true;
        $this->data['success']['ban_user_contain_link'] = true;
        $this->data['success']['del_message_with_forward'] = false;
        $this->data['success']['ban_user_contain_forward'] = true;
        $this->data['success']['work_period'] = 1000;
        $this->data['success']['community_ids'][] = $this->custom_community->id;
        $this->createCommunityForTest();
        $this->data['success']['community_ids'][] = $this->custom_community->id;

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->put($this->url['add_antispam_rules'] . '/' . $antispam->id, $this->data['success']);

        $this->assertDatabaseHas('antispams',
            [
                'name' => $this->data['success']['name'],
                'ban_user_contain_link' => true,
                'ban_user_contain_forward' => false
            ]);
        $response->assertStatus($this->data['success']['expected_status'])
            ->assertJsonStructure($this->data['success']['expected_structure']);
    }

    public function test_list_success()
    {
        for ($i = 0; $i < 27; $i++) {
            Antispam::create([
                'owner' => $this->custom_user->id,
                'name' => Str::random(50),
                'del_message_with_link' => false,
                'ban_user_contain_link' => false,
                'del_message_with_forward' => false,
                'ban_user_contain_forward' => false,
                'work_period' => 100
            ]);
        }

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['add_antispam_rules']);

        $response->assertStatus($this->data['list_success']['expected_status'])
            ->assertJsonStructure($this->data['list_success']['expected_structure']);
    }


}
