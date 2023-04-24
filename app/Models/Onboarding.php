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
 * @property false|mixed|string|null $question_image
 * @property false|mixed|string|null $greeting_image
 */
class Onboarding extends Model
{
//    protected $casts = ['rules'=>'json'];
    protected $hidden = ['created_at', 'updated_at'];
    use HasFactory;

    public function communities(): BelongsToMany
    {
        return $this->belongsToMany(Community::class, 'communities_onboardings', 'onboarding_id', 'community_id');
    }

    public function greeting()
    {
        return $this->hasOne(GreetingMessage::class, 'id','greeting_message_id');
    }

}
