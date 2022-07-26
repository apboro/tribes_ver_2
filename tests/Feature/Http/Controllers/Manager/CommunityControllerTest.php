<?php

namespace Tests\Feature\Http\Controllers\Manager;

use App\Models\Community;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CommunityControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetCommunity()
    {
        Sanctum::actingAs(
            User::factory()->create()
        );

        $community = Community::factory()->create()->only('id', 'title');

        $this->postJson('api/v2/community', ['id' => $community['id']])
            ->assertOk()
            ->assertJsonStructure([
                'id',
                'title'
            ])
            ->assertExactJson($community);

    }
}
