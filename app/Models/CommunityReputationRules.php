<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $uuid
 */
class CommunityReputationRules extends Model
{
    use HasFactory;

    protected $guarded=[];

    protected $appends = ['type'];
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';

    protected $casts = [
        'uuid' => 'string'
    ];

    public function getTypeAttribute()
    {
        return 'reputation_rule';
    }

    public function communities():HasMany
    {
        return $this->hasMany(
            Community::class,
            'reputation_rules_uuid',
            'uuid'
        );
    }

    public function reputationUpWords(): HasMany
    {
        return $this->hasMany(ReputationKeyword::class)->
                      where('direction','=',1);
    }

    public function reputationDownWords(): HasMany
    {
        return $this->hasMany(ReputationKeyword::class)->
                      where('direction','=',-1);
    }

    public function reputationWords(): HasMany
    {
        return $this->hasMany(ReputationKeyword::class);
    }
}
