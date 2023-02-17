<?php

namespace Tests\old_test\Unit\knowledge;

use App\Exceptions\KnowledgeException;
use App\Helper\ArrayHelper;
use App\Models\Knowledge\Answer;
use App\Models\Knowledge\Question;
use App\Services\Knowledge\ManageQuestionService;
use Tests\BaseUnitTest;

class ManageQuestionServiceTest extends BaseUnitTest
{
    private $service;

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app()->make(ManageQuestionService::class);


    }


    public function testCreateUpdateQuestionSuccess()
    {
        $data = $this->prepareDBCommunity();
        $this->service->setUserId(ArrayHelper::getValue($data,'user.id'));
        $result = $this->service->createFromArray([
            "community_id" => ArrayHelper::getValue($data,'community.id'),
            'question' => [
                "is_draft" => false,
                "is_public" => true,
                "context" => "Test question",
                "answer" => [
                    "context" => "Test answer",
                ],
            ]
        ]);
        $this->assertTrue($result, 'Не сохраняет вопрос');

        $this->assertDatabaseHas(Question::class, [
            "community_id" => ArrayHelper::getValue($data,'community.id'),
            'context' => 'Test question',
            "is_draft" => false,
            "is_public" => true,
        ]);

        $this->assertDatabaseHas(Answer::class, [
            "community_id" => ArrayHelper::getValue($data,'community.id'),
            'context' => 'Test answer',
        ]);
        $questionId = $this->service->getStoredKey();
        $result = $this->service->updateFromArray([
            "community_id" => ArrayHelper::getValue($data,'community.id'),
            'question' => [
                'id' => $questionId,
                "is_draft" => true,
                "is_public" => true,
                "context" => "Test update question",
                "answer" => [
                    "context" => "Test update answer",
                ],
            ]
        ]);
        $this->assertTrue($result, 'Не сохраняет вопрос');

        $this->assertDatabaseHas(Question::class, [
            "community_id" => ArrayHelper::getValue($data,'community.id'),
            'context' => 'Test update question',
            "is_draft" => true,
            "is_public" => false,
        ]);

        $this->assertDatabaseHas(Answer::class, [
            "community_id" => ArrayHelper::getValue($data,'community.id'),
            'context' => 'Test update answer',
        ]);
    }

    public function testWrongIdUpdateQuestionException()
    {
        $data = $this->prepareDBCommunity();
        $this->service->setUserId(ArrayHelper::getValue($data,'user.id'));
        $this->expectException(KnowledgeException::class);
        $result = $this->service->updateFromArray([
            "community_id" => ArrayHelper::getValue($data,'community.id'),
            'question' => [
                'id' => 999,
                "is_draft" => false,
                "is_public" => true,
                "context" => "Test question",
                "answer" => [
                    "context" => "Test answer",
                ],
            ]
        ]);
    }
    public function testNullIdUpdateQuestionException()
    {
        $data = $this->prepareDBCommunity();
        $this->service->setUserId(ArrayHelper::getValue($data,'user.id'));
        $this->expectException(KnowledgeException::class);
        $result = $this->service->updateFromArray([
            "community_id" => ArrayHelper::getValue($data,'community.id'),
            'question' => [
                'id' => null,
                "is_draft" => false,
                "is_public" => true,
                "context" => "Test question",
                "answer" => [
                    "context" => "Test answer",
                ],
            ]
        ]);
    }
}


