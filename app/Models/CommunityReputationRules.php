<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommunityReputationRules extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function communities():HasMany
    {
        return $this->hasMany(
            Community::class,
            'reputation_rules_id',
            'id'
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
}
