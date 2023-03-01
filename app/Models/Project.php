<?php

namespace App\Models;

use App\Filters\QueryFilter;
use Carbon\Carbon;
use Database\Factories\ProjectFactory;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method ProjectFactory factory()
 * @method Builder filter()
 * @property string $title
 * @property int $id
 * @property int $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title',
    ];

    /**
     * @throws Exception
     */
    public function scopeFilter(Builder $builder, QueryFilter $filters)
    {
        return $filters->apply($builder);
    }

    public function communities(): HasMany
    {
        return $this->hasMany(Community::class, 'project_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}