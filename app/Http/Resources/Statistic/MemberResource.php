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
            "comm_name" => $this->resource->comm_name,
            "c_messages" => $this->resource->c_messages,
            "exit_date" => $this->resource->exit_date,
        ];
    }
}