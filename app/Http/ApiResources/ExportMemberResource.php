<?php

namespace App\Http\ApiResources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExportMemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'tele_id' => $this->resource->tele_id,
            "name" => $this->resource->name,
            "nick_name" => $this->resource->nick_name,
            "accession_date" => $this->resource->accession_date,
            "comm_name" => $this->resource->comm_name,
            "exit_date" => $this->resource->exit_date,
            "c_messages" => $this->resource->c_messages,
            "c_put_reactions" => $this->resource->c_put_reactions,
            "c_got_reactions" => $this->resource->c_got_reactions,
            "utility" => $this->resource->utility,
        ];;
    }
}
