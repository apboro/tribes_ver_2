<?php

namespace App\Http\Controllers\APIv3;

use App\Http\ApiRequests\ActionsConditions\ApiStoreActionRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Action;



class ApiActionsController extends Controller
{

    //TODO tests for this controller
    public function store(ApiStoreActionRequest $request)
    {
        $action = Action::create([
            'type_id'=> $request->type_id,
            'user_id'=> $request->user_id,
            'group_uuid'=> $request->group_uuid,
            'parameter' => $request->parameter ?? null
        ]);

        return ApiResponse::common($action);
    }

}
