<?php

namespace App\Http\ApiResources;

use App\Helper\PseudoCrypt;
use App\Models\Author;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopResourse extends JsonResource
{
    public $resource;

    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'user_id' => $this->resource->user_id,
            'name' => $this->resource->name,
            'about' => $this->resource->about,
            'photo' => $this->resource->photo,
            'buyable' => $this->resource->buyable,
            'shop_inline' => config('telegram_bot.bot.botFullName') . ' s-' . PseudoCrypt::hash($this->resource->id),
            'shop_link' => 'https://t.me/' . config('telegram_bot.bot.botName') . '/' . config('telegram_bot.bot.marketName') . '/?startapp='. $this->resource->id,
        ];
    }
}
