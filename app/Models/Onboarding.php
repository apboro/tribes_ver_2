<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property mixed $rules
 * @property mixed $user_id
 * @property bool|mixed $title
 * @property mixed $id
 * @property mixed $greeting_message_id
 */
class Onboarding extends Model
{
    use HasFactory;

    public function communities(): BelongsToMany
    {
        return $this->belongsToMany(Community::class, 'communities_onboardings', 'onboarding_id', 'community_id');
    }
}
