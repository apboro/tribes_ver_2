<?php

namespace Api\v3;

use App\Models\Administrator;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class ApiManagerBlockUserTest extends TestCase
{
    private $url = [
        'block_user' => 'api/v3/manager/users/block',
        'unblock_user' => 'api/v3/manager/users/unblock',
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
        'block_user_success'=>[
            'expected_status' => 200,
            'expected_structure' => [
                'message',
            ],
        ]
    ];


    public function test_block_user_data_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['block_user'].'/test');

        $response->assertStatus($this->data['empty_data']['expected_status'])
            ->assertJsonStructure($this->data['empty_data']['expected_structure']);
    }


    public function test_block_user_data_not_admin()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['block_user']."/test");

        $response->assertStatus($this->data['not_admin']['expected_status'])
            ->assertJsonStructure($this->data['not_admin']['expected_structure']);
    }

    public function test_block_user_not_valid_id()
    {
        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['block_user']."/test");


        $response->assertStatus($this->data['not_valid_id']['expected_status'])
            ->assertJsonStructure($this->data['not_valid_id']['expected_structure']);
    }

    public function test_block_user_not_exists()
    {

        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['block_user']."/99999999");

        $response->assertStatus($this->data['user_not_exists']['expected_status'])
            ->assertJsonStructure($this->data['user_not_exists']['expected_structure']);
    }


    public function test_block_user_success()
    {
        $user = User::factory()->create();

        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['block_user']."/".$user->id);

        $user_after = User::where('id','=',$user->id)->first();

        $this->assertTrue($user_after->is_blocked);
        $response->assertStatus($this->data['block_user_success']['expected_status'])
            ->assertJsonStructure($this->data['block_user_success']['expected_structure']);
    }





    public function test_unblock_user_data_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['unblock_user'].'/test');

        $response->assertStatus($this->data['empty_data']['expected_status'])
            ->assertJsonStructure($this->data['empty_data']['expected_structure']);
    }


    public function test_unblock_user_data_not_admin()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['unblock_user']."/test");

        $response->assertStatus($this->data['not_admin']['expected_status'])
            ->assertJsonStructure($this->data['not_admin']['expected_structure']);
    }

    public function test_unblock_user_not_valid_id()
    {
        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['unblock_user']."/test");


        $response->assertStatus($this->data['not_valid_id']['expected_status'])
            ->assertJsonStructure($this->data['not_valid_id']['expected_structure']);
    }

    public function test_ubblock_user_not_exists()
    {

        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['unblock_user']."/99999999");

        $response->assertStatus($this->data['user_not_exists']['expected_status'])
            ->assertJsonStructure($this->data['user_not_exists']['expected_structure']);
    }


    public function test_unblock_user_success()
    {
        $user = User::create([
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'remember_token' => Str::random(10),
            'hash' => Str::random(10),
            'code' => rand(1000,9999),
            'phone' => rand(9510000000,9519999999),
            'password' => bcrypt('test123'),
            'phone_confirmed' => true,
            'locale' => 'ru',
            'api_token' => Str::random(10),
            'is_blocked'=>true,
        ]);

        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['unblock_user']."/".$user->id);

        $user_after = User::where('id','=',$user->id)->first();

        $this->assertFalse($user_after->is_blocked);
        $response->assertStatus($this->data['block_user_success']['expected_status'])
            ->assertJsonStructure($this->data['block_user_success']['expected_structure']);
    }

}
