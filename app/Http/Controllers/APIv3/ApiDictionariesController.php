<?php

namespace App\Http\Controllers\APIv3;

use App\Http\ApiRequests\ActionsConditions\ApiGetActionsDictRequest;
use App\Http\ApiRequests\ActionsConditions\ApiGetConditionsDictRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\ActionsDictionary;
use App\Models\ConditionsDictionary;
use Conditions\ApiGetConditionsRequest;

class ApiDictionariesController extends Controller
{
    public function getActionsDictionary(ApiGetActionsDictRequest $request)
    {
        return ApiResponse::common(ActionsDictionary::all());
    }

    public function getConditionsDictionary(ApiGetConditionsDictRequest $request)
    {
        return ApiResponse::common(ConditionsDictionary::all());
    }
}
