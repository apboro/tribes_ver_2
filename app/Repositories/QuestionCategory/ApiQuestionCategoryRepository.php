<?php


namespace App\Repositories\QuestionCategory;


use App\Http\ApiRequests\ApiRequest;
use App\Http\ApiRequests\QuestionCategory\ApiQuestionCategoryStoreRequest;
use App\Http\ApiRequests\QuestionCategory\ApiQuestionCategoryUpdateRequest;
use App\Models\Knowledge\Knowledge;
use App\Models\QuestionCategory;
use Illuminate\Support\Facades\Auth;

class ApiQuestionCategoryRepository
{
    public function add(ApiQuestionCategoryStoreRequest $request)
    {
        $isKnowledgeOwner = Knowledge::query()->where('id', $request->get('knowledge_id'))->where('owner_id', Auth::user()->id)->exists();

        if (!$isKnowledgeOwner) {
            return false;
        }

        $questionCategory = QuestionCategory::query()->create([
            'owner_id' => Auth::user()->id,
            'name' => $request->get('name'),
            'knowledge_id' => $request->get('knowledge_id')
        ]);

        if (!$questionCategory) {
            return false;
        }

        return $questionCategory;
    }

    public function update(ApiQuestionCategoryUpdateRequest $request, int $id)
    {
        $questionCategory = QuestionCategory::query()
            ->where('id', $id)
            ->where('owner_id', Auth::user()->id)
            ->first();

        if (!$questionCategory) {
            return false;
        }

        $questionCategory->update([
            'name' => $request->get('name')
        ]);

        return $questionCategory;
    }

    public function list(ApiRequest $request)
    {
        $knowledge_id = $request->get('knowledge_id');
        return QuestionCategory::query()
            ->where('owner_id', Auth::user()->id)
            ->when($knowledge_id, function ($query) use ($knowledge_id) {
                $query->where('knowledge_id', $knowledge_id);
            })
            ->get();
    }

    public function show(int $id)
    {
        return QuestionCategory::query()
            ->where('id', $id)
            ->where('owner_id', Auth::user()->id)
            ->first();
    }

    public function delete(int $id)
    {
        $questionCategory = QuestionCategory::query()
            ->where('id', $id)
            ->where('owner_id', Auth::user()->id)
            ->first();

        if (!$questionCategory) {
            return false;
        }

        return $questionCategory->delete();
    }
}
