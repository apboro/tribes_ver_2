<?php

namespace App\Http\ApiRequests\Product;

use App\Http\ApiRequests\ApiRequest;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Put(
 * path="/api/v3/products/category/{id}",
 * operationId="update-category",
 * summary= "Update category",
 * security= {{"sanctum": {} }},
 * tags= {"Product categories"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of category in database",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *          @OA\Schema(
 *              @OA\Property(property="name", type="string"),
 *         )
 *      )
 *  ),
 *     @OA\Response(response=200, description="OK"),
 * )
 */
/**
 * @OA\Delete(
 * path="/products/category/{id}",
 * operationId="delete-category",
 * summary= "Delete category",
 * security= {{"sanctum": {} }},
 * tags= {"Product categories"},
 *     @OA\Parameter(name="id",in="path",
 *         description="ID of category in database",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK")
 *)
 */

class ApiCategoryModify extends ApiRequest
{
    public function all($keys = null)
    {
        return ['id' => $this->route('id')] + parent::all();
    }

    public function rules(): array
    {
        $additionalValues = [];
        if ($this->isMethod('put')) {
            $additionalValues['name'] = 'required|string|min:1';
        }

        return [
            'id' => [
                'required', 'integer',
                function ($attribute, $value, $fail) {
                    $userId = Auth::user()->id ?? null;
                    if (ProductCategory::isBelongsUser($value,  $userId) === false) {
                        $fail('Категория не принадлежит пользователю.');
                    }
                },
            ],
        ] + $additionalValues;
    }
}
