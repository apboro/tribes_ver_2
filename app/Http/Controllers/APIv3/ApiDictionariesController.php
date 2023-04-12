<?php

namespace App\Http\Controllers\APIv3;

use App\Http\ApiRequests\ActionsConditions\ApiGetRulesDictRequest;
use App\Http\ApiRequests\ActionsConditions\ApiGetConditionsDictRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\ActionsDictionary;
use App\Models\ConditionsDictionary;
use Conditions\ApiGetConditionsRequest;

class ApiDictionariesController extends Controller
{
    public function getRulesDictionary(ApiGetRulesDictRequest $request)
    {
        $rules = ConditionsDictionary::all()->map(function($rule){
            return[
                'type_id' => $rule->id,
                'subject'=>$rule->entity,
                'check' =>$rule->to_check,
                'value' =>$rule->detail,
            ];
        });
        $actions = ActionsDictionary::all();
        return ApiResponse::common(['rules'=>$rules, 'actions'=>$actions]);
    }
}
