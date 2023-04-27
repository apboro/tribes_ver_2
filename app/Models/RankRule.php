<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property int $user_id
 * @property array $rank_ids
 * @property Carbon $period_until_reset
 * @property boolean rank_change_in_chat
 * @property string $rank_change_message
 * @property boolean $first_rank_in_chat
 * @property string $first_rank_message
 *
 * @property Rank $ranks
 */
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

    public function ranks(): HasMany
    {
        return $this->hasMany(Rank::class, 'rank_rule_id', 'id');
    }

    public function getRanks(int $ruleId)
    {
        return Rank::query()->where('rank_rule_id', $ruleId)->select('id', 'name', 'reputation_value_to_achieve')->get();
    }
}
