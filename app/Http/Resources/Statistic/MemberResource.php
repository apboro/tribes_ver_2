<?php

namespace App\Http\Resources\Statistic;

use Illuminate\Http\Resources\Json\JsonResource;

/** @property object $resource */
class MemberResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'tele_id' => $this->resource->tele_id,
            "name" => $this->resource->name,
            "nick_name" => $this->resource->nick_name,
            "accession_date" => $this->resource->accession_date,
            "exit_date" => $this->resource->exit_date,
            "c_messages" => $this->resource->c_messages,
            "c_put_reactions" => $this->resource->c_put_reactions,
            "c_got_reactions" => $this->resource->c_got_reactions,
            "utility" => $this->resource->utility,
        ];
    }
}