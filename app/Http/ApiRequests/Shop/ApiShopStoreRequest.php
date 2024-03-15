<?php

namespace App\Http\ApiRequests\Shop;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;


/**
 * @OA\Post(
 * path="/api/v3/shops",
 * operationId="store-shops",
 * summary= "Store shops",
 * security= {{"sanctum": {} }},
 * tags= {"Shop"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *          @OA\Schema(
 *              @OA\Property(property="name", type="string"),
 *              @OA\Property(property="about", type="string"),
 *              @OA\Property(property="photo", type="file", format="binary"),
 *              @OA\Property(property="buyable",type="string"),
 *              @OA\Property(property="unitpay_project_id",type="string"),
 *              @OA\Property(property="unitpay_secretKey",type="string"),
 *         )
 *      )
 *  ),
 *     @OA\Response(response=200, description="OK"),
 * )
 */
class ApiShopStoreRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'about' => 'nullable|string|max:300',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp',
            'buyable' => 'string|in:true,false',
            'unitpay_project_id' => 'nullable|string',
            'unitpay_secretKey' => 'nullable|string',
        ];
    }
}
