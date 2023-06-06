<?php

namespace App\Http\Controllers\APIv3\Community\Rules;


use App\Http\ApiRequests\ApiGetAllRulesRequest;
use App\Http\ApiRequests\ApiUserRulesDeleteRequest;
use App\Http\ApiRequests\ApiUserRulesGetRequest;
use App\Http\ApiRequests\ApiUserRulesShowRequest;
use App\Http\ApiRequests\ApiUserRulesStoreRequest;
use App\Http\ApiRequests\ApiUserRulesUpdateRequest;
use App\Http\ApiResources\Rules\ApiCommunityRuleCollection;
use App\Http\ApiResources\Rules\ApiUserRuleResource;
use App\Http\ApiResources\Rules\ApiUserRulesCollection;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Antispam;
use App\Models\Community;
use App\Models\CommunityReputationRules;
use App\Models\CommunityRule;
use App\Models\Onboarding;
use App\Models\UserRule;
use Askoldex\Teletant\Api;
use Discord\Helpers\Collection;
use Illuminate\Support\Arr;
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

    public function list(ApiUserRulesGetRequest $request)
    {
        $rules = UserRule::where('user_id', Auth::user()->id)->get();

        return ApiResponse::list()->items(ApiUserRulesCollection::make($rules)->toArray($request));
    }

    public function show(ApiUserRulesShowRequest $request)
    {
        $rule = UserRule::where('user_id', Auth::user()->id)->where('uuid', $request->rule_uuid)->first();

        return ApiResponse::common(ApiUserRuleResource::make($rule)->toArray($request));
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
            ->with(['communities', 'restrictedWords'])
            ->when($request->has('rule_title'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('rule_title') . '%');
            })
            ->when($request->has('rule_uuid'), function ($query) use ($request) {
                $query->where('uuid', $request->input('rule_uuid'));
            })
            ->orderBy('updated_at', 'desc')->get();
        $moderationRules = ApiCommunityRuleCollection::make($moderationRules);

        $reputationRules = CommunityReputationRules::where('user_id', $user->id)
            ->with(['communities', 'reputationWords'])
            ->when($request->has('rule_title'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('rule_title') . '%');
            })
            ->when($request->has('rule_uuid'), function ($query) use ($request) {
                $query->where('uuid', $request->input('rule_uuid'));
            })
            ->orderBy('updated_at', 'desc')->get();

        $countAll = $onboardings->count() + $ifThenRules->count() + $antispamRules->count() + $moderationRules->count() + $reputationRules->count();

        $counts = [
            'all' => $countAll, 'onboardings_count' => $onboardings->count(),
            'if-thens_count' => $ifThenRules->count(),
            'antispams_count' => $antispamRules->count(),
            "moderations_count" => $moderationRules->count(),
            "reputations_count" => $reputationRules->count()
        ];
        $rules = $onboardings->concat($ifThenRules)->concat($antispamRules)->concat($moderationRules)->concat($reputationRules);

        /** @var \Illuminate\Support\Collection $sorted */
        $sorted = $rules->sortByDesc(function ($item) {
            return $item->updated_at;
        })->flatten();
        return ApiResponse::common(['rules' => $sorted->skip($request->offset)->take($request->limit), 'counts' => $counts]);
    }


}
