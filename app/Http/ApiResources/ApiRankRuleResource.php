<?php


namespace App\Http\ApiResources;


use App\Models\RankRule;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiRankRuleResource extends JsonResource
{
    /**
     * @var RankRule
     */
    public $resource;

    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'                          => $this->resource->id,
            'name'                        => $this->resource->name,
            'ranks'                       => $this->resource->getRanks($this->resource->id),
            'user_id'                     => $this->resource->user_id,
            'period_until_reset'          => $this->resource->period_until_reset,
            'rank_change_in_chat'         => $this->resource->rank_change_in_chat,
            'rank_change_message'         => $this->resource->rank_change_message,
            'first_rank_in_chat'          => $this->resource->first_rank_in_chat,
            'first_rank_message'          => $this->resource->first_rank_message,
        ];
    }
}