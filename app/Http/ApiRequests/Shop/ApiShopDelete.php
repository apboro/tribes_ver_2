<?php

namespace App\Http\ApiRequests\Shop;

use App\Http\ApiRequests\ApiRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * @OA\Delete(
 * path="/api/v3/shops",
 * operationId="delete-shop",
 * summary= "Delete shop",
 * security= {{"sanctum": {} }},
 * tags= {"Shop"},
 *     @OA\Parameter(name="id",in="path",
 *         description="ID of shop in database",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiShopDelete extends ApiRequest
{
    public function all($keys = null)
    {
        return [
                'id' => $this->route('id'),
                'userId' => Auth::user()->id ?? null
                ] + parent::all();
    }

    public function rules(): array
    {
        return [
            'userId' => 'required|integer',
            'id' => [
                'required', 'integer',
                Rule::exists('shops')->where(function ($query) {
                    return $query->where('user_id', Auth::user()->id);
                }),
            ],
        ];
    }
}
