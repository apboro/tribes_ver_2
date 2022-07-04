<?php

namespace App\Repositories\Knowledge;

use App\Filters\API\QuestionsFilter;
use App\Models\Knowledge\Answer;
use App\Models\Knowledge\Question;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

interface KnowledgeRepositoryContract
{
    public function getQuestionById(int $id): ?Question;

    public function getCountQuestionsByCommunityByAuthorId(int $communityId, int $authorId): int;

    public function getQuestionsByCommunityId(int $community_id, QuestionsFilter $filters): LengthAwarePaginator;

    public function getAnswerById(int $answerId): ?Answer;

    public function getAnswerForQuestionId(int $questionId): ?Answer;

    public function clearAnswerForQuestionId(int $questionId): bool;

    public function deleteQuestion(int $questionId): bool;

    public function deleteQuestions(array $questionIds): bool;

    public function updatePublishQuestions(array $questionIds,bool $publish): bool;

    public function updateDraftQuestions(array $questionIds,bool $draft): bool;

    public function getQuestionsByIds($ids): Collection;
}
