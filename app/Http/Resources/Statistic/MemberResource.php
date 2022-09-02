<?php

namespace App\Http\Resources\Statistic;

use Illuminate\Http\Resources\Json\JsonResource;

/** @property object $resource */
class MemberResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "name" => $this->resource->name,
            "nick_name" => $this->resource->username,
            "accession_date" => $this->resource->accession_date,
            "exit_date" => $this->resource->exit_date,
            "c_messages" => $this->resource->c_messages,
            "c_put_reactions" => $this->resource->c_put_reactions,
            "c_got_reactions" => $this->resource->c_got_reactions,
        ];
    }
}