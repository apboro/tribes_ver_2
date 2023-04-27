<?php


namespace App\Http\ApiRequests\UserRank;


use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get(path="/api/v3/chats/rank",
 *     tags={"Chats Rank Rules"},
 *     summary="Display list of rank rules",
 *     operationId="chats-show-list-rank-rules",
 *     security={{"sanctum": {} }},
 *     @OA\Response(response=200, description="OK"),
 * )
 */
class ApiRankRuleListRequest extends ApiRequest
{
}