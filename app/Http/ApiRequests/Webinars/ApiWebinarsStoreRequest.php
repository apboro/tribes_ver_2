<?php

namespace App\Http\ApiRequests\Webinars;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\POST(path="/api/v3/webinars",
 *     tags={"Webinars"},
 *     summary="Webinars add",
 *     operationId="webinar-add",
 *     security={{"sanctum": {} }},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(property="title",type="string", description="Название вебинара"),
 *                 @OA\Property(property="description",type="string",description="Описание вебинара"),
 *                 @OA\Property(property="start_at",type="datetime", description="время начала вебинара, не позже. Формат: YYYY-MM-DD HH:MM:SS (обязательно)"),
 *                 @OA\Property(property="end_at",type="datetime", description="время завершения вебинара, не раньше. Формат: YYYY-MM-DD HH:MM:SS (обязательно)"),
 *                 @OA\Property(property="background_image",type="file"),
 *                 ),
 *             ),
 *         ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiWebinarsStoreRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|nullable|string|max:150',
            'description' => 'sometimes|nullable|string|max:50000',
            'start_at' => 'required|date_format:Y-m-d H:i:s',
            'end_at' => 'required|date_format:Y-m-d H:i:s|after:start_at',
            'background_image' => 'nullable|image|max:10240',
        ];
    }
}
