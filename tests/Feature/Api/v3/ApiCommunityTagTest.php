<?php

namespace Tests\Feature\Api\v3;

use App\Models\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiCommunityTagTest extends TestCase
{
    private $url = [
        'attach_tag' => 'api/v3/chats-tags/attach',
        'detach_tag' => 'api/v3/chats-tags/detach',
    ];

    private $data = [
        'error_not_auth' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ],
        ],
        'error_empty_tag_id' => [
            'tag_id' => '',
            'community_id' => '123',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ],
        ],
        'error_not_valid_tag_id' => [
            'tag_id' => 'test',
            'community_id' => '123',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ],
        ],
        'error_empty_community_id' => [
            'tag_id' => '123',
            'community_id' => '',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ],
        ],
        'error_not_valid_community_id' => [
            'tag_id' => '123',
            'community_id' => 'test',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ],
        ],
        'error_not_attached'=>[
            'tag_id' => 9999999,
            'community_id' => 999999,
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ],
        ],
        'attach_success'=>[
            'expected_status' => 200,
            'expected_structure' => [
                'message',
            ],
        ],
    ];

    public function test_attach_tag_to_community_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->url['attach_tag']);

        $response->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);
    }

    public function test_attach_tag_to_community_empty_tag_id_error(){
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['attach_tag'],$this->data['error_empty_tag_id']);

        $response->assertStatus($this->data['error_empty_tag_id']['expected_status'])
            ->assertJsonStructure($this->data['error_empty_tag_id']['expected_structure']);
    }

    public function test_attach_tag_to_community_not_valid_tag_id_error(){
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['attach_tag'],$this->data['error_not_valid_tag_id']);

        $response->assertStatus($this->data['error_not_valid_tag_id']['expected_status'])
            ->assertJsonStructure($this->data['error_not_valid_tag_id']['expected_structure']);
    }

    public function test_attach_tag_to_community_empty_community_id_error(){
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['attach_tag'],$this->data['error_empty_community_id']);

        $response->assertStatus($this->data['error_empty_community_id']['expected_status'])
            ->assertJsonStructure($this->data['error_empty_community_id']['expected_structure']);
    }

    public function test_attach_tag_to_community_not_valid_community_id_error(){
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['attach_tag'],$this->data['error_not_valid_community_id']);

        $response->assertStatus($this->data['error_not_valid_community_id']['expected_status'])
            ->assertJsonStructure($this->data['error_not_valid_community_id']['expected_structure']);
    }

    public function test_attach_tag_success(){

        $tag = Tag::create([
            'user_id'=>$this->custom_user->id,
            'name'=>'test'
        ]);

        $this->data['attach_success']['community_id'] = $this->custom_community->id;
        $this->data['attach_success']['tag_id'] = $tag->id;

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['attach_tag'],$this->data['attach_success']);
        $response->assertStatus($this->data['attach_success']['expected_status'])
            ->assertJsonStructure($this->data['attach_success']['expected_structure']);
    }

    public function test_detach_tag_to_community_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->url['detach_tag']);

        $response->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);
    }

    public function test_detach_tag_to_community_empty_tag_id_error(){
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['detach_tag'],$this->data['error_empty_tag_id']);

        $response->assertStatus($this->data['error_empty_tag_id']['expected_status'])
            ->assertJsonStructure($this->data['error_empty_tag_id']['expected_structure']);
    }

    public function test_detach_tag_to_community_not_valid_tag_id_error(){
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['detach_tag'],$this->data['error_not_valid_tag_id']);

        $response->assertStatus($this->data['error_not_valid_tag_id']['expected_status'])
            ->assertJsonStructure($this->data['error_not_valid_tag_id']['expected_structure']);
    }

    public function test_detach_tag_to_community_empty_community_id_error(){
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['detach_tag'],$this->data['error_empty_community_id']);

        $response->assertStatus($this->data['error_empty_community_id']['expected_status'])
            ->assertJsonStructure($this->data['error_empty_community_id']['expected_structure']);
    }

    public function test_detach_tag_to_community_not_valid_community_id_error(){
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['detach_tag'],$this->data['error_not_valid_community_id']);

        $response->assertStatus($this->data['error_not_valid_community_id']['expected_status'])
            ->assertJsonStructure($this->data['error_not_valid_community_id']['expected_structure']);
    }

    public function test_detach_tag_to_community_not_attached(){
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['detach_tag'],$this->data['error_not_attached']);

        $response->assertStatus($this->data['error_not_attached']['expected_status'])
            ->assertJsonStructure($this->data['error_not_attached']['expected_structure']);
    }

    public function test_detach_tag_success(){

        $tag = Tag::create([
            'user_id'=>$this->custom_user->id,
            'name'=>'test'
        ]);

        $this->data['attach_success']['community_id'] = $this->custom_community->id;
        $this->data['attach_success']['tag_id'] = $tag->id;
        $this->custom_community->tags()->attach($tag);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['detach_tag'],$this->data['attach_success']);

        $response->assertStatus($this->data['attach_success']['expected_status'])
            ->assertJsonStructure($this->data['attach_success']['expected_structure']);
    }

}
