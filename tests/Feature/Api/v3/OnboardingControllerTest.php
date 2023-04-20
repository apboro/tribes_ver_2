<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class OnboardingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_onboarding()
    {
// prepare test data
        $this->createCommunityForTest();
        $this->createCommunityForTest();
        $this->createCommunityForTest();
        $user = $this->custom_user;
        $this->actingAs($user);
        $greetingImage = UploadedFile::fake()->image('greeting_image.jpg');
        $questionImage = UploadedFile::fake()->image('question_image.jpg');
        $requestData = [
            'greeting_message_text' => 'Hello, world!',
            'communities_ids' => [1, 2, 3],
            'rules' => json_encode(['key' => 'value']),
            'title' => 'Onboarding title',
            'greeting_image' => $greetingImage,
            'question_image' => $questionImage,
        ];

// call the store method
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post(env('APP_URL').'/api/v3/onboarding', $requestData);

// check the response status code
        $response->assertStatus(200);

// check if the data is saved in the database
        $this->assertDatabaseHas('greeting_messages', [
            'text' => $requestData['greeting_message_text'],
        ]);
        $this->assertDatabaseHas('onboardings', [
            'rules' => $requestData['rules'],
            'user_id' => $user->id,
            'title' => $requestData['title'],
            'greeting_message_id' => 1,
        ]);
    }
}
