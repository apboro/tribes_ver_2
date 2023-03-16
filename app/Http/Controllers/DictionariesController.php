<?php

namespace App\Http\Controllers;

use App\Http\ApiRequests\ApiGetActionsRequest;
use App\Http\ApiRequests\ApiGetConditionsRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Models\ActionsDictionary;
use App\Models\ConditionsDictionary;
use Illuminate\Http\Request;

class DictionariesController extends Controller
{
    public function getActionsDictionary(ApiGetActionsRequest $request)
    {
        return ApiResponse::common(ActionsDictionary::all());
    }

    public function getConditionsDictionary(ApiGetConditionsRequest $request)
    {
        return ApiResponse::common(ConditionsDictionary::all());
    }
}
