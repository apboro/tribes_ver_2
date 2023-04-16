<?php

namespace App\Repositories\Community;

use App\Http\ApiRequests\ApiRequest;
use App\Models\CommunityReputationRules;

class CommunityReputationRepository
{

    public function add(ApiRequest $request)
    {
        /** @var CommunityReputationRules $community_reputation_rules */

        $community_reputation_rules = CommunityReputationRules::create([
            'name' => $request->input('name'),

            'who_can_rate' => $request->input('who_can_rate'),
            'rate_period' => $request->input('rate_period'),
            'rate_member_period' => $request->input('rate_member_period'),
            'rate_reset_period' => $request->input('rate_reset_period'),

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

        return $community_reputation_rules;
    }

}