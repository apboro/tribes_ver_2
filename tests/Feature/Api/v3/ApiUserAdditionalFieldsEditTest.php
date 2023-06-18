<?php

namespace Tests\Feature\Api\v3;

use Tests\TestCase;

class ApiUserAdditionalFieldsEditTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_not_auth()
    {
        $response = $this->put(route('api.users.edit_additional_fields'));
        $response->assertStatus(401);
    }

    public function test_error_gender()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token
        ])->put(route('api.users.edit_additional_fields'), ['gender' => 'test']);
        $response->assertStatus(422);
    }

    public function test_error_age()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token
        ])->put(route('api.users.edit_additional_fields'), ['age' => 'test']);
        $response->assertStatus(422);
    }

    public function test_success()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token
        ])->put(route('api.users.edit_additional_fields'), [
            'gender' => 'm',
            'age' => 15,
            'country' => 'США'
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['id' => $this->custom_user->id, 'gender' => 'm', 'age' => 15]);
    }
}
