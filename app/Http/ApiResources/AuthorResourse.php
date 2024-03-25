<?php

namespace App\Http\ApiResources;

use App\Helper\PseudoCrypt;
use App\Models\Author;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResourse extends JsonResource
{

    /** @var Author */

    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'author_id' => $this->resource->id,
            'user_id' => $this->resource->user_id,
            'name' => $this->resource->name,
            'about' => $this->resource->about,
            'photo' => $this->resource->photo,
            'shop_inline' => config('telegram_bot.bot.botFullName') . ' s-' . PseudoCrypt::hash($this->resource->id),
            'shop_link' => 'https://t.me/' . config('telegram_bot.bot.botName') . '/' . config('telegram_bot.bot.marketName') . '?startapp='. $this->resource->id,
        ];
    }
}
