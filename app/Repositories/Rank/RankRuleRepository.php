<?php


namespace App\Repositories\Rank;


use App\Http\ApiRequests\UserRank\ApiRankRuleStoreRequest;
use App\Http\ApiRequests\UserRank\ApiRankRuleUpdateRequest;
use App\Models\Rank;
use App\Models\RankRule;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class RankRuleRepository
{
    public function list(): Collection
    {
        return RankRule::query()->where('user_id', Auth::user()->id)->get();
    }

    public function add(ApiRankRuleStoreRequest $request)
    {
        /** @var RankRule $rankRule */
        $rankRule = RankRule::query()->create([
            'name' => $request->get('rule_name'),
            'user_id' => Auth::user()->id,
            'rank_ids' => [],
            'period_until_reset' => Carbon::now()->addSeconds($request->get('period_until_reset'))->format('Y-m-d H:i'),
            'rank_change_in_chat' => $request->get('rank_change_in_chat'),
            'rank_change_message' => $request->get('rank_change_message'),
            'first_rank_in_chat' => $request->get('first_rank_in_chat'),
            'first_rank_message' => $request->get('first_rank_message'),
        ]);

        if (!$rankRule) {
            return false;
        }

        $ranks = $request->get('ranks');

        $rankIds = [];

        foreach ($ranks as $rank) {
            $rank = Rank::query()->create([
                'rank_rule_id' => $rankRule->id,
                'name'=> $rank['name'],
                'reputation_value_to_achieve' => $rank['reputation_value_to_achieve'],
            ]);

            $rankIds[] = $rank->id;
        }

        $rankRule->update([
            'rank_ids' => $rankIds
        ]);

        return $rankRule;
    }

    public function edit(ApiRankRuleUpdateRequest $request, int $id)
    {
        /** @var RankRule $rankRule */
        $rankRule = RankRule::query()
            ->where('user_id', Auth::user()->id)
            ->where('id', $id)
            ->first();

        if (!$rankRule) {
            return false;
        }

        $rankIds = [];

        foreach ($request->get('ranks') as $rank) {
            Rank::query()->where('id', $rank['id'])->update([
                'name' => $rank['name'],
                'reputation_value_to_achieve' => $rank['reputation_value_to_achieve']
            ]);

            $rankIds[] = $rank['id'];
        }

        Rank::query()->where('rank_rule_id', $rankRule->id)->whereNotIn('id', $rankIds)->delete();

        $rankRule->update([
            'name' => $request->get('rule_name'),
            'rank_ids' => $rankIds,
            'period_until_reset' => Carbon::now()->addSeconds($request->get('period_until_reset'))->format('Y-m-d H:i'),
            'rank_change_in_chat' => $request->get('rank_change_in_chat'),
            'rank_change_message' => $request->get('rank_change_message'),
            'first_rank_in_chat' => $request->get('first_rank_in_chat'),
            'first_rank_message' => $request->get('first_rank_message'),
        ]);

        return $rankRule;
    }

    public function show(int $id)
    {
        $rankRule = RankRule::query()->where('user_id', Auth::user()->id)->where('id', $id)->with('ranks')->first();

        if (!$rankRule) {
            return false;
        }

        return $rankRule;
    }
}