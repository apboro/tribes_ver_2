<?php

namespace App\Http\Controllers\APIv3\Donates;

use App\Http\ApiRequests\ApiRequest;
use Illuminate\Support\Facades\Storage;
use OpenApi\Annotations as OA;


/**
 * @OA\Put(
 *  path="/api/v3/donate/{id}",
 *  operationId="donate_update",
 *  summary="Update donate",
 *  security={{"sanctum": {} }},
 *  tags={"Donates"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *         )
 *     ),
 *     @OA\RequestBody(
 *      @OA\JsonContent(
 *           @OA\Property(property="title", type="string", example="Donate 111"),
 *           @OA\Property(property="command", type="string", example="@my_donate"),
 *           @OA\Property(property="description", type="string", example="My lovely donate"),
 *           @OA\Property(property="image", description="base64 image", type="string"),
 *           @OA\Property(property="donate_is_active", type="boolean", example="true"),
 *           @OA\Property(property="random_sum_is_active", type="boolean", example="true"),
 *           @OA\Property(property="random_sum_min", type="integer", example="50"),
 *           @OA\Property(property="random_sum_max", type="integer", example="5000"),
 *           @OA\Property(property="fix_sum_1_is_active", type="boolean", example="true"),
 *           @OA\Property(property="fix_sum_2_is_active", type="boolean", example="true"),
 *           @OA\Property(property="fix_sum_3_is_active", type="boolean", example="true"),
 *           @OA\Property(property="fix_sum_1", type="integer", example="500"),
 *           @OA\Property(property="fix_sum_2", type="integer", example="1000"),
 *           @OA\Property(property="fix_sum_3", type="integer", example="10000"),
 *           @OA\Property(property="fix_sum_1_button", type="string", example="na iriski"),
 *           @OA\Property(property="fix_sum_2_button", type="string", example="na kotletki"),
 *           @OA\Property(property="fix_sum_3_button", type="string", example="na samolet"),
 *         ),
 *      ),
 *      @OA\Response(response=200, description="Phone confirmed successfully")
 * )
 */
class ApiNewDonateUpdateRequest extends ApiRequest
{

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:120',
        ];
    }

    public function prepareForValidation(): void
    {
        $base64String = $this->request->get('image');
        $base64Data = substr($base64String, strpos($base64String, ',') + 1);
        $file = base64_decode($base64Data, true);
        if ($file) {
            preg_match('/^data:image\/(\w+);base64,/', $base64String, $matches);
            $fileExtension = $matches[1];
            $filename = uniqid() . '-' . time();
            $filePath = 'donates_images/' . $filename . '.' . $fileExtension;
            Storage::disk('public')->put($filePath, $file);

            $this->merge([
                'image' => 'storage/' . $filePath
            ]);
        }
    }

}