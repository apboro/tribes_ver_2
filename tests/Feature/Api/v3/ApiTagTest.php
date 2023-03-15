<?php

namespace Tests\Feature\Api\v3;

use App\Models\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class ApiTagTest extends TestCase
{
    private $url = [
        'create_tag' => 'api/v3/tags',
        'show_tag' => 'api/v3/tags',
        'delete_tag' => 'api/v3/tags',
        'list_tag'=>'api/v3/tags',
    ];

    private $data = [
        'error_not_auth' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ],
        ],
        'error_empty_name' => [
            'name' => '',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ],
        ],
        'error_not_exists'=>[
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ],
        ],
        'create_success' => [
            'name' => 'test',
            'expected_status' => 200,
            'expected_structure' => [
                'data'=>[
                    'id',
                    'name'
                ]
            ],
        ],
        'store_name_error'=>[
            'name' => '',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ],
        ],
        'show_tag_success'=>[
            'expected_status' => 200,
            'expected_structure' => [
                'data'=>[
                    'id',
                    'name'
                ]
            ],
        ],
        'show_list_success'=>[
            'expected_status' => 200,
            'expected_structure' => [
                'data'=>[[
                    'id',
                    'name'
                ]
                ]
            ],
        ],
        'delete_success'=>[
            'expected_status' => 200,
            'expected_structure' => [
                'message',
            ],
        ]
    ];

    public function test_tag_store_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->url['create_tag']);
        $response->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);
    }

    public function test_tag_store_empty_name_error(){
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['create_tag']);
        $response->assertStatus($this->data['error_empty_name']['expected_status'])
            ->assertJsonStructure($this->data['error_empty_name']['expected_structure']);
    }

    public function test_tag_store_name_error(){
        $this->data['store_name_error']['name'] = Str::random(55);
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['create_tag'],$this->data['store_name_error']);
        $response->assertStatus($this->data['store_name_error']['expected_status'])
            ->assertJsonStructure($this->data['store_name_error']['expected_structure']);
    }

    public function test_tag_store_success(){

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['create_tag'],$this->data['create_success']);
        $response->assertStatus($this->data['create_success']['expected_status'])
            ->assertJsonStructure($this->data['create_success']['expected_structure']);
    }

    public function test_tag_show_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['show_tag'].'/test');
        $response->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);
    }

    public function test_tag_show_empty_id_error(){
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['show_tag'].'/test');

        $response->assertStatus($this->data['error_empty_name']['expected_status'])
            ->assertJsonStructure($this->data['error_empty_name']['expected_structure']);
    }

    public function test_tag_show_not_exists(){

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['show_tag'].'/99999999');


        $response->assertStatus($this->data['error_not_exists']['expected_status'])
            ->assertJsonStructure($this->data['error_not_exists']['expected_structure']);
    }

    public function test_tag_show_success(){

        $tag = Tag::create([
            'user_id'=>$this->custom_user->id,
            'name'=>'test'
        ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['create_tag']."/".$tag->id,$this->data['show_tag_success']);

        $response->assertStatus($this->data['show_tag_success']['expected_status'])
            ->assertJsonStructure($this->data['show_tag_success']['expected_structure']);
    }


    public function test_tag_show_list_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['list_tag']);
        $response->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);
    }

    public function test_tag_show_list_success()
    {

        for($i=0;$i<10;$i++){
            $tag = Tag::create([
                'user_id'=>$this->custom_user->id,
                'name'=>'test'
            ]);
        }
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['list_tag']);
        $response->assertStatus($this->data['show_list_success']['expected_status'])
            ->assertJsonStructure($this->data['show_list_success']['expected_structure']);
    }

    public function test_tag_delete_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->delete($this->url['delete_tag'].'/test');

        $response->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);
    }

    public function test_tag_delete_empty_id_error(){
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->delete($this->url['delete_tag'].'/test');

        $response->assertStatus($this->data['error_empty_name']['expected_status'])
            ->assertJsonStructure($this->data['error_empty_name']['expected_structure']);
    }

    public function test_tag_delete_not_exists(){

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->delete($this->url['show_tag'].'/99999999');

        $response->assertStatus($this->data['error_not_exists']['expected_status'])
            ->assertJsonStructure($this->data['error_not_exists']['expected_structure']);
    }

    public function test_tag_delete_success(){

        $tag = Tag::create([
            'user_id'=>$this->custom_user->id,
            'name'=>'test'
        ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->delete($this->url['show_tag']."/".$tag->id);

        $response->assertStatus($this->data['delete_success']['expected_status'])
            ->assertJsonStructure($this->data['delete_success']['expected_structure']);
    }

}
