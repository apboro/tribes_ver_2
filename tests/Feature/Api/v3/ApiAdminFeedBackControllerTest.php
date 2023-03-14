<?php

namespace Tests\Feature\Api\v3;

use App\Models\Administrator;
use App\Models\Feedback;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiAdminFeedBackControllerTest extends TestCase
{
    use WithFaker;

    private $url = [
        'feed_back_answer' => 'api/v3/manager/feed-back/answer',
        'feed_back_close' => 'api/v3/manager/feed-back/close',
        'feed_back_show' => 'api/v3/manager/feed-back/show',
        'feed_back_list'=> 'api/v3/manager/feed-backs'
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
            'id' => 'test',
            'message' => 'test',
            'expected_status' => 422,
            'expected_structure' => [
                'errors',
                'message',
                'payload'
            ],
        ],
        'feed_back_not_exists' => [
            'id' => 9999999,
            'message' => 'test',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'payload'
            ],
        ],
        'empty_message' => [
            'id' => 9999999,
            'message' => '',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'payload'
            ],
        ],
        'user_not_exists' => [
            'id' => 123,
            'message' => 'test',
            'expected_status' => 404,
            'expected_structure' => [
                'message',
                'payload'
            ],
        ],
        'answer_success' => [
            'id' => 123,
            'message' => 'test',
            'expected_status' => 200,
            'expected_structure' => [
                'message',
                'payload'
            ],
        ],
        'close_success' => [
            'id' => 123,
            'expected_status' => 200,
            'expected_structure' => [
                'message',
                'payload'
            ],
        ],
        'show_success' => [
            'id' => 123,
            'expected_status' => 200,
            'expected_structure' => [
                'message',
                'payload',
                'data' => [
                    "id",
                    "name",
                    "email",
                    "text",
                    "answer",
                    "status",
                    "user_id",
                    "manager_id",
                    "created_at",
                ]
            ],
        ],
        'list_success' => [
            'expected_status' => 200,
            'expected_structure' => [
                'message',
                'payload',
                'list' => [[
                    "id",
                    "name",
                    "email",
                    "text",
                    "answer",
                    "status",
                    "user_id",
                    "manager_id",
                    "created_at",
                ]

                ]
            ],
        ]
    ];

    public function test_feed_back_answer_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->url['feed_back_answer']);

        $response->assertStatus($this->data['empty_data']['expected_status'])
            ->assertJsonStructure($this->data['empty_data']['expected_structure']);
    }


    public function test_feed_back_answer_not_admin()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['feed_back_answer']);

        $response->assertStatus($this->data['not_admin']['expected_status'])
            ->assertJsonStructure($this->data['not_admin']['expected_structure']);
    }

    public function test_feed_back_answer_not_exists_id()
    {
        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['feed_back_answer'], $this->data['feed_back_not_exists']);

        $response->assertStatus($this->data['feed_back_not_exists']['expected_status'])
            ->assertJsonStructure($this->data['feed_back_not_exists']['expected_structure']);
    }

    public function test_feed_back_answer_empty_message()
    {
        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $feed_back = Feedback::create([
            'user_id' => $this->custom_user->id,
            'email' => $this->custom_user->id,
            'phone' => $this->faker->e164PhoneNumber,
            'name' => $this->faker->name,
            'text' => $this->faker->paragraph,
            'status' => 'Новый',
        ]);

        $this->data['empty_message']['id'] = $feed_back->id;

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['feed_back_answer'], $this->data['empty_message']);

        $response->assertStatus($this->data['empty_message']['expected_status'])
            ->assertJsonStructure($this->data['empty_message']['expected_structure']);
    }

    public function test_feed_back_answer_user_not_exists()
    {

        $feed_back = Feedback::create([
            'user_id' => 99999,
            'email' => $this->custom_user->id,
            'phone' => $this->faker->e164PhoneNumber,
            'name' => $this->faker->name,
            'text' => $this->faker->paragraph,
            'status' => 'Новый',
        ]);

        $this->data['user_not_exists']['id'] = $feed_back->id;

        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['feed_back_answer'], $this->data['user_not_exists']);

        $response->assertStatus($this->data['user_not_exists']['expected_status'])
            ->assertJsonStructure($this->data['user_not_exists']['expected_structure']);
    }


    public function test_feed_back_answer_success()
    {

        $feed_back = Feedback::create([
            'user_id' => $this->custom_user->id,
            'email' => $this->custom_user->email,
            'phone' => $this->faker->e164PhoneNumber,
            'name' => $this->faker->name,
            'text' => $this->faker->paragraph,
            'status' => 'Новый',
        ]);

        $this->data['answer_success']['id'] = $feed_back->id;

        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['feed_back_answer'], $this->data['answer_success']);
        $feed_back_after = Feedback::where('id', '=', $feed_back->id)->first();

        $this->assertNotEquals($feed_back->status, $feed_back_after->status);
        $this->assertEquals($this->custom_user->id, $feed_back_after->manager_user_id);

        $response->assertStatus($this->data['answer_success']['expected_status'])
            ->assertJsonStructure($this->data['answer_success']['expected_structure']);
    }

    public function test_feed_back_close_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['feed_back_close'] . "/test");

        $response->assertStatus($this->data['empty_data']['expected_status'])
            ->assertJsonStructure($this->data['empty_data']['expected_structure']);
    }


    public function test_feed_back_close_not_admin()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['feed_back_close'] . "/test");

        $response->assertStatus($this->data['not_admin']['expected_status'])
            ->assertJsonStructure($this->data['not_admin']['expected_structure']);
    }

    public function test_feed_back_close_not_exists_id()
    {
        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['feed_back_close'] . "/999999");

        $response->assertStatus($this->data['feed_back_not_exists']['expected_status'])
            ->assertJsonStructure($this->data['feed_back_not_exists']['expected_structure']);
    }

    public function test_feed_back_close_success()
    {
        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $feed_back = Feedback::create([
            'user_id' => $this->custom_user->id,
            'email' => $this->custom_user->email,
            'phone' => $this->faker->e164PhoneNumber,
            'name' => $this->faker->name,
            'text' => $this->faker->paragraph,
            'status' => 'Новый',
        ]);

        $this->data['close_success']['id'] = $feed_back->id;

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['feed_back_close'] . "/" . $this->data['close_success']['id']);

        $feed_back_after = Feedback::where('id', '=', $feed_back->id)->first();

        $this->assertEquals('Закрыт', $feed_back_after->status);
        $response->assertStatus($this->data['close_success']['expected_status'])
            ->assertJsonStructure($this->data['close_success']['expected_structure']);
    }


    public function test_feed_back_show_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['feed_back_show'] . "/test");

        $response->assertStatus($this->data['empty_data']['expected_status'])
            ->assertJsonStructure($this->data['empty_data']['expected_structure']);
    }


    public function test_feed_back_show_not_admin()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['feed_back_show'] . "/test");

        $response->assertStatus($this->data['not_admin']['expected_status'])
            ->assertJsonStructure($this->data['not_admin']['expected_structure']);
    }

    public function test_feed_back_show_not_exists_id()
    {
        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['feed_back_show'] . "/" . $this->data['feed_back_not_exists']['id']);

        $response->assertStatus($this->data['feed_back_not_exists']['expected_status'])
            ->assertJsonStructure($this->data['feed_back_not_exists']['expected_structure']);
    }

    public function test_feed_back_show_success()
    {
        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $feed_back = Feedback::create([
            'user_id' => $this->custom_user->id,
            'email' => $this->custom_user->email,
            'phone' => $this->faker->e164PhoneNumber,
            'name' => $this->faker->name,
            'text' => $this->faker->paragraph,
            'status' => 'Новый',
        ]);

        $this->data['show_success']['id'] = $feed_back->id;

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['feed_back_show'] . "/" . $this->data['show_success']['id']);

        $response->assertStatus($this->data['show_success']['expected_status'])
            ->assertJsonStructure($this->data['show_success']['expected_structure']);
    }



    public function test_feed_back_list_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['feed_back_list']);

        $response->assertStatus($this->data['empty_data']['expected_status'])
            ->assertJsonStructure($this->data['empty_data']['expected_structure']);
    }


    public function test_feed_back_list_not_admin()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['feed_back_list']);

        $response->assertStatus($this->data['not_admin']['expected_status'])
            ->assertJsonStructure($this->data['not_admin']['expected_structure']);
    }


    public function test_feed_back_list_success()
    {
        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();
        for($z=0;$z<30;$z++){
            Feedback::create([
                'user_id' => $this->custom_user->id,
                'email' => $this->custom_user->email,
                'phone' => $this->faker->e164PhoneNumber,
                'name' => $this->faker->name,
                'text' => $this->faker->paragraph,
                'status' => 'Новый',
            ]);
        }

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['feed_back_list']."?page=2");

        $response->assertStatus($this->data['list_success']['expected_status'])
            ->assertJsonStructure($this->data['list_success']['expected_structure']);
    }
}
