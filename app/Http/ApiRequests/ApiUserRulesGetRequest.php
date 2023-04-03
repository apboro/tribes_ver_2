<?php

namespace App\Http\ApiRequests;


/**
 * @OA\GET(
 * path="/api/v3/user-rules/get",
 * operationId="Get_user_rules",
 * summary= "Get user rules",
 * security= {{"sanctum": {} }},
 * tags= {"User Rules"},
 *
 *      @OA\Response(response=200, description="OK"),
 * )
 */
class ApiUserRulesGetRequest extends ApiRequest
{

}