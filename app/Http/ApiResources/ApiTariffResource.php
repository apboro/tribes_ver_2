<?php

namespace App\Http\ApiResources;

use App\Helper\PseudoCrypt;
use App\Models\Tariff;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Tariff */
class ApiTariffResource extends JsonResource
{
    public function toArray($request)
    {
        $variant = $this->variants()->first();
        $community = $this->community;

        return [
            'id' => $this->id,
            'title' => $this->title,
            'main_description' => $this->main_description,
            'community_id' => $this->community_id,
            'tariff_is_payable' => $this->tariff_is_payable,
            'test_period' => $this->test_period,
            'bot command' => config('telegram_bot.bot.botFullName'). ' t-' . $this->inline_link.'-'.PseudoCrypt::hash($this->community->id),
            'main_image' => $this->main_image,
            'thanks_image' => $this->thanks_image,
            'thanks_message_is_active' => $this->thanks_message_is_active,
            'thanks_message' => $this->thanks_message,
            'test_period_is_active' => $this->test_period_is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'price' => $variant ? $variant->price : null,
            'chat_name' => $community->title,
            'followers' => $community->followers()->count(),
            'community_image' => $community->image,
            'tariff_page' => config('app.frontend_url').Tariff::FRONTEND_TARIFF_PAGE.$this->inline_link,
        ];
    }
}
