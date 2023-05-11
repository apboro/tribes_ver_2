<?php

namespace App\Http\Controllers\APIv3;


use App\Http\ApiRequests\ApiGetAllRulesRequest;
use App\Http\ApiRequests\ApiUserRulesDeleteRequest;
use App\Http\ApiRequests\ApiUserRulesGetRequest;
use App\Http\ApiRequests\ApiUserRulesStoreRequest;
use App\Http\ApiRequests\ApiUserRulesUpdateRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Antispam;
use App\Models\Community;
use App\Models\CommunityRule;
use App\Models\Onboarding;
use App\Models\UserRule;
use Illuminate\Support\Facades\Auth;

class ApiUserRulesController extends Controller
{
    public function store(ApiUserRulesStoreRequest $request)
    {
        $rule = new UserRule();
        $rule->rules = json_encode($request->input('rules'));
        $rule->user_id = Auth::user()->id;
        $rule->title = $request->input('title');
        $rule->save();
        foreach ($request->input('communities_ids') as $community_id) {
            $community = Community::where('id', $community_id)->where('owner', Auth::user()->id)->first();
            if ($community !== null) {
                $community->if_then_uuid = $rule->uuid;
                $community->save();
            }
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
        $rule = UserRule::find($request->user_rule_uuid);
        $rule->title = $request->input('title');
        $rule->rules = json_encode($request->input('rules'));
        $rule->save();
        foreach ($request->input('communities_ids') as $community_id) {
            $community = Community::where('id', $community_id)->where('owner', Auth::user()->id)->first();
            if ($community !== null) {
                $community->if_then_uuid = $rule->uuid;
                $community->save();
            }
        }

        return ApiResponse::common($rule);
    }

    public function delete(ApiUserRulesDeleteRequest $request)
    {
        UserRule::find($request->user_rule_uuid)->delete();

        return ApiResponse::success('Правило удалено');
    }

    public function getAllRules(ApiGetAllRulesRequest $request)
    {
        $user = Auth::user();

        $onboardings = Onboarding::query()
            ->with('communities')
            ->where('user_id', $user->id)
            ->when($request->has('rule_title'), function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->input('rule_title') . '%');
            })
            ->when($request->has('rule_uuid'), function ($query) use ($request) {
                $query->where('uuid', $request->input('rule_uuid'));
            })
            ->orderBy('updated_at', 'desc')->get();

        $ifThenRules = UserRule::where('user_id', $user->id)
            ->with('communities')
            ->when($request->has('rule_title'), function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->input('rule_title') . '%');
            })
            ->when($request->has('rule_uuid'), function ($query) use ($request) {
                $query->where('uuid', $request->input('rule_uuid'));
            })
            ->orderBy('updated_at', 'desc')->get();

        $antispamRules = Antispam::where('owner', $user->id)
            ->with('communities')
            ->when($request->has('rule_title'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('rule_title') . '%');
            })
            ->when($request->has('rule_uuid'), function ($query) use ($request) {
                $query->where('uuid', $request->input('rule_uuid'));
            })
            ->orderBy('updated_at', 'desc')->get();

        $moderationRules = CommunityRule::where('user_id', $user->id)
            ->with('communities')
            ->when($request->has('rule_title'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('rule_title') . '%');
            })
            ->when($request->has('rule_uuid'), function ($query) use ($request) {
                $query->where('uuid', $request->input('rule_uuid'));
            })
            ->orderBy('updated_at', 'desc')->get();

        $countAll = $onboardings->count() + $ifThenRules->count() + $antispamRules->count() + $moderationRules->count();

        $counts = ['all' => $countAll, 'onboardings_count' => $onboardings->count(), 'if-thens_count' => $ifThenRules->count(), 'antispams_count' => $antispamRules->count(), "moderations_count" => $moderationRules->count()];
        $rules = $onboardings->concat($ifThenRules)->concat($antispamRules)->concat($moderationRules);

        return ApiResponse::common(['rules' => $rules->skip($request->offset)->take($request->limit), 'counts' => $counts]);
    }


}
