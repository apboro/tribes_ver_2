<?php

namespace Api\v3;

use App\Models\Administrator;
use App\Models\UserSettings;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiManagerUserTest extends TestCase
{
    use WithFaker;

    private $url = [
        'show_user' => 'api/v3/manager/users',
        'update_commission' => 'api/v3/manager/users',
    ];


    private $data = [
        'empty_data' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ],
        ],
        'not_admin' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ],
        ],
        'not_valid_id' => [
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ],
        ],
        'user_not_exists'=>[
            'expected_status' => 404,
            'expected_structure' => [
                'message',
            ],
        ],
        'get_user_success'=>[
            'expected_status' => 200,
            'expected_structure' => [
                'data'=>[
                    'id',
                    'name',
                    'email',
                    'phone',
                    'phone_confirmed',
                    'is_blocked',
                    'locale',
                    'role_index',
                    'created_at',
                    'updated_at',
                ]
            ],
        ],
        'update_commission_empty_data' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ],
        ],
        'update_commission_not_admin' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ],
        ],
        'update_commission_not_valid_id'=>[
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ],
        ],
        'update_comission_empty_commission'=>[
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ],
        ],
        'update_commission_not_valid_commission'=>[
            'commission'=>'test',
            'expected_status' => 422,
            'comission'=>'test',
            'expected_structure' => [
                'message',
            ],
        ],
        'user_commission_user_not_exists'=>[
            'commission'=>12,
            'expected_status' => 404,
            'expected_structure' => [
                'message',
            ],
        ],
        'commission_not_exist'=>[
            'commission'=>12,
            'expected_status' => 200,
            'expected_structure' => [
                'message',
                
            ],
        ],
        'update_existed_commission'=>[
            'commission'=>12,
            'expected_status' => 200,
            'expected_structure' => [
                'message',
                
            ],
        ]
    ];

    public function test_get_user_data_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['show_user'].'/test');
        $response->assertStatus($this->data['empty_data']['expected_status'])
            ->assertJsonStructure($this->data['empty_data']['expected_structure']);
    }


    public function test_get_user_data_not_admin()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['show_user']."/test");

        $response->assertStatus($this->data['not_admin']['expected_status'])
            ->assertJsonStructure($this->data['not_admin']['expected_structure']);
    }

    public function test_get_user_not_valid_id()
    {
        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['show_user']."/test");
        $response->assertStatus($this->data['not_valid_id']['expected_status'])
            ->assertJsonStructure($this->data['not_valid_id']['expected_structure']);
    }

    public function test_get_user_not_exists()
    {

        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['show_user']."/99999999");

        $response->assertStatus($this->data['user_not_exists']['expected_status'])
            ->assertJsonStructure($this->data['user_not_exists']['expected_structure']);
    }


    public function test_get_user_success()
    {

        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['show_user']."/".$this->custom_user->id);

        $response->assertStatus($this->data['get_user_success']['expected_status'])
            ->assertJsonStructure($this->data['get_user_success']['expected_structure']);
    }


    public function test_set_user_commission_data_not_auth(){
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->put($this->url['update_commission'].'/test');

        $response->assertStatus($this->data['update_commission_empty_data']['expected_status'])
            ->assertJsonStructure($this->data['update_commission_empty_data']['expected_structure']);
    }

    public function test_set_user_commission_not_admin()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->put($this->url['update_commission']."/test");

        $response->assertStatus($this->data['update_commission_not_admin']['expected_status'])
            ->assertJsonStructure($this->data['update_commission_not_admin']['expected_structure']);
    }

    public function test_set_user_commission_empty_commission()
    {
        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->put($this->url['update_commission']."/test");

        $response->assertStatus($this->data['update_comission_empty_commission']['expected_status'])
            ->assertJsonStructure($this->data['update_comission_empty_commission']['expected_structure']);
    }

    public function test_set_user_commission_not_valid_commission()
    {
        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->put($this->url['update_commission']."/".$this->custom_user->id,$this->data['update_commission_not_valid_commission']);

        $response->assertStatus($this->data['update_commission_not_valid_commission']['expected_status'])
            ->assertJsonStructure($this->data['update_commission_not_valid_commission']['expected_structure']);
    }

    public function test_set_user_commission_user_not_exists()
    {
        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->put($this->url['update_commission']."/99999999",$this->data['user_commission_user_not_exists']);

        $response->assertStatus($this->data['user_commission_user_not_exists']['expected_status'])
            ->assertJsonStructure($this->data['user_commission_user_not_exists']['expected_structure']);
    }

    public function test_set_user_commission_commission_not_exist()
    {

        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->put($this->url['update_commission']."/".$this->custom_user->id,$this->data['commission_not_exist']);

        $response->assertStatus($this->data['commission_not_exist']['expected_status'])
            ->assertJsonStructure($this->data['commission_not_exist']['expected_structure']);
    }

    public function test_set_user_commission_commission_exist()
    {
        $user_settings = UserSettings::create([
            'user_id'=>$this->custom_user->id,
            'name'=>'percent',
            'value'=>10
        ]);
        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->put($this->url['update_commission']."/".$this->custom_user->id,$this->data['update_existed_commission']);

        $response->assertStatus($this->data['update_existed_commission']['expected_status'])
            ->assertJsonStructure($this->data['update_existed_commission']['expected_structure']);

        $after_update = UserSettings::where('id','=',$user_settings->id)->first();

        $this->assertEquals($this->data['update_existed_commission']['commission'],$after_update->value);

    }




}
