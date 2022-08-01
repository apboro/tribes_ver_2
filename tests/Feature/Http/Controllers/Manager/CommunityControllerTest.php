<?php

namespace Tests\Feature\Http\Controllers\Manager;

use App\Models\Administrator;
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
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user
        );
        Administrator::factory()->create([
            'user_id' => $user->id
        ]);

        $community = Community::factory()->create()->only('id', 'title');

        $response = $this->postJson('api/v2/community', ['id' => $community['id']]);

        $response->assertOk()
            ->assertJsonStructure([
                'id',
                'title'
            ])
            ->assertExactJson($community);
    }
}
