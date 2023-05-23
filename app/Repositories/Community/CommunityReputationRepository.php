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
            'title' => $request->input('title'),
            'user_id' => Auth::user()->id,
            'who_can_rate' => $request->input('who_can_rate'),
            'restrict_rate_member_period' => $request->input('restrict_rate_member_period'),

            'delay_start_rules_time' => $request->input('delay_start_rules_time'),
            'delay_start_rules_total_messages' => $request->input('delay_start_rules_total_messages'),

            'show_rating_tables' => $request->input('show_rating_tables'),
            'show_rating_tables_period' => $request->input('show_rating_tables_period'),
            'show_rating_tables_time' => $request->input('show_rating_tables_time'),
            'show_rating_tables_number_of_users' => $request->input('show_rating_tables_number_of_users'),
            'show_rating_tables_image' => $request->input('show_rating_tables_image'),
            'show_rating_tables_message' => $request->input('show_rating_tables_message'),

            'notify_about_rate_change' => $request->input('notify_about_rate_change'),
            'notify_about_rate_change_points' => $request->input('notify_about_rate_change_points'),
            'notify_about_rate_change_image' => $request->input('notify_about_rate_change_image'),
            'notify_about_rate_change_message' => $request->input('notify_about_rate_change_message'),

            'restrict_accumulate_rate' => $request->input('restrict_accumulate_rate'),
            'restrict_accumulate_rate_period' => $request->input('restrict_accumulate_rate_period'),
            'restrict_accumulate_rate_image' => $request->input('restrict_accumulate_rate_image'),
            'restrict_accumulate_rate_message' => $request->input('restrict_accumulate_rate_message'),
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
        array                    $words,
        CommunityReputationRules $community_reputation_rule,
        int                      $direction = self::TYPE_INCREASE_REPUTATION
    )
    {
        foreach ($words as $word) {
            ReputationKeyword::create([
                'direction' => $direction,
                'community_reputation_rules_uuid' => $community_reputation_rule->uuid,
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
        ApiRequest               $request,
        CommunityReputationRules $community_rule
    )
    {
        if (!empty($community_rule->communities)) {
            /** @var Community $community */
            foreach ($community_rule->communities as $community) {
                $community->reputation_rules_uuid = null;
                $community->save();
            }
        }
        foreach ($request->input('community_ids') as $community_id) {
            /** @var Community $community */
            $community = Community::where('id', $community_id)->
            where('owner', Auth::user()->id)->
            first();
            if ($community !== null) {
                $community->reputation_rules_uuid = $community_rule->uuid;
                $community->save();
            }
        }
    }

    public function edit(ApiRequest $request, int $id)
    {
        /** @var CommunityReputationRules $community_reputation_rules */
        $community_reputation_rules = CommunityReputationRules::where('id', $id)->where('user_id', Auth::user()->id)->first();
        if ($community_reputation_rules === null) {
            return false;
        }
        $community_reputation_rules->fill([
            'title' => $request->input('title'),
            'user_id' => Auth::user()->id,
            'who_can_rate' => $request->input('who_can_rate'),
            'restrict_rate_member_period' => $request->input('restrict_rate_member_period'),

            'delay_start_rules_time' => $request->input('delay_start_rules_time'),
            'delay_start_rules_total_messages' => $request->input('delay_start_rules_total_messages'),

            'show_rating_tables' => $request->input('show_rating_tables'),
            'show_rating_tables_period' => $request->input('show_rating_tables_period'),
            'show_rating_tables_time' => $request->input('show_rating_tables_time'),
            'show_rating_tables_number_of_users' => $request->input('show_rating_tables_number_of_users'),
            'show_rating_tables_image' => $request->input('show_rating_tables_image'),
            'show_rating_tables_message' => $request->input('show_rating_tables_message'),

            'notify_about_rate_change' => $request->input('notify_about_rate_change'),
            'notify_about_rate_change_points' => $request->input('notify_about_rate_change_points'),
            'notify_about_rate_change_image' => $request->input('notify_about_rate_change_image'),
            'notify_about_rate_change_message' => $request->input('notify_about_rate_change_message'),

            'restrict_accumulate_rate' => $request->input('restrict_accumulate_rate'),
            'restrict_accumulate_rate_period' => $request->input('restrict_accumulate_rate_period'),
            'restrict_accumulate_rate_image' => $request->input('restrict_accumulate_rate_image'),
            'restrict_accumulate_rate_message' => $request->input('restrict_accumulate_rate_message'),
        ]);
        $res = $community_reputation_rules->save();
        if ($res === null) {
            return false;
        }

        if (!empty($request->input('keyword_rate_up'))) {
            $this->removeKeywords($community_reputation_rules, self::TYPE_INCREASE_REPUTATION);
            $this->addReputationWords(
                $request->input('keyword_rate_up'),
                $community_reputation_rules,
                self::TYPE_INCREASE_REPUTATION
            );
        }

        if (!empty($request->input('keyword_rate_down'))) {
            $this->removeKeywords($community_reputation_rules, self::TYPE_DECREASE_REPUTATION);
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
        int                      $direction = self::TYPE_INCREASE_REPUTATION
    )
    {
        ReputationKeyword::where('community_reputation_rules_uuid', $community_reputation_rules->uuid)->
        where('direction', $direction)->
        delete();
    }
}