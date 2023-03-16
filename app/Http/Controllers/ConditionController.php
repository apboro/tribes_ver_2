<?php

namespace App\Http\Controllers;

use App\Http\ApiRequests\ApiStoreConditionRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Models\Condition;
use Illuminate\Support\Facades\Auth;

class ConditionController extends Controller
{
    public function store(ApiStoreConditionRequest $request)
    {
        $condition = Condition::create([
            'type_id'=>$request->get('type_id'),
            'user_id'=>Auth::user()->id,
            'parameter'=>$request->get('parameter') ?? null,
        ]);
        return ApiResponse::common($condition);
    }
}
