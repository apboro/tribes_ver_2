<?php

namespace App\Models\Knowledge;

use App\Models\Community;
use App\Models\QuestionCategory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property array $community_ids
 * @property string $name
 * @property array $question_ids
 * @property string $status
 * @property Carbon $question_in_chat_lifetime
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @property boolean $in_link_publish
 */
class Knowledge extends Model
{
    use HasFactory;

    protected $fillable = [
        'uri_hash',
        'community_ids',
        'owner_id',
        'name',
        'question_ids',
    ];

    protected $casts = [
        'question_ids' => 'array',
        'community_ids' => 'array'
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'knowledge_id', 'id');
    }

    public function communities(): HasMany
    {
        return $this->hasMany(Community::class);
    }

    public function getQuestionsWithAnswers(int $knowledgeId)
    {
        return Question::query()->where('knowledge_id', $knowledgeId)->with('answer')->get();
    }

    public function categories()
    {
        return $this->hasMany(QuestionCategory::class);
    }
}
