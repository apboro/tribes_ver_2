<?php

namespace App\Http\ApiResources\Admin;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserForManagerResource extends JsonResource
{
    /** @var User */
    public $resource;

    public function toArray($request)
    {

        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'phone' => $this->resource->phone,
            'community_owner_num' => count($this->resource->communities),
            'phone_confirmed' => $this->resource->phone_confirmed,
            'telegram_user_name' => $this->resource->telegramMeta->pluck('user_name'), //->user_name ?? '—',
            'is_blocked' => $this->resource->is_blocked,
            'locale' => $this->resource->locale,
            'role_index' => $this->resource->role_index,
            'commission' => User::getCommission($this->resource->id),
            'payins' => $this->resource->accumulation()->sum('amount') / 100,
            'payouts' => $this->resource->accumulation()
                    ->where('status', 'closed')
                    ->sum('amount') / 100 ?? '—',
            'created_at' => $this->resource->created_at->timestamp,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}
