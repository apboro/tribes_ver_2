<?php

namespace App\Http\Controllers;


use App\Http\ApiRequests\ApiUserRulesDeleteRequest;
use App\Http\ApiRequests\ApiUserRulesGetRequest;
use App\Http\ApiRequests\ApiUserRulesStoreRequest;
use App\Http\ApiRequests\ApiUserRulesUpdateRequest;
use App\Http\ApiResponses\ApiResponse;
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
    }

    public function delete(ApiUserRulesDeleteRequest $request)
    {
        foreach ($request->input('communities_ids') as $community_id) {
            $rule = UserRule::find($request->user_rule_id);
            $rule->community_id = null;
            $rule->save();
        }


    }

}
