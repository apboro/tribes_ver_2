<?php


namespace App\Repositories\Knowledge;


use App\Http\ApiRequests\Knowledge\ApiKnowledgeBindToCommunityRequest;
use App\Http\ApiRequests\Knowledge\ApiKnowledgeStoreRequest;
use App\Http\ApiRequests\Knowledge\ApiKnowledgeUpdateRequest;
use App\Models\Community;
use App\Models\Knowledge\Knowledge;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ApiKnowledgeRepository
{
    public function add(ApiKnowledgeStoreRequest $request)
    {
        $isKnowledgeExists = Knowledge::query()
            ->where('owner_id', Auth::user()->id)
            ->where('name', $request->get('knowledge_name'))
            ->exists();

        if ($isKnowledgeExists) {
            return false;
        }

        /** @var Knowledge $knowledge */
        $knowledge = Knowledge::query()->create([
            'owner_id' => Auth::user()->id,
            'name' => $request->get('knowledge_name'),
            'uri_hash' => Str::uuid(),
        ]);

        if (!$knowledge) {
            return false;
        }

        return $knowledge;
    }

    public function show(int $id)
    {
        $knowledge = Knowledge::query()
            ->where('id', $id)
            ->where('owner_id', Auth::user()->id)
            ->first();

        if (!$knowledge) {
            return false;
        }

        return $knowledge;
    }

    public function update(ApiKnowledgeUpdateRequest $request, int $id)
    {
        /** @var Knowledge $knowledge */
        $knowledge = Knowledge::query()
            ->where('id', $id)
            ->where('owner_id', Auth::user()->id)
            ->first();

        if (!$knowledge) {
            return false;
        }

        $knowledge->update([
            'name' => $request->get('knowledge_name'),
//            'uri_hash' => Str::uuid(),
        ]);


        return $knowledge;
    }

    public function list()
    {
        $knowledges = Knowledge::query()
            ->where('owner_id', Auth::user()->id)
            ->orderByDesc('created_at')
            ->get();

        if (!$knowledges) {
            return false;
        }

        return $knowledges;
    }

    public function delete(int $id)
    {
        $knowledge = Knowledge::query()
            ->where('id', $id)
            ->where('owner_id', Auth::user()->id)
            ->first();

        if (!$knowledge) {
            return false;
        }

        return $knowledge->delete();
    }

    public function bindToCommunity(ApiKnowledgeBindToCommunityRequest $request): bool
    {
        $communities = Community::query()
            ->where('owner', Auth::user()->id)
            ->whereIn('id', $request->get('community_ids'))
            ->get();

        if (!$communities->count()) {
            return false;
        }

        $isUserKnowledgeOwner = Knowledge::query()
            ->where('id', $request->get('knowledge_id'))
            ->where('owner_id', Auth::user()->id)
            ->exists();

        if ($isUserKnowledgeOwner) {
            foreach ($communities as $community) {
                $community->update([
                    'knowledge_id' => $request->get('knowledge_id')
                ]);
            }
        } else {
            return false;
        }

        return true;
    }
}