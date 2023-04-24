<?php

namespace App\Http\ApiResources;

use App\Models\Antispam;
use App\Models\Community;
use App\Models\CommunityRule;
use App\Models\Onboarding;
use App\Models\UserRule;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CommunityResource extends JsonResource
{

    /**  @var Community */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = Auth::user();

        $onboardings = Onboarding::where('user_id', $user->id)->get();
        $ifThenRules = UserRule::where('user_id', $user->id)->get();
        $antispamRules = Antispam::where('owner', $user->id)->get();
        $moderationRules = CommunityRule::where('user_id', $user->id)->get();
        $rules = [
            ['onboardings' => $onboardings],
            ['ifThenRules' => $ifThenRules],
            ['antispamRules' => $antispamRules],
            ['moderationRules' => $moderationRules]
            ];
        return [
            "id" => $this->resource->id,
            "title" => $this->resource->title,
            "image" => $this->resource->image,
            "description" => $this->resource->description,
            "created_at" => $this->resource->created_at->timestamp,
            "updated_at" => $this->resource->updated_at->timestamp,
            "balance" => $this->resource->balance,
            "type" => $this->resource->connection->chat_type,
            "tags" => $this->resource->tags->makeHidden('pivot'),
            "rules" => $rules,
        ];
    }
}
