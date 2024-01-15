<?php

namespace App\Http\ApiRequests\Shop;

use App\Http\ApiRequests\ApiRequest;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/v3/shops/my",
 *     operationId="show-shops-my-list",
 *     summary= "Show shops list of current user",
 *     tags= {"Shop"},
 *     @OA\Parameter(name="name",in="query",description="Search by name",required=false,@OA\Schema(type="string",)),
 *     @OA\Parameter(name="offset",in="query",description="Begin records from number {offset}",required=false,@OA\Schema(type="integer",)),
 *     @OA\Parameter(name="limit",in="query",description="Total records to display",required=false,@OA\Schema(type="integer",)),
 *     @OA\Response(response=200, description="OK"),
 * )
 */
class ApiShopShowMyListRequest extends ApiRequest
{
    public function all($keys = null)
    {
        return [
                'userId' => Auth::user()->id ?? null
                ] + parent::all();
    }

    public function rules(): array
    {
        return [
            'userId' => 'required|integer',
            'limit' => 'nullable|integer',
            'offset' => 'nullable|integer',
            'name' => 'nullable|string',
        ];
    }
}
