<?php

namespace App\Http\ApiResources;

use App\Models\Antispam;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiAntispamResource extends JsonResource
{
    /** @var Antispam $resource */
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
            'name' => $this->resource->name,
            'del_message_with_link' => $this->resource->del_message_with_link,
            'ban_user_contain_link' => $this->resource->ban_user_contain_link,
            'del_message_with_forward' => $this->resource->del_message_with_forward,
            'ban_user_contain_forward' => $this->resource->ban_user_contain_forward,
            'work_period' => $this->resource->work_period
        ];
    }
}
