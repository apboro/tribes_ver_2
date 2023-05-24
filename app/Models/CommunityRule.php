<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $restrictedWords
 *
 * @property int $user_id
 * @property string content
 * @property string content_image_path
 * @property boolean $quiet_on_restricted_words
 * @property int $max_violation_times
 */
class CommunityRule extends Model
{
    use HasFactory;

    protected $table = 'moderation_rules';
    protected $hidden = ['created_at'];
    protected $guarded = [];
    protected $appends = ['type'];
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';

    protected $casts = [
        'uuid' => 'string'
    ];

    public function getTypeAttribute()
    {
        return 'moderation_rule';
    }

    public function communities(): HasMany
    {
        return $this->hasMany(Community::class, 'moderation_rule_uuid', 'uuid');
    }

    public function restrictedWords(): HasMany
    {
        return $this->hasMany(RestrictedWord::class, 'moderation_rule_uuid', 'uuid');
    }
}
