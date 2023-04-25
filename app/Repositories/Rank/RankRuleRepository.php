<?php


namespace App\Repositories\Rank;


use App\Http\ApiRequests\UserRank\ApiRankRuleStoreRequest;
use App\Http\ApiRequests\UserRank\ApiRankRuleUpdateRequest;
use App\Models\Rank;
use App\Models\RankRule;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class RankRuleRepository
{
    public function list(): Collection
    {
        return RankRule::all();
    }

    public function add(ApiRankRuleStoreRequest $request)
    {
        $rankNames = $request->get('rank_names');

        $rankIds = [];

        foreach ($rankNames as $rankName) {
            $rank = Rank::query()->create([
                'name'=> $rankName,
                'reputation_value_to_achieve' => $request->get('reputation_value_to_achieve'),
            ]);

            $rankIds[] = $rank->id;
        }

        $rankRule = RankRule::query()->create([
            'name' => $request->get('rule_name'),
            'rank_ids' => $rankIds,
            'period_until_reset' => Carbon::parse($request->get('period_until_reset')),
            'rank_change_in_chat' => $request->get('rank_change_in_chat'),
            'rank_change_message' => $request->get('rank_change_message'),
            'first_rank_in_chat' => $request->get('first_rank_in_chat'),
            'first_rank_message' => $request->get('first_rank_message'),
        ]);

        if (!$rankRule) {
            return false;
        }

        return $rankRule;
    }

    public function edit(ApiRankRuleUpdateRequest $request, int $id)
    {
        /** @var RankRule $rankRule */
        $rankRule = RankRule::query()->where('id', $id)->first();

        if (!$rankRule) {
            return false;
        }

        $rankIds = [];

        foreach ($request->get('rank_ids') as $rankId) {
            $rankIds[] = (int) $rankId;
        }

        $rankRule->update([
            'name' => $request->get('rule_name'),
            'rank_ids' => $rankIds,
            'period_until_reset' => Carbon::parse($request->get('period_until_reset')),
            'rank_change_in_chat' => $request->get('rank_change_in_chat'),
            'rank_change_message' => $request->get('rank_change_message'),
            'first_rank_in_chat' => $request->get('first_rank_in_chat'),
            'first_rank_message' => $request->get('first_rank_message'),
        ]);

        return $rankRule;
    }

    public function show(int $id)
    {
        $rankRule = RankRule::query()->where('id', $id)->first();

        if (!$rankRule) {
            return false;
        }

        return $rankRule;
    }
}