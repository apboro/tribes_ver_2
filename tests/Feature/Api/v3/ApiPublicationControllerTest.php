<?php

namespace Tests\Feature\Api\v3;

use App\Models\Author;
use App\Models\Publication;
use Tests\TestCase;

class ApiPublicationControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_list_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            //'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get(route('api.publications.list'));

        $response->assertStatus(401);
    }

    public function test_list_not_author()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get(route('api.publications.list'));

        $response->assertStatus(404);
    }

    public function test_list_success()
    {
        Author::create([
            'user_id' => $this->custom_user->id,
        ]);
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get(route('api.publications.list'));

        $response->assertStatus(200);
    }


    public function test_store_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post(route('api.publications.create'));

        $response->assertStatus(401);
    }

    public function test_store_success()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post(route('api.publications.create'));


        $response->assertStatus(200)->assertJsonStructure(
            ['data' => [
                'id',
                'uuid',
                'title',
                'description',
                'is_active',
                'background_image',
                'price',
                'author_id'
            ]]
        );
    }


    public function test_delete_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->delete(route('api.publications.delete', ['id' => 999]));

        $response->assertStatus(401);
    }

    public function test_delete_success()
    {
        $author = Author::create([
            'user_id' => $this->custom_user->id,
        ]);
        $publication = Publication::create([
            'author_id' => $author->id
        ]);
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->delete(route('api.publications.delete', ['id' => $publication->id]));

        $response->assertStatus(200);
    }


    public function test_show_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get(route('api.publications.show', ['id' => 999]));

        $response->assertStatus(401);
    }

    public function test_show_success()
    {
        $author = Author::create([
            'user_id' => $this->custom_user->id,
        ]);
        $publication = Publication::create([
            'author_id' => $author->id
        ]);
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get(route('api.publications.show', ['id' => $publication->id]));

        $response->assertStatus(200)->assertJsonStructure(
            ['data' => [
                'id',
                'uuid',
                'title',
                'description',
                'is_active',
                'background_image',
                'price',
                'author_id'
            ]]);
    }

    public function test_add_part_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            //'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post(route('api.publication_parts.create'));

        $response->assertStatus(401);
    }

    public function test_add_part_success()
    {
        $author = Author::create([
            'user_id' => $this->custom_user->id,
        ]);
        $publication = Publication::create([
            'author_id' => $author->id
        ]);
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post(route('api.publication_parts.create'), [
            'publication_id' => $publication->id,
            'type' => 1,
            'text' => 'test',
            'order' => 1
        ]);
        $response->assertStatus(200);
    }

}
