<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommunityRule extends Model
{
    use HasFactory;

    protected $table = 'moderation_rules';
    protected $hidden = ['created_at', 'updated_at'];
    protected $guarded = [];

    public function communities(): HasMany
    {
        return $this->hasMany(Community::class);
    }

    public function restrictedWords(): HasMany
    {
        return $this->hasMany(RestrictedWord::class);
    }
}
