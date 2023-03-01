<?php

namespace Tests\Feature\Api\v3;

use App\Models\Project;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiProjectTest extends TestCase
{
    use WithFaker;

    private $url = [
        'show_project' => 'api/v3/projects',
        'create_project' => 'api/v3/projects/create',
        'update_project' => 'api/v3/projects',
        'show_user_projects' => 'api/v3/projects',
    ];

    private $data = [
        'error_not_auth' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ],
        ],
        'empty_data' => [
            'expected_status' => 200,
            'expected_structure' => [
                'message',
                'payload',
                'data' => [
                    'id',
                    'title',
                    'user_id',
                    'created_at',
                ],
            ],
        ],
        'title_data' => [
            'title' => 'test',
            'expected_status' => 200,
            'expected_structure' => [
                'message',
                'payload',
                'data' => [
                    'id',
                    'title',
                    'user_id',
                    'created_at',
                ],
            ],
        ],
        'title_data_error' => [
            'title' => 123,
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'payload',
                'errors',
            ],
        ],
        'show_project' => [
            'id' => 0,
            'expected_status' => 200,
            'expected_structure' => [
                'message',
                'payload',
                'data' => [
                    'id',
                    'title',
                    'user_id',
                    'created_at',
                ],
            ],
        ],
        'show_project_error_type' => [
            'id' => 'test',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'payload',
                'errors',
            ],
        ],
        'show_project_not_found' => [
            'id' => 0,
            'expected_status' => 404,
            'expected_structure' => [
                'message',
                'payload',
            ],
        ],
        'update_empty_id' => [
            'id' => 0,
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'payload',
                'errors',
            ],
        ],
        'update_not_valid_id' => [
            'id' => 'test',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'payload',
                'errors',
            ],
        ],
        'update_empty_title' => [
            'id' => '',
            'title' => '',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'payload',
                'errors',
            ],
        ],
        'update_not_valid_title' => [
            'id' => '',
            'title' => 123,
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'payload',
                'errors',
            ],
        ],
        'update_project_not_exist' => [
            'id' => 9999999999,
            'title' => 'test_title',
            'expected_status' => 404,
            'expected_structure' => [
                'message',
                'payload',
            ],
        ],
        'update_success' => [
            'id' => '',
            'title' => 'new_title_test',
            'expected_status' => 200,
            'expected_structure' => [
                'message',
                'payload',
                'data' => [
                    'id',
                    'title',
                    'user_id',
                    'created_at',
                    'updated_at',
                ],
            ],
        ],
        'update_project_error' => [
            'id' => '',
            'title' => 'new_title_test',
            'expected_status' => 401,
            'expected_structure' => [
                'message',
                'payload',
            ],
        ],
        'show_user_projects' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
                'payload',
            ],
        ],

        'show_user_projects_success' => [
            'expected_status' => 200,
            'expected_structure' => [
                'message',
                'payload',
                'data' => [[
                    'id',
                    'title',
                    'user_id',
                    'created_at',
                ]],
            ],
        ],

    ];

    public function test_create_project_error_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->url['create_project']);

        $response
            ->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);
    }

    public function test_create_project_title_data_error()
    {

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['create_project'], $this->data['title_data_error']);
        $response->assertStatus($this->data['title_data_error']['expected_status'])
            ->assertJsonStructure($this->data['title_data_error']['expected_structure']);
    }

    public function test_create_project_empty_data()
    {

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['create_project']);

        $response
            ->assertStatus($this->data['empty_data']['expected_status'])
            ->assertJsonStructure($this->data['empty_data']['expected_structure']);
    }

    public function test_create_project_title_data()
    {

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['create_project'], $this->data['title_data']);

        $response
            ->assertStatus($this->data['title_data']['expected_status'])
            ->assertJsonStructure($this->data['title_data']['expected_structure']);
    }

    public function test_show_project()
    {
        $project = Project::create([
            'title' => 'title1',
            'user_id' => $this->custom_user->id,
        ]);
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['show_project'] . '/' . $project->id);

        $response
            ->assertStatus($this->data['show_project']['expected_status'])
            ->assertJsonStructure($this->data['show_project']['expected_structure']);
    }

    public function test_show_project_error_type()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['show_project'] . '/' . $this->data['show_project_error_type']['id']);

        $response
            ->assertStatus($this->data['show_project_error_type']['expected_status'])
            ->assertJsonStructure($this->data['show_project_error_type']['expected_structure']);
    }

    public function test_show_project_not_found()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['show_project'] . '/' . $this->data['show_project_not_found']['id']);

        $response
            ->assertStatus($this->data['show_project_not_found']['expected_status'])
            ->assertJsonStructure($this->data['show_project_not_found']['expected_structure']);
    }

    public function test_update_project_empty_id()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['update_project'] . '/' . $this->data['update_empty_id']['id']);

        $response
            ->assertStatus($this->data['update_empty_id']['expected_status'])
            ->assertJsonStructure($this->data['update_empty_id']['expected_structure']);
    }

    public function test_update_project_not_valid_id()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['update_project'] . '/' . $this->data['update_not_valid_id']['id']);

        $response
            ->assertStatus($this->data['update_not_valid_id']['expected_status'])
            ->assertJsonStructure($this->data['update_not_valid_id']['expected_structure']);
    }

    public function test_update_project_empty_title()
    {
        $project = Project::factory()->create();
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['update_project'] . '/' . $project->id, $this->data['update_empty_title']);

        $response
            ->assertStatus($this->data['update_empty_title']['expected_status'])
            ->assertJsonStructure($this->data['update_empty_title']['expected_structure']);
    }

    public function test_update_project_not_valid_title()
    {
        $project = Project::factory()->create();
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['update_project'] . '/' . $project->id, $this->data['update_not_valid_title']);

        $response
            ->assertStatus($this->data['update_not_valid_title']['expected_status'])
            ->assertJsonStructure($this->data['update_not_valid_title']['expected_structure']);
    }

    public function test_update_project_project_not_exist()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['update_project'] . '/' . $this->data['update_project_not_exist']['id'], $this->data['update_project_not_exist']);

        $response
            ->assertStatus($this->data['update_project_not_exist']['expected_status'])
            ->assertJsonStructure($this->data['update_project_not_exist']['expected_structure']);
    }

    public function test_update_project_success()
    {
        $project = Project::create([
            'title' => 'old_title',
            'user_id' => $this->custom_user->id,
        ]);
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['update_project'] . '/' . $project->id, $this->data['update_success']);

        $after_update = Project::where('id', '=', $project->id)->first();

        $this->assertEquals($after_update->title, $this->data['update_success']['title']);

        $response
            ->assertStatus($this->data['update_success']['expected_status'])
            ->assertJsonStructure($this->data['update_success']['expected_structure']);
    }

    public function test_update_project_error()
    {
        $project = Project::create([
            'title' => 'old_title',
            'user_id' => $this->custom_user->id,
        ]);
        $this->createUserForTest();
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['update_project'] . '/' . $project->id, $this->data['update_project_error']);

        $after_update = Project::where('id', '=', $project->id)->first();

        $this->assertNotEquals($after_update->title, $this->data['update_project_error']['title']);

        $response
            ->assertStatus($this->data['update_project_error']['expected_status'])
            ->assertJsonStructure($this->data['update_project_error']['expected_structure']);
    }

    public function test_projects_auth_error()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['show_user_projects']);

        $response
            ->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);
    }

    public function test_projects_success()
    {
        Project::create([
            'title' => 'title1',
            'user_id' => $this->custom_user->id,
        ]);

        $project = Project::create([
            'title' => 'title2',
            'user_id' => $this->custom_user->id,
        ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['show_user_projects']);

        $response
            ->assertStatus($this->data['show_user_projects_success']['expected_status'])
            ->assertJsonStructure($this->data['show_user_projects_success']['expected_structure']);
    }
}
