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
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'uuid' => $this->resource->uuid,
            'title' => $this->resource->title,
            'who_can_rate' => $this->resource->who_can_rate,
            'restrict_rate_member_period' => $this->resource->restrict_rate_member_period,

            'delay_start_rules_seconds' => $this->resource->delay_start_rules_seconds,
            'delay_start_rules_total_messages' => $this->resource->delay_start_rules_total_messages,

            'show_rating_tables' => $this->resource->show_rating_tables,
            'show_rating_tables_period' => $this->resource->show_rating_tables_period,
            'show_rating_tables_time' => $this->resource->show_rating_tables_time,
            'show_rating_tables_number_of_users' => $this->resource->show_rating_tables_number_of_users,
            'show_rating_tables_image' => $this->resource->show_rating_tables_image,
            'show_rating_tables_message' => $this->resource->show_rating_tables_message,

            'notify_about_rate_change' => $this->resource->notify_about_rate_change,
            'notify_about_rate_change_points' => $this->resource->notify_about_rate_change_points,
            'notify_about_rate_change_image' => $this->resource->notify_about_rate_change_image,
            'notify_about_rate_change_message' => $this->resource->notify_about_rate_change_message,

            'restrict_accumulate_rate' => $this->resource->restrict_accumulate_rate,
            'restrict_accumulate_rate_period' => $this->resource->restrict_accumulate_rate_period,
            'restrict_accumulate_rate_ptime' => $this->resource->restrict_accumulate_rate_time,
            'restrict_accumulate_rate_image' => $this->resource->restrict_accumulate_rate_image,
            'restrict_accumulate_rate_message' => $this->resource->restrict_accumulate_rate_message,

            'keywords_up'=>$this->resource->reputationUpWords->pluck('word')->toArray(),
            'keywords_down'=>$this->resource->reputationDownWords->pluck('word')->toArray(),
            'communities'=>$this->resource->communities->pluck('id')->toArray(),
        ];
    }
}
