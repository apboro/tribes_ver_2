<?php

namespace App\Http\ApiRequests\ActionsConditions;

use App\Http\ApiRequests\ApiRequest;


/**
 * @OA\Get(
 *  path="/api/v3/actions-conditions/getList",
 *  operationId="get_users_actions_and_conditions",
 *  summary="Get users actions and conditions",
 *  security={{"sanctum": {} }},
 *  tags={"ActionsConditions"},
 *  @OA\Response(response=200, description="OK")
 *  )
 */

class ApiGetListActionsRequest extends ApiRequest
{

}