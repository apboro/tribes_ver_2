<?php

namespace Tests\Feature\Api\v3;

use App\Models\Publication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ApiStoreLMSFeedbackTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testStoreLMSFeedback()
    {
        // Create a mock Publication model instance
        $publication = Publication::factory()->create();

        // Generate random data for the LMSFeedbackRequest
        $requestData = [
            'like_material' => $this->faker->randomElement(['yes', 'no', 'neutral']),
            'enough_material' => $this->faker->randomElement(['enough', 'not_enough']),
            'what_to_add' => [
                'all_ok' => $this->faker->boolean,
                'options' => $this->faker->randomElements(['add_audio_video', 'add_images', 'add_text', 'make_webinar'], $this->faker->numberBetween(0, 4)),
            ],
            'what_to_remove' => [
                'all_ok' => $this->faker->boolean,
                'options' => $this->faker->randomElements(['not_interesting', 'less_audio', 'less_video', 'less_images', 'less_text', 'less_webinars'], $this->faker->numberBetween(0, 6)),
            ],
        ];

        $this->data['user_id'] = $this->custom_user->id;
        $this->data['author_id'] = $publication->author->id;
        $this->data['publication_id'] = $publication->id;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post('/api/v3/lms_feedback/'. $publication->id, $requestData);

        // Assert the response
        $response->assertStatus(200);

        // Assert that the LMSFeedback record was created in the database
        $this->assertDatabaseHas('lms_feedback', [
            'like_material' => $requestData['like_material'],
            'enough_material' => $requestData['enough_material'],
            'author_id' => $publication->author->id,
            'user_id' => $this->custom_user->id,
            'publication_id' => $publication->id,
        ]);
    }
}
