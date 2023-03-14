<?php

namespace Api\v3;

use App\Models\Administrator;
use App\Models\User;
use Tests\TestCase;

class ApiManagerMakeUserAdminTest extends TestCase
{
    private $url = [
        'make_admin' => 'api/v3/manager/users/make-admin',
        'remove_from_admin' => 'api/v3/manager/users/remove-from-admin',
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
                'errors',
                'message',
                'payload'
            ],
        ],
        'user_not_exists'=>[
            'expected_status' => 404,
            'expected_structure' => [
                'message',
                'payload'
            ],
        ],
        'make_user_admin_success'=>[
            'expected_status' => 200,
            'expected_structure' => [
                'message',
                'payload'
            ],
        ],
        'remove_from_admin_user_not_admin'=>[
            'expected_status' => 404,
            'expected_structure' => [
                'message',
                'payload'
            ],
        ]
    ];

    public function test_make_user_admin_data_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['make_admin'].'/test');

        $response->assertStatus($this->data['empty_data']['expected_status'])
            ->assertJsonStructure($this->data['empty_data']['expected_structure']);
    }


    public function test_make_user_admin_data_not_admin()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['make_admin']."/test");

        $response->assertStatus($this->data['not_admin']['expected_status'])
            ->assertJsonStructure($this->data['not_admin']['expected_structure']);
    }

    public function test_make_user_admin_not_valid_id()
    {
        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['make_admin']."/test");


        $response->assertStatus($this->data['not_valid_id']['expected_status'])
            ->assertJsonStructure($this->data['not_valid_id']['expected_structure']);
    }

    public function test_make_user_admin_not_exists()
    {

        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['make_admin']."/99999999");



        $response->assertStatus($this->data['user_not_exists']['expected_status'])
            ->assertJsonStructure($this->data['user_not_exists']['expected_structure']);
    }


    public function test_make_user_admin_success()
    {
        $user = User::factory()->create();

        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['make_admin']."/".$user->id);

        $user_admin = Administrator::where('user_id','=',$user->id)->first();

        $this->assertEquals($user_admin->user_id,$user->id);
        $response->assertStatus($this->data['make_user_admin_success']['expected_status'])
            ->assertJsonStructure($this->data['make_user_admin_success']['expected_structure']);
    }



    public function test_remove_user_from_admin_data_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['remove_from_admin'].'/test');

        $response->assertStatus($this->data['empty_data']['expected_status'])
            ->assertJsonStructure($this->data['empty_data']['expected_structure']);
    }


    public function test_remove_user_from_admin_data_not_admin()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['remove_from_admin']."/test");

        $response->assertStatus($this->data['not_admin']['expected_status'])
            ->assertJsonStructure($this->data['not_admin']['expected_structure']);
    }

    public function test_remove_user_from_admin_not_valid_id()
    {
        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['remove_from_admin']."/test");


        $response->assertStatus($this->data['not_valid_id']['expected_status'])
            ->assertJsonStructure($this->data['not_valid_id']['expected_structure']);
    }

    public function test_remove_user_from_admin_not_exists()
    {

        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['remove_from_admin']."/99999999");



        $response->assertStatus($this->data['user_not_exists']['expected_status'])
            ->assertJsonStructure($this->data['user_not_exists']['expected_structure']);
    }


    public function test_remove_user_from_admin_user_not_admin()
    {
        $user = User::factory()->create();

        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();


        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['remove_from_admin']."/".$user->id);

        $response->assertStatus($this->data['remove_from_admin_user_not_admin']['expected_status'])
            ->assertJsonStructure($this->data['remove_from_admin_user_not_admin']['expected_structure']);
    }


    public function test_remove_user_from_admin_success()
    {
        $user = User::factory()->create();

        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $admin = new Administrator();
        $admin->user_id = $user->id;
        $admin->save();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['remove_from_admin']."/".$user->id);

        $user_admin = Administrator::where('user_id','=',$user->id)->first();

        $this->assertNull($user_admin);
        $response->assertStatus($this->data['make_user_admin_success']['expected_status'])
            ->assertJsonStructure($this->data['make_user_admin_success']['expected_structure']);
    }

}
