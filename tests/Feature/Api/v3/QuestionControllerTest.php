<?php

namespace Tests\Feature\Api\v3;

use App\Models\Knowledge\Answer;
use App\Models\Knowledge\Knowledge;
use App\Models\Knowledge\Question;
use App\Models\QuestionCategory;
use Illuminate\Support\Str;
use Tests\TestCase;

class QuestionControllerTest extends TestCase
{
    private array $url = [
        'default' => 'api/v3/question',
        'list' => 'api/v3/question/list',
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
                'status',
                'knowledge_id',
                'category_id',
                'overlap',
                'context',
                'author_id',
                'uri_hash',
                'c_enquiry',
                'answer',
            ]
        ],
        'list' => [
            'data' => [[
                'id',
                'status',
                'knowledge_id',
                'category_id',
                'overlap',
                'context',
                'author_id',
                'uri_hash',
                'c_enquiry',
                'answer',
            ]]
        ],
    ];

    public function test_question_store_unauthorized()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post($this->url['default']);

        $response->assertStatus($this->statuses['unauthorized'])->assertJsonStructure($this->structures['messageCode']);
    }

    public function test_question_store_empty_required_data()
    {
        /** @var QuestionCategory $category */
        $category = QuestionCategory::query()->create([
            'name' => 'test category title',
            'owner_id' => $this->custom_user->id,
        ]);

        $createKnowledge = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post('api/v3/knowledge',
            [
                'knowledge_name' => Str::random(10),
            ]);

        $knowledge = json_decode($createKnowledge->getContent());

        $countUnprocessable = 0;

        // Проверяем отдельно каждое зачение = null
        for ($i = 0; $i < 6; ++$i) {
            $data = [
                'knowledge_id' => $knowledge->data->id,
                'question_status' => 'draft',
                'category_id' => $category->id,
                'overlap' => 'part',
                'question_text' => 'question test text',
                'answer_text' => 'answer test text',
            ];

            $newArray = array_values($data);
            $newArray[$i] = null;
            $j = 0;
            foreach ($data as $key => $value) {
                $data[$key] = $newArray[$j];
                ++$j;
            }

            $response = $this->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->custom_token,
            ])->post($this->url['default'], $data)
                ->assertStatus($this->statuses['unprocessable'])->assertJsonStructure($this->structures['messageCode']);
        }
    }

    public function test_question_store_success()
    {
        /** @var Knowledge $knowledge */
        $knowledge = Knowledge::query()->create([
            'name' => 'test knowledge name',
            'owner_id' => $this->custom_user->id,
            'uri_hash' => Str::uuid()
        ]);

        /** @var QuestionCategory $category */
        $category = QuestionCategory::query()->create([
            'name' => 'test category title',
            'owner_id' => $this->custom_user->id,
        ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['default'],
            [
                'knowledge_id' => $knowledge->id,
                'question_status' => 'draft',
                'category_id' => $category->id,
                'overlap' => 'part',
                'question_text' => 'question test text',
                'answer_text' => 'answer test text',
            ]);

        $decodedResponse = json_decode($response->getContent());

        Question::query()->where('id', $decodedResponse->data->id)->delete();
        $category->delete();
        $knowledge->delete();

        $response->assertStatus($this->statuses['success'])->assertJsonStructure($this->structures['default']);
    }

    public function test_question_list_unauthorized()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['list']);

        $response->assertStatus($this->statuses['unauthorized'])->assertJsonStructure($this->structures['messageCode']);
    }

    public function test_question_list_success()
    {
        /** @var Knowledge $knowledge */
        $knowledge = Knowledge::query()->create([
            'name' => 'test knowledge name',
            'owner_id' => $this->custom_user->id,
            'uri_hash' => Str::uuid()
        ]);

        /** @var Answer $answer */
        $answer = Answer::query()->create([
            'context' => 'test answer name'
        ]);

        /** @var QuestionCategory $category */
        $category = QuestionCategory::query()->create([
            'name' => 'test category title',
            'owner_id' => $this->custom_user->id,
        ]);

        $question = Question::query()->create([
            'context' => 'test question context',
            'status' => 'draft',
            'c_enquiry' => 0,
            'answer_id' => $answer->id,
            'author_id' => $this->custom_user->id,
            'category_id' => $category->id,
            'knowledge_id' => $knowledge->id
        ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['list'] . "/$knowledge->id");

        $answer->delete();
        $question->delete();
        $category->delete();
        $knowledge->delete();

        $response->assertStatus($this->statuses['success'])->assertJsonStructure($this->structures['list']);
    }

    public function test_question_show_unauthorized()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['default'] . '/1');

        $response->assertStatus($this->statuses['unauthorized'])->assertJsonStructure($this->structures['messageCode']);
    }

    public function test_question_show_success()
    {
        /** @var Knowledge $knowledge */
        $knowledge = Knowledge::query()->create([
            'name' => 'test knowledge name',
            'owner_id' => $this->custom_user->id,
            'uri_hash' => Str::uuid()
        ]);

        /** @var Answer $answer */
        $answer = Answer::query()->create([
            'context' => 'test answer name'
        ]);

        /** @var QuestionCategory $category */
        $category = QuestionCategory::query()->create([
            'name' => 'test category title',
            'owner_id' => $this->custom_user->id,
        ]);

        /** @var Question $question */
        $question = Question::query()->create([
            'context' => 'test question context',
            'status' => 'draft',
            'c_enquiry' => 0,
            'answer_id' => $answer->id,
            'author_id' => $this->custom_user->id,
            'category_id' => $category->id,
            'knowledge_id' => $knowledge->id
        ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['default'] . "/$question->id");

        $answer->delete();
        $question->delete();
        $category->delete();
        $knowledge->delete();

        $response->assertStatus($this->statuses['success'])->assertJsonStructure($this->structures['default']);
    }

    public function test_question_update_unauthorized()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->put($this->url['default'] . '/1');

        $response->assertStatus($this->statuses['unauthorized'])->assertJsonStructure($this->structures['messageCode']);
    }

    public function test_question_update_empty_required_data()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->put($this->url['default'] . '/1');

        $response->assertStatus($this->statuses['unprocessable'])->assertJsonStructure($this->structures['messageCode']);
    }

    public function test_question_update_success()
    {
        /** @var Knowledge $knowledge */
        $knowledge = Knowledge::query()->create([
            'name' => 'test knowledge name',
            'owner_id' => $this->custom_user->id,
            'uri_hash' => Str::uuid()
        ]);

        /** @var Answer $answer */
        $answer = Answer::query()->create([
            'context' => 'test answer name'
        ]);

        /** @var QuestionCategory $category */
        $category = QuestionCategory::query()->create([
            'name' => 'test category title',
            'owner_id' => $this->custom_user->id,
        ]);

        /** @var Question $question */
        $question = Question::query()->create([
            'context' => 'test question context',
            'status' => 'draft',
            'c_enquiry' => 0,
            'answer_id' => $answer->id,
            'author_id' => $this->custom_user->id,
            'category_id' => $category->id,
            'knowledge_id' => $knowledge->id
        ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->put($this->url['default'] . "/$question->id",
            [
                'knowledge_id' => $knowledge->id,
                'question_status' => 'draft',
                'category_id' => $category->id,
                'overlap' => 'part',
                'question_text' => 'question test text 2',
                'answer_text' => 'answer test text 2',
            ]);

        $answer->delete();
        $question->delete();
        $category->delete();
        $knowledge->delete();

        $response->assertStatus($this->statuses['success'])->assertJsonStructure($this->structures['default']);
    }

    public function test_question_delete_unauthorized()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->delete($this->url['default'] . '/1');

        $response->assertStatus($this->statuses['unauthorized'])->assertJsonStructure($this->structures['messageCode']);
    }

    public function test_question_delete_success()
    {
        /** @var Knowledge $knowledge */
        $knowledge = Knowledge::query()->create([
            'name' => 'test knowledge name',
            'owner_id' => $this->custom_user->id,
            'uri_hash' => Str::uuid()
        ]);

        /** @var Answer $answer */
        $answer = Answer::query()->create([
            'context' => 'test answer name'
        ]);

        /** @var QuestionCategory $category */
        $category = QuestionCategory::query()->create([
            'name' => 'test category title',
            'owner_id' => $this->custom_user->id,
        ]);

        /** @var Question $question */
        $question = Question::query()->create([
            'context' => 'test question context',
            'status' => 'draft',
            'c_enquiry' => 0,
            'answer_id' => $answer->id,
            'author_id' => $this->custom_user->id,
            'category_id' => $category->id,
            'knowledge_id' => $knowledge->id
        ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->delete($this->url['default'] . "/$question->id");

        $answer->delete();
        $question->delete();
        $category->delete();
        $knowledge->delete();

        $response->assertStatus($this->statuses['success'])->assertJsonStructure($this->structures['messageCode']);
    }
}
