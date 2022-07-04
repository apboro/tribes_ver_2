<?php

namespace App\Http\Resources;

use App\Models\Community;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use function RingCentral\Psr7\parse_header;

/**
 * @property Community $resource
 */
class CommunitiesResource extends ResourceCollection
{

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->collection = $this->collection->map(function ($item){
            return new CommunityResource($item);
        });
    }

    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
