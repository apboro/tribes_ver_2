<?php

namespace App\Http\Resources\Knowledge;

use App\Http\Resources\CommunityResource;
use App\Models\Community;
use Illuminate\Http\Resources\Json\ResourceCollection;

class QuestionsResource extends ResourceCollection
{
    public static $wrap = 'items';

    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->collection = $this->collection->map(function ($item){
            return new QuestionResource($item);
        });
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public function with($request): array
    {
        $additional = [];
        /** @var Community $community */
        $community = Community::find($request->community_id ?? null);
        if($community){
            $additional['meta_info'] = [
                'public_list_link' => $community->getPublicKnowledgeLink(),
                'how_it_works_link' => $community->howItWorksLink(),
                'community_title' => $community->title,
            ];
        }
        return $additional;
    }
}
