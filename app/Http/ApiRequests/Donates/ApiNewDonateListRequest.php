<?php

namespace App\Http\ApiRequests\Donates;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get(
 *  path="/api/v3/donates",
 *  operationId="donates-list",
 *  summary="List of donates",
 *  security={{"sanctum": {} }},
 *  tags={"Donates"},
 *     @OA\Parameter(name="offset",in="query",description="Begin records from number {offset}",required=false,@OA\Schema(type="integer",)),
 *     @OA\Parameter(name="limit",in="query",description="Total records to display",required=false,@OA\Schema(type="integer",)),
 *     @OA\Parameter(name="sort_field",in="query",description="SortBy(updated_at, payments_count, payments_sum, donate_is_active)",required=false,@OA\Schema(type="string")),
 *     @OA\Parameter(name="sort_direction",in="query",description="sort_type(asc, desc)",required=false,@OA\Schema(type="string")),
 *     @OA\Parameter(name="search",in="query",description="search by title", required=false,@OA\Schema(type="string")),
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiNewDonateListRequest extends ApiRequest
{

    public function rules(): array
    {
        return [
            'sort_field'=>'string|in:updated_at,payments_count,payments_sum,donate_is_active',
            'sort_direction'=>'in:asc,desc',
            'search' => 'string|max:150',
        ];
    }


}