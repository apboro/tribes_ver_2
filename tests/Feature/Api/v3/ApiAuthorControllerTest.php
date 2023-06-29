<?php

namespace Tests\Feature\Api\v3;

use App\Models\Author;
use Tests\TestCase;

class ApiAuthorControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    private $resource_format = [
        'author_id',
        'user_id',
        'name',
        'about',
        'photo'
    ];

    private $data = [
        'name' => 'test',
        'about' => 'test'
    ];

    public function test_store_author_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post(route('api.author.create'));

        $response->assertStatus(401);
    }

    public function test_store_author()
    {

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post(route('api.author.create'), $this->data);

        $response->assertStatus(200)
            ->assertJsonStructure($this->resource_format);
    }

    public function test_update_author()
    {

        $author = Author::create([
            'user_id' => $this->custom_user->id,
            'name' => 'test',
            'about' => 'test_about',
        ]);
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->put(route('api.author.create'), $this->data);

        $response->assertStatus(200)
            ->assertJsonStructure($this->resource_format);
    }
}
