<?php

namespace App\Http\Controllers;

use App\Http\ApiRequests\ApiRulesTemplateRequest;
use App\Http\ApiResponses\ApiResponse;

class ApiRulesTemplateController extends Controller
{
    public function getTemplate(ApiRulesTemplateRequest $request): ApiResponse
    {
        return ApiResponse::common([
            'common_part' => trans('responses/chats_rules.common_part'),
            'restricted_words' => trans('responses/chats_rules.restricted_words'),
            'warn_from_bot_for_violation' => trans('responses/chats_rules.warn_from_bot_for_violation'),
            'warn_from_bot_for_complaint' => trans('responses/chats_rules.warn_from_bot_for_complaint'),
        ]);
    }
}
