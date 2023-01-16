<?php

namespace App\Http\Resources\Manager;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
/** @property User $resource */
class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'code' => $this->resource->code,
            'phone' => $this->resource->phone,
            'email_verified_at' => $this->resource->email_verified_at,
            'phone_confirmed' => $this->resource->phone_confirmed,
            'role_index' => $this->resource->role_index,
            'hash' => $this->resource->hash,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
            'locale' => $this->resource->locale,
            'commission' =>  $this->resource->getTribesCommission(),
            'telegram' => $this->resource->telegramMeta->user_name ?? '—',
            'community_owner_num' => $this->resource->getOwnCommunities()->count() ?? '—',
            'is_blocked' => $this->resource->is_blocked,
            'payins' => $this->resource->accumulation()->sum('amount') / 100,
            'payouts' =>$this->resource->accumulation()
                ->where('status','closed')
                ->sum('amount') / 100 ?? '—',
        ];
    }
}