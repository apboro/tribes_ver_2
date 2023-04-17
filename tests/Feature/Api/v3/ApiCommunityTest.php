<?php

namespace Tests\Feature\Api\v3;

use App\Models\Community;
use App\Models\Models\Tag;
use App\Models\TelegramConnection;
use App\Models\TelegramUser;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Tests\TestCase;

class ApiCommunityTest extends TestCase
{
    private $url = [
        'show_community' => 'api/v3/user/chats',
        'create_community' => 'api/v3/user/chats',
        'get_list' => 'api/v3/user/chats',
        'filter'=>'api/v3/communities/filter',
    ];

    private $data = [
        'error_not_auth' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ],
        ],
        'show_community_error_type' => [
            'id' => 'test',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ],
        ],
        'show_community_not_auth_user' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
                
            ],
        ],
        'show_community_success' => [
            'expected_status' => 200,
            'expected_structure' => [
                'data' => [
                    'id',
                    'title',
                    'image',
                    'description',
                    'created_at',
                    'updated_at',
                    'balance',
                    'type',
                    'tags'=>[],
                    'rules'=>[],
                ],
            ],
        ],
        'add_community_not_auth_user' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ],
        ],
        'add_community_empty_hash' => [
            'hash' => '',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ],
        ],
        'not_respond_telegram' => [
            'hash' => '',
            'expected_status' => 400,
            'expected_structure' => [
                'message',
            ],
        ],
        'add_community_success' => [
            'hash' => '',
            'expected_status' => 200,
            'expected_structure' => [
                'data' => [
                    'id',
                    'title',
                    'image',
                    'description',
                ],
            ],
        ],
        'get_list_error_not_auth' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ],
        ],
        'get_list_success' => [
            'expected_status' => 200,
            'expected_structure' => [
                'data' => [
                    [
                        'id',
                        'title',
                        'image',
                        'description',
                        'created_at',
                        'updated_at',
                        'balance',
                        'tags'=>[]
                    ],
                ],
            ],
        ],
        'get_list_filter_success'=>[
            'expected_status' => 200,
            'expected_structure' => [
                'data' => [
                    [
                        'id',
                        'connection_id',
                        'owner',
                        'title',
                        'image',
                        'description',
                        'created_at',
                        'updated_at',
                        'hash',
                        'balance',
                        'project_id',
                        'donate',
                        'tags'=>[]
                    ],
                ],
            ],
        ],
        'get_list_filter_name_success'=>[
            'expected_status' => 200,
            'expected_structure' => [
                'data' => [
                    [
                        'id',
                        'connection_id',
                        'title',
                        'image',
                        'description',
                        'created_at',
                        'updated_at',
                        'hash',
                        'balance',
                        'project_id',
                        'donate',
                        'tags'=>[]
                    ],
                ],
            ],
        ],
        'get_list_filter_date_error'=>[
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ],
        ]
    ];

    public function test_show_community_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['show_community'] . "/99999999999");
        $response->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);
    }

    public function test_show_community_error_type()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['show_community'] . '/' . $this->data['show_community_error_type']['id']);
        $response->assertStatus($this->data['show_community_error_type']['expected_status'])
            ->assertJsonStructure($this->data['show_community_error_type']['expected_structure']);
    }

    public function test_show_community_success()
    {
        $this->createCommunityForTest();
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['show_community'] . '/' . $this->custom_community->id);

        $response->assertStatus($this->data['show_community_success']['expected_status'])
            ->assertJsonStructure($this->data['show_community_success']['expected_structure']);
    }

    public function test_show_community_not_authorize_user()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . 'fake_token',
        ])->get($this->url['show_community'] . '/' . $this->custom_community->id);

        $response->assertStatus($this->data['show_community_not_auth_user']['expected_status'])
            ->assertJsonStructure($this->data['show_community_not_auth_user']['expected_structure']);
    }

    public function test_add_community_not_auth_user()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->url['create_community']);
        $response->assertStatus($this->data['add_community_not_auth_user']['expected_status'])
            ->assertJsonStructure($this->data['add_community_not_auth_user']['expected_structure']);
    }

    public function test_add_community_empty_hash()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['create_community'], $this->data['add_community_empty_hash']);
        $response->assertStatus($this->data['add_community_empty_hash']['expected_status'])
            ->assertJsonStructure($this->data['add_community_empty_hash']['expected_structure']);
    }

    public function test_add_community_not_respond_telegram()
    {
        $this->data['not_respond_telegram']['hash'] = $this->custom_telegram_connection->hash;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['create_community'], $this->data['not_respond_telegram']);
        $response->assertStatus($this->data['not_respond_telegram']['expected_status'])
            ->assertJsonStructure($this->data['not_respond_telegram']['expected_structure']);
    }

    public function test_add_community_success()
    {
        $this->createTelegramConnectionForTest([
            'status' => 'connected',
        ]);
        $this->data['add_community_success']['hash'] = $this->custom_telegram_connection->hash;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['create_community'], $this->data['add_community_success']);

        $telegramm_connection_after = TelegramConnection::where('id', '=', $this->custom_telegram_connection->id)->first();

        $this->assertEquals($telegramm_connection_after->status, 'completed');
        $response->assertStatus($this->data['add_community_success']['expected_status'])
            ->assertJsonStructure($this->data['add_community_success']['expected_structure']);
    }

    public function test_get_list_error_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['get_list']);
        $response->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);
    }

    public function test_get_list_success()
    {
        for ($x = 0; $x < 3; $x++) {
            $this->createTelegramConnectionForTest();
            $this->createCommunityForTest();
        }

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['get_list']);

        $response->assertStatus($this->data['get_list_success']['expected_status'])
            ->assertJsonStructure($this->data['get_list_success']['expected_structure']);
    }

    public function test_filter_community_date_error(){
        for ($z = 0; $z < 5; $z++) {
            $this->createTelegramConnectionForTest();
            $this->createCommunityForTest([
                'status'=>'completed',
                'created_at'=>Carbon::now()->subDays(10)]);
            $this->createCommunityForTest([
                'status'=>'completed',
                'created_at'=>Carbon::now()->subDays(20)
            ]);
        }

        $this->createCommunityForTest([
            'status'=>'completed',
            'created_at'=>Carbon::createFromFormat('Y-m-d', '2023-01-15')]);

        $this->data['get_list_filter_date_error']['date_from'] = 'test';
        $this->data['get_list_filter_date_error']['date_to'] = 'test';

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['filter'],$this->data['get_list_filter_date_error']);

        $response->assertStatus($this->data['get_list_filter_date_error']['expected_status'])
            ->assertJsonStructure($this->data['get_list_filter_date_error']['expected_structure']);
    }

    public function test_filter_community_date_success(){
        for ($z = 0; $z < 5; $z++) {
            $this->createTelegramConnectionForTest();
            $this->createCommunityForTest([
                'status'=>'completed',
                'created_at'=>Carbon::now()->subDays(10)]);
            $this->createCommunityForTest([
                'status'=>'completed',
                'created_at'=>Carbon::now()->subDays(20)
            ]);
        }

        $this->createCommunityForTest([
            'status'=>'completed',
            'created_at'=>Carbon::createFromFormat('Y-m-d', '2023-01-15')]);

        $this->data['get_list_filter_success']['date_from'] = '2023-01-10';
        $this->data['get_list_filter_success']['date_to'] = '2023-01-20';

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['filter'],$this->data['get_list_filter_success']);

        $response->assertStatus($this->data['get_list_filter_success']['expected_status'])
            ->assertJsonStructure($this->data['get_list_filter_success']['expected_structure']);
    }


    public function test_filter_community_by_name(){

        $this->createTelegramConnectionForTest();
        $random_name = Str::random(20);
        $this->createCommunityForTest([
            'status'=>'completed',
            'created_at'=>Carbon::createFromFormat('Y-m-d', '2023-01-15'),
                'title'=>$random_name
            ]
        );

        $this->data['get_list_filter_name_success']['name'] = substr($random_name,0,10);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['filter'],$this->data['get_list_filter_name_success']);

        $response->assertStatus($this->data['get_list_filter_name_success']['expected_status'])
            ->assertJsonStructure($this->data['get_list_filter_name_success']['expected_structure']);
    }


    public function test_filter_community_by_tag_name(){

        $this->createTelegramConnectionForTest();
        $this->createCommunityForTest();

        $random_name = Str::random(20);
        $tag = Tag::create([
            'user_id'=>!empty($parameters['owner']) ? $parameters['owner'] : $this->custom_user->id,
            'name'=>$random_name
        ]);
        $this->custom_community->tags()->attach($tag);


        $this->data['get_list_filter_name_success']['tag_name'] = substr($random_name,0,10);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['filter'],$this->data['get_list_filter_name_success']);

        $response->assertStatus($this->data['get_list_filter_name_success']['expected_status'])
            ->assertJsonStructure($this->data['get_list_filter_name_success']['expected_structure']);
    }

}
