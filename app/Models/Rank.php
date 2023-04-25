<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rank extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'reputation_value_to_achieve'
    ];

    public function rankRules(): BelongsTo
    {
        return $this->belongsTo(RankRule::class);
    }
}
