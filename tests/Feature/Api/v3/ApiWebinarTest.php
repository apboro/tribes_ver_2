<?php

namespace Tests\Feature\Api\v3;

use App\Models\Author;
use App\Models\Webinar;
use App\Services\WebinarService;
use Carbon\Carbon;
use Tests\TestCase;

class ApiWebinarTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_webinar_store_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            //'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post(route('api.webinar.store'));

        $response->assertStatus(401);
    }

    public function test_webinar_store_success()
    {
        Author::create([
            'user_id' => $this->custom_user->id,
        ]);
        $start_at = Carbon::now()->addDay()->format('Y-m-d H:i:s');
        $end_at = Carbon::now()->addDays()->addHour(1)->format('Y-m-d H:i:s');
        $this->mock(WebinarService::class)
            ->shouldReceive('add')
            ->once()
            ->andReturn((object)[
                'id' => 600,
                'name' => 'test',
                'desc' => 'test',
                'start_at' => $start_at,
                'end_at' => $end_at,
                'url' => "https://wbnr.su:50443/rooms?meet=494c1799-586c-4dff-8a96-df4920170a1a"
            ]);
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post(route('api.webinar.store'),
            [
                'title' => 'test',
                'description' => 'test',
                'start_at' => $start_at,
                'end_at' => $end_at
            ]);

        $response->assertStatus(200)->assertJsonStructure([
            "data" => [
                'author_id',
                'title',
                'description',
                "external_id",
                "external_url",
                "background_image",
                "start_at",
                "end_at",
                "uuid",
                "id",
            ]
        ]);
    }

    public function test_webinar_delete_not_auth()
    {
        $start_at = Carbon::now()->addDay()->format('Y-m-d H:i:s');
        $end_at = Carbon::now()->addDays()->addHour(1)->format('Y-m-d H:i:s');
        $author=Author::create([
            'user_id' => $this->custom_user->id,
        ]);
       $webinar= Webinar::create([
            'author_id' => $author->id,
            'title' => 'test',
            'description' => 'test',
            'external_id' => 123,
            'external_url' => 'test',
            'background_image' => 'test',
            'start_at' => $start_at,
            'end_at' => $end_at
        ]);
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            //'Authorization' => 'Bearer ' . $this->custom_token,
        ])->delete(route('api.webinar.delete',['id'=>$webinar->id]));

        $response->assertStatus(401);
    }

    public function test_webinar_delete_success()
    {
        $start_at = Carbon::now()->addDay()->format('Y-m-d H:i:s');
        $end_at = Carbon::now()->addDays()->addHour(1)->format('Y-m-d H:i:s');
        $author=Author::create([
            'user_id' => $this->custom_user->id,
        ]);
        $webinar= Webinar::create([
            'author_id' => $author->id,
            'title' => 'test',
            'description' => 'test',
            'external_id' => 123,
            'external_url' => 'test',
            'background_image' => 'test',
            'start_at' => $start_at,
            'end_at' => $end_at
        ]);

        $this->mock(WebinarService::class)
            ->shouldReceive('delete')
            ->once()
            ->andReturn(true);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->delete(route('api.webinar.delete',['id'=>$webinar->id]));

        $response->assertStatus(200);
    }


    public function test_webinar_update_success()
    {
        $author = Author::create([
            'user_id' => $this->custom_user->id,
        ]);
        $start_at = Carbon::now()->addDay()->format('Y-m-d H:i:s');
        $end_at = Carbon::now()->addDays()->addHour(1)->format('Y-m-d H:i:s');

        $webinar= Webinar::create([
            'author_id' => $author->id,
            'title' => 'test',
            'description' => 'test',
            'external_id' => 123,
            'external_url' => 'test',
            'background_image' => 'test',
            'start_at' => $start_at,
            'end_at' => $end_at
        ]);


        $this->mock(WebinarService::class)
            ->shouldReceive('update')
            ->once()
            ->andReturn((object)[
                'id' => 600,
                'name' => 'test',
                'desc' => 'test',
                'start_at' => $start_at,
                'end_at' => $end_at,
                'url' => "https://wbnr.su:50443/rooms?meet=494c1799-586c-4dff-8a96-df4920170a1a"
            ]);
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post(route('api.webinar.update',['id'=>$webinar->id]),
            [
                'title' => 'test1',
                'description' => 'test1',
                'start_at' => $start_at,
                'end_at' => $end_at
            ]);

        $response->assertStatus(200)->assertJsonStructure([
            "data" => [
                'author_id',
                'title',
                'description',
                "external_id",
                "external_url",
                "background_image",
                "start_at",
                "end_at",
                "uuid",
                "id",
            ]
        ]);
    }


    public function test_webinar_list_success()
    {
        $author = Author::create([
            'user_id' => $this->custom_user->id,
        ]);
        $start_at = Carbon::now()->addDay()->format('Y-m-d H:i:s');
        $end_at = Carbon::now()->addDays()->addHour(1)->format('Y-m-d H:i:s');

        $webinar= Webinar::create([
            'author_id' => $author->id,
            'title' => 'test',
            'description' => 'test',
            'external_id' => 123,
            'external_url' => 'test',
            'background_image' => 'test',
            'start_at' => $start_at,
            'end_at' => $end_at
        ]);


        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get(route('api.webinar.list'),
            [
                'title' => 'test1',
                'description' => 'test1',
                'start_at' => $start_at,
                'end_at' => $end_at
            ]);

        $response->assertStatus(200);
    }


    public function test_webinar_show_success()
    {
        $author = Author::create([
            'user_id' => $this->custom_user->id,
        ]);
        $start_at = Carbon::now()->addDay()->format('Y-m-d H:i:s');
        $end_at = Carbon::now()->addDays()->addHour(1)->format('Y-m-d H:i:s');

        $webinar= Webinar::create([
            'author_id' => $author->id,
            'title' => 'test',
            'description' => 'test',
            'external_id' => 123,
            'external_url' => 'test',
            'background_image' => 'test',
            'start_at' => $start_at,
            'end_at' => $end_at
        ]);


        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get(route('api.webinar.list',['id'=>$webinar->id]),
            [
                'title' => 'test1',
                'description' => 'test1',
                'start_at' => $start_at,
                'end_at' => $end_at
            ]);
        $response->assertStatus(200);
    }
}
