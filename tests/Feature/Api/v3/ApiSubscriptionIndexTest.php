<?php

namespace Tests\Feature\Api\v3;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiSubscriptionIndexTest extends TestCase
{
    /**
     *
     * @return void
     */
    public function test_show_subscriptions_list_success()
    {
        $expected_structure = [
            'list' => [
                [
                    "id",
                    "name",
                    "description",
                    "is_active",
                    "price",
                    "period_days",
                    "sort_order",
                    "file_id",
                    "commission",
                    "created_at",
                    "updated_at",
                    "deleted_at",
                ],
            ],
            'message',
            'payload',
        ];
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get('/api/v3/subscriptions');

        $response->assertStatus(200)
            ->assertJsonStructure($expected_structure);

    }
}
