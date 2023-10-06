<?php

namespace App\Http\ApiResources\Admin;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserForManagerResource extends JsonResource
{
    /** @var User */
    public $resource;

    /**
     * Возвращает массив с записями Фамилия + Имя пользователя в телеге
     */
    private function findFirstLastName()
    {
        $firstNames = $this->resource->telegramMeta->pluck('first_name')->toArray() ?? null;
        $lastNames = $this->resource->telegramMeta->pluck('last_name')->toArray() ?? null;
        if (!$firstNames) {
            return null;
        }

        $result = [];
        foreach ($firstNames as $key=>$name){ 
            $result[] = $lastNames[$key] . ' ' . $name;
        }

    return $result;
    }

    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'phone' => $this->resource->phone,
            'community_owner_num' => count($this->resource->communities),
            'phone_confirmed' => $this->resource->phone_confirmed,
            'telegram_user_name' => array_map(
                                        fn($value) => '@' . $value,
                                        $this->resource->telegramMeta->pluck('user_name')->toArray()
                                    ),
            'telegram_first_last_name' => $this->findFirstLastName(), 
            'is_blocked' => $this->resource->is_blocked,
            'locale' => $this->resource->locale,
            'role_index' => $this->resource->role_index,
            'commission' => $this->resource->getTribesCommission(),
            'payins' => $this->resource->accumulation()->sum('amount') / 100,
            'payouts' => $this->resource->accumulation()
                    ->where('status', 'closed')
                    ->sum('amount') / 100 ?? '—',
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
            'communities' => implode(', ', $this->resource->communities->pluck('title')->toArray()),
        ];
    }
}
