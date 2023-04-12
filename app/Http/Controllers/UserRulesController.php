<?php

namespace App\Http\Controllers;

use App\Http\ApiRequests\ApiRequest;
use App\Http\ApiRequests\ApiUserRulesGetRequest;
use App\Http\ApiRequests\ApiUserRulesStoreRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Models\UserRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class UserRulesController extends Controller
{
    public function store(ApiUserRulesStoreRequest $request)
    {
        foreach ($request->input('communities_ids') as $community_id) {
            $rule = new UserRule();
            $rule->rules = $request->input('rules');
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
}
