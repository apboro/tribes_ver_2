<?php

namespace App\Http\ApiResources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserForManagerResource extends JsonResource
{
    /** @var User */
    public $resource;

    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'phone'=>$this->resource->phone,
            'community_owner_num'=>count($this->resource->communities),
            'phone_confirmed'=>$this->resource->phone_confirmed,
            'is_blocked'=>$this->resource->is_blocked,
            'locale'=>$this->resource->locale,
            'role_index'=>$this->resource->role_index,
            'commission'=>$this->resource->commission,
            'payins'=>$this->resource->payins,
            'created_at'=>$this->resource->created_at,
            'updated_at'=>$this->resource->updated_at,
        ];
    }
}
