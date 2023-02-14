<?php

namespace App\Services\Knowledge;

use App\Exceptions\KnowledgeException;
use App\Helper\ArrayHelper;
use App\Helper\PseudoCrypt;
use App\Models\Knowledge\Answer;
use App\Models\Knowledge\Question;
use App\Models\User;
use App\Repositories\Knowledge\KnowledgeRepositoryContract;
use Auth;


class ManageQuestionService
{
    private ?Question $question;
    private ?Answer $answer;
    private array $commands = [
        'delete',
        'update_draft',
        'update_publish',
    ];
    private KnowledgeRepositoryContract $knowledgeRepository;
    private $userId;
    private array $errors = [];
    private array $wrongIds = [];

    public function __construct(
        KnowledgeRepositoryContract $knowledgeRepository
    )
    {
        $this->knowledgeRepository = $knowledgeRepository;
    }

    /**
     * @param array $data
     * @return bool
     * @throws KnowledgeException
     */
    public function createFromArray(array $data): bool
    {
        $question = $this->prepareQuestion($data);
        if (!$this->storeQuestion()) {
            return false;
        }

        if (
            !empty(ArrayHelper::getValue($data, 'question.answer.context')) &&
            !empty($answerData = ArrayHelper::getValue($data, 'question.answer'))
        ) {
            return $this->createAnswerFromArray($answerData);
        }

        return true;
    }

    public function updateFromArray(array $data): bool
    {
        $questionId = ArrayHelper::getValue($data, 'question.id');

        $oldQuestion = $this->knowledgeRepository->getQuestionById($questionId);
        if(empty($oldQuestion)) {
            throw new KnowledgeException("Вопрос с идентификатором $questionId не найден");
        }
        $this->prepareQuestion($data, $oldQuestion);
        if (!$this->storeQuestion()) {
            return false;
        }

        if (
            !empty(ArrayHelper::getValue($data, 'question.answer.context')) &&
            !empty($answerData = ArrayHelper::getValue($data, 'question.answer'))
        ) {
            return $this->updateAnswerFromArray($answerData);
        } else {
            $this->knowledgeRepository->clearAnswerForQuestionId($this->question->id);
        }

        return true;
    }

    /**
     * @throws KnowledgeException
     */
    public function massOperation(string $command, array $ids, bool $mark = false): bool
    {
        if (!in_array($command, $this->commands)) {
            throw new KnowledgeException("Массовая операция $command еще не реализована");
        }

        switch ($command) {
            case 'delete':
                $this->knowledgeRepository->deleteQuestions($ids);
                break;
            case 'update_draft':
                $this->knowledgeRepository->updateDraftQuestions($ids, $mark);
                break;
            case 'update_publish':
                $this->knowledgeRepository->updatePublishQuestions($ids, $mark);
                break;
        }

        return empty($this->errors);
    }

    public function getStoredKey()
    {
        return $this->question->id;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getWrongIds(): array
    {
        return $this->wrongIds;
    }

    /**
     * @throws KnowledgeException
     */
    private function prepareQuestion(array $questionData, ?Question $question = null): Question
    {
        $questionText = ArrayHelper::getValue($questionData, 'question.context', '');
        if (empty($questionText)) {
            throw new KnowledgeException('Вопрос без текста нельзя сохранять', $questionData);
        }
        $this->question = $question ?? new Question();
        $this->question->community_id = ArrayHelper::getValue($questionData, 'community_id');
        $this->question->context = $questionText;
        $this->question->is_public = ArrayHelper::getValue($questionData, 'question.is_public', false);
        $this->question->category_id = ArrayHelper::getValue($questionData, 'question.category_id', null);
        $this->question->author_id = $this->getUserId();
        $this->question->setDraft(ArrayHelper::getValue($questionData, 'question.is_draft', false));


        $questionCount = $this->knowledgeRepository
            ->getCountQuestionsByCommunityByAuthorId($this->question->community_id, $this->question->author_id);
        //костыль на коленке, уникальный номер для каждого вопроса для формирования хеша для ссылки
        $unique = $this->question->author_id . $this->question->community_id . $questionCount;
        $this->question->uri_hash = PseudoCrypt::hash((int)$unique);

        $this->question->c_enquiry = 0;

        return $this->question;
    }

    private function storeQuestion(): bool
    {
        try {
            //todo по фэншую сохранение обектов надо выносить в репозиторий
            return $this->question->save();
        } catch (\Throwable $e) {
            throw new KnowledgeException($e->getMessage(), [
                'question' => $this->question->toArray(),
            ]);
        }
    }

    private function prepareAnswer(array $data, ?Answer $oldAnswer = null): Answer
    {
        $this->answer = $oldAnswer ?? new Answer();
        $this->answer->question_id = $this->question->id;
        $this->answer->community_id = $this->question->community_id;
        $this->answer->context = ArrayHelper::getValue($data, 'context', '');
        $this->answer->is_draft = ArrayHelper::getValue($data, 'is_draft', false);

        return $this->answer;
    }

    /**
     * @throws KnowledgeException
     */
    private function storeAnswer(): bool
    {
        try {
            //TODO перенести в репозиторий сохранение объектов
            return $this->answer->save();
        } catch (\Throwable $e) {
            throw new KnowledgeException($e->getMessage(), [
                'question' => $this->question->toArray(),
                'answer' => $this->answer->toArray(),
            ]);
        }

    }

    /**
     * @throws KnowledgeException
     */
    private function createAnswerFromArray($answerData): bool
    {
        $this->prepareAnswer($answerData);
        if (!$this->storeAnswer()) {
            return false;
        }
        return true;
    }

    private function updateAnswerFromArray($answerData): bool
    {
        //$answerId = ArrayHelper::getValue($answerData, 'id');
        //если $answerData есть берем связную модель или создаем новую
        $oldAnswer = $this->knowledgeRepository->getAnswerForQuestionId($this->question->id);

        $this->prepareAnswer($answerData, $oldAnswer);
        if (!$this->storeAnswer()) {
            return false;
        }
        return true;
    }

    /**
     * @return mixed
     */
    protected function getUserId()
    {
        if ($this->userId) {
            return $this->userId;
        }
        $authUser = Auth::user();
        $nameIdentifier = $authUser->getAuthIdentifierName();
        return $authUser->{$nameIdentifier};
    }

    public function setUserId($id)
    {
        $this->userId = $id;
    }

}