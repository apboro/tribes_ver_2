<?php

namespace App\Http\Controllers\APIv3;

use App\Http\ApiRequests\ActionsConditions\ApiDeleteConditionRequest;
use App\Http\ApiRequests\ActionsConditions\ApiGetConditionsRequest;
use App\Http\ApiRequests\ActionsConditions\ApiStoreConditionRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Condition;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

//TODO Tests for this controller
class ApiConditionController extends Controller
{
    public function getList(ApiGetConditionsRequest $request): ApiResponse
    {
        $conditions = Condition::where('user_id', Auth::user()->id)->get();
        return ApiResponse::common($conditions);
    }
    public function store(ApiStoreConditionRequest $request): ApiResponse
    {
        $condition = Condition::create([
            'type_id'=>$request->get('type_id'),
            'user_id'=>Auth::user()->id,
            'group_uuid'=>$request->group_uuid ?? Str::uuid(),
            'prefix'=>$request->prefix ?? null,
            'parameter'=>$request->get('parameter') ?? null,
            'parent_id' => $request->get('parent_id') ?? null,
        ]);
        return ApiResponse::common($condition);
    }

    public function delete(ApiDeleteConditionRequest $request)
    {
        $condition = Condition::where('user_id', Auth::user()->id)->where('id', $request->condition_id)->first();
        if ($condition) {
            $condition->delete();
            return ApiResponse::success('common.deleted');
        }
        return ApiResponse::error('Not found');
    }
}
