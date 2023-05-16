<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $restrictedWords
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
