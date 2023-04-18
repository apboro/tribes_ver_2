<?php

namespace App\Http\ApiResources;

use App\Models\CommunityReputationRules;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiCommunityReputationRuleResource extends JsonResource
{

    /**
     * @var CommunityReputationRules
     */
    public $resource;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->resource->id,
            'name'=>$this->resource->name,
            'who_can_rate'=>$this->resource->who_can_rate,
            'rate_period'=>$this->resource->rate_period,
            'rate_member_period'=>$this->resource->rate_member_period,

            'rate_reset_period' => $this->resource->rate_reset_period,

            'notify_about_rate_change' => $this->resource->notify_about_rate_change,
            'notify_type' => $this->resource->notify_type,
            'notify_period' => $this->resource->notify_period,
            'notify_content_chat' => $this->resource->notify_content_chat,
            'notify_content_user' => $this->resource->notify_content_user,

            'public_rate_in_chat' => $this->resource->public_rate_in_chat,
            'type_public_rate_in_chat' => $this->resource->type_public_rate_in_chat,
            'rows_public_rate_in_chat' =>$this->resource->rows_public_rate_in_chat,
            'text_public_rate_in_chat' => $this->resource->text_public_rate_in_chat,
            'period_public_rate_in_chat' =>$this->resource->period_public_rate_in_chat,

            'count_for_new' => $this->resource->count_for_new,
            'start_count_for_new' => $this->resource->start_count_for_new,
            'count_reaction' => $this->resource->count_reaction,
            'keywords_up'=>$this->resource->reputationUpWords,
            'keywords_down'=>$this->resource->reputationDownWords,
            'communities'=>$this->resource->communities
        ];
    }
}
