<?php

namespace App\Repositories\Community;

use App\Http\ApiRequests\ApiRequest;
use App\Models\Community;
use App\Models\CommunityReputationRules;
use App\Models\ReputationKeyword;
use Illuminate\Support\Facades\Auth;

class CommunityReputationRepository
{

    const TYPE_INCREASE_REPUTATION = 1;
    const TYPE_DECREASE_REPUTATION = -1;

    /**
     * @param ApiRequest $request
     * @return CommunityReputationRules|false
     */
    public function add(ApiRequest $request)
    {
        /** @var CommunityReputationRules $community_reputation_rules */

        $community_reputation_rules = CommunityReputationRules::create([
            'name' => $request->input('name'),
            'user_id'=>Auth::user()->id,

            'who_can_rate' => $request->input('who_can_rate') ?? 'all',
            'rate_period' => $request->input('rate_period') ?? null,
            'rate_member_period' => $request->input('rate_member_period') ?? 60,
            'rate_reset_period' => $request->input('rate_reset_period') ?? null,

            'notify_about_rate_change' => $request->input('notify_about_rate_change'),
            'notify_type' => $request->input('notify_type'),
            'notify_period' => $request->input('notify_period'),
            'notify_content_chat' => $request->input('notify_content_chat'),
            'notify_content_user' => $request->input('notify_content_user'),

            'public_rate_in_chat' => $request->input('public_rate_in_chat'),
            'type_public_rate_in_chat' => $request->input('type_public_rate_in_chat'),
            'rows_public_rate_in_chat' => $request->input('rows_public_rate_in_chat'),
            'text_public_rate_in_chat' => $request->input('text_public_rate_in_chat'),
            'period_public_rate_in_chat' => $request->input('period_public_rate_in_chat'),

            'count_for_new' => $request->input('count_for_new'),
            'start_count_for_new' => $request->input('start_count_for_new'),
            'count_reaction' => $request->input('count_reaction'),
        ]);

        if ($community_reputation_rules == null) {
            return false;
        }
        if (!empty($request->input('keyword_rate_up'))) {
            $this->addReputationWords(
                $request->input('keyword_rate_up'),
                $community_reputation_rules,
                self::TYPE_INCREASE_REPUTATION
            );
        }

        if (!empty($request->input('keyword_rate_down'))) {
            $this->addReputationWords(
                $request->input('keyword_rate_down'),
                $community_reputation_rules,
                self::TYPE_DECREASE_REPUTATION
            );
        }

        if (!empty($request->input('community_ids'))) {
            $this->attachCommunities($request, $community_reputation_rules);
        }

        return $community_reputation_rules;
    }


    public function addReputationWords(
        array $words,
        CommunityReputationRules $community_reputation_rule,
        int $direction = self::TYPE_INCREASE_REPUTATION
    )
    {
        foreach ($words as $word) {
            ReputationKeyword::create([
                'direction'=>$direction,
                'community_reputation_rules_id' => $community_reputation_rule->id,
                'word' => $word
            ]);
        }
    }


    /**
     * @param ApiRequest $request
     * @param CommunityReputationRules $community_rule
     * @return void
     */
    public function attachCommunities(
        ApiRequest    $request,
        CommunityReputationRules $community_rule
    )
    {
        if(!empty($community_rule->communities)){
            /** @var Community $community */
            foreach($community_rule->communities as $community){
                $community->reputation_rules_id = null;
                $community->save();
            }
        }
        foreach ($request->input('community_ids') as $community_id) {
            /** @var Community $community */
            $community = Community::where('id', $community_id)->
                                    where('owner', Auth::user()->id)->
                                    first();
            if ($community !== null) {
                $community->reputation_rules_id = $community_rule->id;
                $community->save();
            }
        }
    }

    public function edit(ApiRequest $request, int $id)
    {
        /** @var CommunityReputationRules $community_reputation_rules */
        $community_reputation_rules = CommunityReputationRules::where('id',$id)->where('user_id',Auth::user()->id)->first();
        if($community_reputation_rules === null){
            return false;
        }
        $community_reputation_rules->fill([
            'name' => $request->input('name'),
            'user_id'=>Auth::user()->id,

            'who_can_rate' => $request->input('who_can_rate') ?? 'all',
            'rate_period' => $request->input('rate_period') ?? null,
            'rate_member_period' => $request->input('rate_member_period') ?? 60,
            'rate_reset_period' => $request->input('rate_reset_period') ?? null,

            'notify_about_rate_change' => $request->input('notify_about_rate_change'),
            'notify_type' => $request->input('notify_type'),
            'notify_period' => $request->input('notify_period'),
            'notify_content_chat' => $request->input('notify_content_chat'),
            'notify_content_user' => $request->input('notify_content_user'),

            'public_rate_in_chat' => $request->input('public_rate_in_chat'),
            'type_public_rate_in_chat' => $request->input('type_public_rate_in_chat'),
            'rows_public_rate_in_chat' => $request->input('rows_public_rate_in_chat'),
            'text_public_rate_in_chat' => $request->input('text_public_rate_in_chat'),
            'period_public_rate_in_chat' => $request->input('period_public_rate_in_chat'),

            'count_for_new' => $request->input('count_for_new'),
            'start_count_for_new' => $request->input('start_count_for_new'),
            'count_reaction' => $request->input('count_reaction'),
        ]);
        $res = $community_reputation_rules->save();
        if($res === null){
            return false;
        }

        if (!empty($request->input('keyword_rate_up'))) {
            $this->removeKeywords($community_reputation_rules,self::TYPE_INCREASE_REPUTATION);
            $this->addReputationWords(
                $request->input('keyword_rate_up'),
                $community_reputation_rules,
                self::TYPE_INCREASE_REPUTATION
            );
        }

        if (!empty($request->input('keyword_rate_down'))) {
            $this->removeKeywords($community_reputation_rules,self::TYPE_DECREASE_REPUTATION);
            $this->addReputationWords(
                $request->input('keyword_rate_down'),
                $community_reputation_rules,
                self::TYPE_DECREASE_REPUTATION
            );
        }

        if (!empty($request->input('community_ids'))) {
            $this->attachCommunities($request, $community_reputation_rules);
            $community_reputation_rules->load('communities');
        }

        return $community_reputation_rules;
    }

    public function removeKeywords(
        CommunityReputationRules $community_reputation_rules,
        int $direction=self::TYPE_INCREASE_REPUTATION
    ){
        ReputationKeyword::where('community_reputation_rules_id',$community_reputation_rules->id)->
                           where('direction',$direction)->
                           delete();
    }
}