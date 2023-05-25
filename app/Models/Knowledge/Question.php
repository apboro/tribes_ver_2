<?php

namespace App\Models\Knowledge;

use App\Filters\QueryFilter;
use App\Helper\PseudoCrypt;
use App\Models\QuestionCategory;
use App\Traits\Searchable;
use Carbon\Carbon;
use Database\Factories\Knowledge\QuestionFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/** @method QuestionFactory factory() */

/**
 * @method Builder filter()
 * @property int $id
 * @property string $status
 * @property int $category_id
 * @property int $knowledge_id
 * @property string $overlap
 * @property string context
 * @property int $answer_id
 * @property int $author_id
 * @property string $uri_hash
 * @property string $image
 * @property int $c_enquiry
 * @property Carbon $updated_at
 * @property Carbon $created_at
 *
 * @property Answer $answer
 */
class Question extends Model
{
    use HasFactory, Searchable;

    public $useSearchType = 'Question';

    protected $fillable = [
        'status',
        'knowledge_id',
        'category_id',
        'overlap',
        'context',
        'answer_id',
        'author_id',
        'uri_hash',
        'c_enquiry',
        'image',
    ];

    /**
     *   Question::filter($filters)
     */
    public function scopeFilter(Builder $builder, QueryFilter $filters)
    {
        return $filters->apply($builder);
    }

    public function answer(): HasOne
    {
        return $this->hasOne(Answer::class, 'id', 'answer_id');
    }

    public function getShortAnswerAttribute()
    {
        /** @var Answer $answer */
        $answer = $this->answer()->first();

        if (strlen($answer->context) > 200) {
            $answer->context = mb_substr($answer->context, 0, 199) . '...';
        }
        return $answer;
    }

    public function getLink(): string
    {
        return $this->uri_hash;
    }

    public function getPublicLink()
    {
        $hash = PseudoCrypt::hash($this->community_id);
        $question = $this->id;

        return route('public.knowledge.view', compact('hash', 'question'));
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function knowledge(): BelongsTo
    {
        return $this->belongsTo(Knowledge::class, 'knowledge_id', 'id');
    }

    public function questionCategory(): HasOne
    {
        return $this->hasOne(QuestionCategory::class, 'id', 'category_id');
    }

    public function getCategoryName(int $id): string
    {
        return QuestionCategory::query()->where('id', $id)->pluck('name')->first();
    }
}
