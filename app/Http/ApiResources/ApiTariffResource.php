<?php

namespace App\Http\ApiResources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Tariff */
class ApiTariffResource extends JsonResource
{
    public function toArray($request)
    {
        $variant = $this->variants()->first();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'main_description' => $this->main_description,
            'community_id' => $this->community_id,
            'test_period' => $this->test_period,
            'bot command' => config('telegram_bot.bot.botFullName'). ' ' . $variant->inline_link,
            'main_image' => $this->main_image,
            'thanks_image' => $this->thanks_image,
            'thanks_message_is_active' => $this->thanks_message_is_active,
            'tariff_is_payable' => $this->tariff_is_payable,
            'thanks_message' => $this->thanks_message,
            'test_period_is_active' => $this->test_period_is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'price' =>$variant->price,
        ];
    }
}
