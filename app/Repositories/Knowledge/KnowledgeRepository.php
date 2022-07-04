<?php

namespace App\Repositories\Knowledge;

use App\Filters\API\QuestionsFilter;
use App\Models\Knowledge\Answer;
use App\Models\Knowledge\Question;
use DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Log;

class KnowledgeRepository implements KnowledgeRepositoryContract
{
    public function getQuestionById($id): ?Question
    {
        return Question::where([
            'id' => $id,
        ])->with('answer')->first();
    }

    public function getCountQuestionsByCommunityByAuthorId($communityId, $authorId): int
    {
        return Question::where([
            'community_id' => $communityId,
            'author_id' => $authorId,
        ])->count();
    }

    public function getQuestionsByCommunityId($community_id, QuestionsFilter $filters): LengthAwarePaginator
    {
        return Question::filter($filters)->where(['community_id' => $community_id])
            ->with('community')->with('answer')->
            paginate(request('filter.per_page', 15), ['*'], 'page', request('filter.page', 1));
    }

    public function getAnswerById(int $answerId): ?Answer
    {
        return Answer::find($answerId);
    }

    public function clearAnswerForQuestionId(int $questionId): bool
    {
        return Answer::whereQuestionId($questionId)->delete();
    }

    public function getAnswerForQuestionId(int $questionId): ?Answer
    {
        return Answer::where(['question_id' => $questionId])->first();
    }

    public function deleteQuestion(int $questionId): bool
    {
        return (bool)Question::where(['id' => $questionId])->forceDelete();
    }

    public function deleteQuestions(array $questionIds): bool
    {
        return (bool)Question::whereIn('id', $questionIds)->forceDelete();
    }

    public function updatePublishQuestions(array $questionIds, bool $publish): bool
    {
        foreach ($this->getQuestionsByIds($questionIds) as $question) {
            $question->setPublic($publish);
            $question->save();
        }
        return true;
    }

    public function updateDraftQuestions(array $questionIds, bool $draft): bool
    {
        foreach ($this->getQuestionsByIds($questionIds) as $question) {
            $question->setDraft($draft);
            $question->save();
        }
        return true;
    }

    public function getQuestionsByIds($ids): Collection
    {
        return Question::whereIn('id', $ids)->get();
    }
}
