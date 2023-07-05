<?php

namespace Tests\Feature\Api\v3;

use App\Models\Author;
use App\Models\FavouritePublication;
use App\Models\Publication;
use Tests\TestCase;

class ApiPublicationFavouriteTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_add_to_favourite_not_auth()
    {
        $response = $response = $this->withHeaders([
            'Accept' => 'application/json',
            //'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post(route('api.publications.favorite.create'));
        $response->assertStatus(401);
    }

    public function test_add_to_success()
    {
        $author = Author::create([
            'user_id' => $this->custom_user->id,
        ]);
        $publication = Publication::create([
            'author_id' => $author->id
        ]);

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post(route('api.publications.favorite.create'), ['publication_id' => $publication->id])->assertOk();

        $this->assertDatabaseHas('favourite_publications', [
            'user_id' => $this->custom_user->id,
            'publication_id' => $publication->id
        ]);
    }

    public function test_delete_success()
    {
        $author = Author::create([
            'user_id' => $this->custom_user->id,
        ]);
        $publication = Publication::create([
            'author_id' => $author->id
        ]);

        FavouritePublication::create([
            'publication_id' => $publication->id,
            'user_id' => $this->custom_user->id
        ]);

        $this->assertDatabaseHas('favourite_publications', [
            'user_id' => $this->custom_user->id,
            'publication_id' => $publication->id
        ]);

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->delete(route('api.publications.favorite.delete', ['id' => $publication->id]))->assertOk();

        $this->assertDatabaseMissing('favourite_publications', [
            'user_id' => $this->custom_user->id,
            'publication_id' => $publication->id
        ]);
    }

    public function test_delete_not_auth()
    {
        $this->withHeaders([
            'Accept' => 'application/json',
            //'Authorization' => 'Bearer ' . $this->custom_token,
        ])->delete(route('api.publications.favorite.delete', ['id' => '123']))->assertStatus(401);
    }

}
