<?php

namespace Tests\Feature\Api\v3;

use Tests\TestCase;

class ApiUserDeleteTest extends TestCase
{

    public function test_delete_user_not_auth()
    {

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->delete(route('api.user.delete'));

        $response->assertStatus(401);
    }


    public function test_delete_user()
    {

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->delete(route('api.user.delete'));

        $response->assertStatus(200);
        $this->assertSoftDeleted('users', ['id' => $this->custom_user->id]);
    }
}
