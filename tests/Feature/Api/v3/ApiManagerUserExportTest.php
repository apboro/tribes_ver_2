<?php

namespace Tests\Feature\Api\v3;

use App\Models\Administrator;
use Tests\TestCase;

class ApiManagerUserExportTest extends TestCase
{
    private $url = [
        'user_export' => 'api/v3/manager/export/users',
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
        'user_export_success'=>[
            'expected_status' => 200,
            'expected_structure' => [
                'message',
                'payload',
            ],
        ]
    ];


    public function test_user_export_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['user_export']);

        $response->assertStatus($this->data['empty_data']['expected_status'])
            ->assertJsonStructure($this->data['empty_data']['expected_structure']);
    }


    public function test_user_export_not_admin()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['user_export']);

        $response->assertStatus($this->data['not_admin']['expected_status'])
            ->assertJsonStructure($this->data['not_admin']['expected_structure']);
    }




    public function test_user_export_success()
    {

        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['user_export'])->assertOk();
        $this->assertNotEmpty($response->headers->get('content-disposition'));
        $response->assertDownload();
    }

}
