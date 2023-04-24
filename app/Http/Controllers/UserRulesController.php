<?php

namespace App\Http\Controllers;


use App\Http\ApiRequests\ApiGetAllRulesRequest;
use App\Http\ApiRequests\ApiUserRulesDeleteRequest;
use App\Http\ApiRequests\ApiUserRulesGetRequest;
use App\Http\ApiRequests\ApiUserRulesStoreRequest;
use App\Http\ApiRequests\ApiUserRulesUpdateRequest;
use App\Http\ApiResources\CommunitiesCollection;
use App\Http\ApiResponses\ApiResponse;
use App\Models\Antispam;
use App\Models\CommunityRule;
use App\Models\Onboarding;
use App\Models\UserRule;
use Illuminate\Support\Facades\Auth;

class UserRulesController extends Controller
{
    public function store(ApiUserRulesStoreRequest $request)
    {
        foreach ($request->input('communities_ids') as $community_id) {
            $rule = new UserRule();
            $rule->rules = json_encode($request->input('rules'));
            $rule->user_id = Auth::user()->id;
            $rule->community_id = $community_id;
            $rule->save();
        }

        return ApiResponse::success('rules.saved_successfully');
    }

    public function get(ApiUserRulesGetRequest $request)
    {
        $rules = UserRule::where('user_id', Auth::user()->id)->get();

        return ApiResponse::common($rules);
    }

    public function update(ApiUserRulesUpdateRequest $request)
    {
        foreach ($request->input('communities_ids') as $community_id) {
            $rule = UserRule::find($request->user_rule_id);
            $rule->rules = json_encode($request->input('rules'));
            $rule->community_id = $community_id;
            $rule->save();
        }

        return ApiResponse::common($rule);
    }

    public function delete(ApiUserRulesDeleteRequest $request)
    {
        UserRule::find($request->user_rule_id)->delete();

        return ApiResponse::success('Правило удалено');
    }

    public function getAllRules(ApiGetAllRulesRequest $request)
    {
        $user = Auth::user();

        $onboardings = Onboarding::where('user_id', $user->id)->get();
        $ifThenRules = UserRule::where('user_id', $user->id)->get();
        $antispamRules = Antispam::where('owner', $user->id)->get();
        $moderationRules = CommunityRule::where('user_id', $user->id)->get();

        $countAll = $onboardings->count() + $ifThenRules->count() + $antispamRules->count()+ $moderationRules->count();
        $rules = [
            [   'onboardings' => $onboardings,
                'count' => $onboardings->count(),
            ],
            [   'ifThenRules' => $ifThenRules,
                'count' => $ifThenRules->count()
            ],
            [   'antispamRules' => $antispamRules,
                'count' => $antispamRules->count()],

            [   'moderationRules' => $moderationRules,
                'count' => $moderationRules->count()]
        ];
        return ApiResponse::common(['rules'=>$rules, 'count_all'=>$countAll]);
    }


}
