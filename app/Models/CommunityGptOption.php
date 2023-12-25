<?php

namespace App\Models;

use App\Models\Community;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommunityGptOption extends Model
{
    use HasFactory;

    protected $guarded = [];

    const CREATED_AT = null;
    const UPDATED_AT = null;

    public function themesOnOff(bool $isActive): self
    {
        return $this->updateOrCreate(
            ['community_id' => $this->community_id],
            ['is_active' => $isActive]
        );
    }

    public function community(): BelongsTo
    {
        return $this->belongsTo(Community::class);
    }
}
