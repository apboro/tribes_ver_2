<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RankRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'rank_ids',
        'period_until_reset',
        'rank_change_in_chat',
        'rank_change_message',
        'first_rank_in_chat',
        'first_rank_message',
    ];

    protected $casts = [
        'rank_ids' => 'array'
    ];

    protected $guarded = [];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
