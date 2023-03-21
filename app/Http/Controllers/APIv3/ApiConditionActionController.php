<?php

namespace App\Http\Controllers\APIv3;

use App\Http\ApiRequests\ActionsConditions\ApiAssignRulesToCommunityRequest;
use App\Http\ApiRequests\ActionsConditions\ApiDetachRuleFromCommunityRequest;
use App\Http\ApiRequests\ActionsConditions\ApiGetListActionsRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Action;
use App\Models\Community;
use App\Models\Condition;
use App\Models\ConditionAction;
use Illuminate\Support\Facades\Auth;

class ApiConditionActionController extends Controller
{

    //TODO tests for this controller
    public function getList(ApiGetListActionsRequest $request): ApiResponse
    {
        $conditions = Condition::where('user_id', Auth::user()->id)->get();
        $actions = Action::where('user_id', Auth::user()->id)->get();

        return ApiResponse::common(['actions'=>$actions, 'conditions'=>$conditions]);
    }

    public function assignToCommunity(ApiAssignRulesToCommunityRequest $request)
    {
        ConditionAction::create([
           'community_id'=>$request->community_id,
           'group_uuid'=>$request->group_uuid
        ]);
        return ApiResponse::success('common.added');
    }

    public function detachFromCommunity(ApiDetachRuleFromCommunityRequest $request): ApiResponse
    {
        $rule = ConditionAction::where('community_id', $request->community_id)->where('group_uuid', $request->group_uuid)->first();
        if ($rule){
            $rule->delete();
            return ApiResponse::success('common.deleted');
        }
        return ApiResponse::error('common.not_found');
    }

}
