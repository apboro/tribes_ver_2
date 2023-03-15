<?php

namespace App\Http\ApiResources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /** @var User */
    public $resource;

    /**
     * @OA\Schema(
     *     title="user",
     *     schema="userResource",
     *     description="User resource",
     *     @OA\Xml(name="userResource"),
     *       @OA\Property(
     *            property="id",
     *            description="User ID",
     *            type="integer",
     *        ),
     *        @OA\Property(
     *            property="name",
     *            description="Имя пользователя",
     *            type="string",
     *       ),
     *        @OA\Property(
     *            property="email",
     *            description="Почта пользователя",
     *            type="string",
     *       ),
     *       @OA\Property(
     *            property="phone",
     *            description="Телефон пользователя",
     *            type="string",
     *       ),
     *       @OA\Property(
     *            property="phone_confirmed",
     *            description="Флаг подтверждения телефона",
     *            type="boolean",
     *       ),
     *  )
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'phone'=>$this->phone ?? null,
            'phone_confirmed'=>$this->phone_confirmed,
            'telegram_accounts' =>$this->telegramData(),
            'subscription'=>$this->subscription,
        ];
    }
}
