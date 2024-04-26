<?php

namespace App\Http\ApiRequests\Shop;

use App\Http\ApiRequests\ApiRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * @OA\Put(
 * path="/api/v3/shops",
 * operationId="update-shop",
 * summary= "Update shop",
 * security= {{"sanctum": {} }},
 * tags= {"Shop"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *          @OA\Schema(
 *              @OA\Property(property="name", type="string"),
 *              @OA\Property(property="about", type="string"),
 *              @OA\Property(property="photo", type="file", format="binary"),
 *         )
 *      )
 *  ),
 *     @OA\Response(response=200, description="OK"),
 * )
 */
class ApiShopUpdateRequest extends ApiRequest
{

    public function all($keys = null)
    {
        return ['id' => $this->route('id'),
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
                    return $query->where('user_id', Auth::user()->id ?? null);
                }),
            ],
            'name' => 'nullable|required|string|max:200',
            'about' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp',
        ];
    }

}
