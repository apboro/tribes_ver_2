<?php

namespace Tests\Feature\Api\v3;

use App\Models\QuestionCategory;
use Tests\TestCase;

class QuestionCategoryControllerTest extends TestCase
{
    private array $url = [
        'default' => 'api/v3/question-category',
    ];

    private array $statuses = [
        'unauthorized' => 401,
        'success' => 200,
        'unprocessable' => 422,
    ];

    private array $structures = [
        'messageCode' => ['message','code'],
        'default' => [
            'data' => [
                'id',
                'name',
            ]
        ],
        'list' => [
            'data' => [[
                'id',
                'name',
            ]]
        ],
    ];

    public function test_question_category_store_unauthorized()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post($this->url['default']);

        $response->assertStatus($this->statuses['unauthorized'])->assertJsonStructure($this->structures['messageCode']);
    }

    public function test_question_category_store_empty_required_data()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['default']);

        $response->assertStatus($this->statuses['unprocessable'])->assertJsonStructure($this->structures['messageCode']);
    }

    public function test_question_category_store_success()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['default'],
            [
                'name' => 'test question category name',
            ]);

        $decodedResponse = json_decode($response->getContent());

        QuestionCategory::query()->where('id', $decodedResponse->data->id)->delete();

        $response->assertStatus($this->statuses['success'])->assertJsonStructure($this->structures['default']);
    }

    public function test_question_category_list_unauthorized()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['default']);

        $response->assertStatus($this->statuses['unauthorized'])->assertJsonStructure($this->structures['messageCode']);
    }

    public function test_question_category_list_success()
    {
        /** @var QuestionCategory $category */
        $category = QuestionCategory::query()->create([
            'name' => 'test category title',
            'owner_id' => $this->custom_user->id,
        ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['default']);

        $category->delete();

        $response->assertStatus($this->statuses['success'])->assertJsonStructure($this->structures['list']);
    }

    public function test_question_category_show_unauthorized()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['default'] . '/1');

        $response->assertStatus($this->statuses['unauthorized'])->assertJsonStructure($this->structures['messageCode']);
    }

    public function test_question_category_show_success()
    {
        /** @var QuestionCategory $category */
        $category = QuestionCategory::query()->create([
            'name' => 'test category title',
            'owner_id' => $this->custom_user->id,
        ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['default'] . "/$category->id");

        $category->delete();

        $response->assertStatus($this->statuses['success'])->assertJsonStructure($this->structures['default']);
    }

    public function test_question_category_update_unauthorized()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->put($this->url['default'] . '/1');

        $response->assertStatus($this->statuses['unauthorized'])->assertJsonStructure($this->structures['messageCode']);
    }

    public function test_question_category_update_empty_required_data()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->put($this->url['default'] . '/1');

        $response->assertStatus($this->statuses['unprocessable'])->assertJsonStructure($this->structures['messageCode']);
    }

    public function test_question_category_update_success()
    {
        /** @var QuestionCategory $category */
        $category = QuestionCategory::query()->create([
            'name' => 'test category title',
            'owner_id' => $this->custom_user->id,
        ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->put($this->url['default'] . "/$category->id",
            [
                'name' => 'test question category name 2',
            ]);

        $category->delete();

        $response->assertStatus($this->statuses['success'])->assertJsonStructure($this->structures['default']);
    }

    public function test_question_category_delete_unauthorized()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->delete($this->url['default'] . '/1');

        $response->assertStatus($this->statuses['unauthorized'])->assertJsonStructure($this->structures['messageCode']);
    }

    public function test_question_category_delete_success()
    {
        /** @var QuestionCategory $category */
        $category = QuestionCategory::query()->create([
            'name' => 'test category title',
            'owner_id' => $this->custom_user->id,
        ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->delete($this->url['default'] . "/$category->id");

        $category->delete();

        $response->assertStatus($this->statuses['success'])->assertJsonStructure($this->structures['messageCode']);
    }
}
