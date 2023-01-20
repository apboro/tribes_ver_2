<?php

namespace App\Models\Knowledge;

use App\Models\Community;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * @property integer $id
 * @property string $variant
 * @property integer $community_id
 * @property string $title
 *
 */
class Category extends Model
{
    use HasFactory;

    protected $table = 'knowledge.categories';

    public function community(): BelongsTo
    {
        return $this->belongsTo(Community::class, 'community_id');
    }

    public function quiestions(): HasMany
    {
        return $this->hasMany(Question::class, 'question_id');
    }
}
