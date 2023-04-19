<?php

namespace App\Http\ApiRequests;


/**
 * @OA\GET(
 * path="/api/v3/user-community-rules",
 * operationId="Get_user_rules",
 * summary= "Get user rules",
 * security= {{"sanctum": {} }},
 * tags= {"Chats IF-THEN"},
 *
 *      @OA\Response(response=200, description="OK"),
 * )
 */
class ApiUserRulesGetRequest extends ApiRequest
{

}