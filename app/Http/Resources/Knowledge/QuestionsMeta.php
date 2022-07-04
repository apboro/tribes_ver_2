<?php

namespace App\Http\Resources\Knowledge;

use App\Http\Resources\CommunityResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class QuestionsMeta extends JsonResource
{

    public function toArray($request)
    {
        return [
            'link' => 1
        ];
    }
}
