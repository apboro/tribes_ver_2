<?php

namespace App\Models;


use App\Models\Knowledge\Knowledge;
use App\Models\Knowledge\Question;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $name
 * @property int $owner_id
 * @property int $knowledge_id
 */
class QuestionCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'owner_id',
        'knowledge_id',
    ];

    public function knowledge(): BelongsTo
    {
        return $this->belongsTo(Knowledge::class, 'knowledge_id', 'id');
    }

    public function questionsCount(int $id): int
    {
        return Question::query()->where('category_id', $id)->count();
    }
}
