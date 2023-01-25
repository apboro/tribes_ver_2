<?php

namespace App\Models\Knowledge;

use App\Filters\API\QuestionsFilter;
use App\Filters\QueryFilter;
use App\Helper\PseudoCrypt;
use App\Models\Community;
use App\Traits\Searchable;
use Database\Factories\Knowledge\QuestionFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** @method QuestionFactory factory() */

/**
 * @method Builder filter()
 * @property mixed $community_id
 * @property mixed $context
 * @property bool|mixed $is_draft
 * @property false|mixed $is_public
 * @property mixed $author_id
 * @property mixed|string $uri_hash
 * @property int|mixed $c_enquiry
 * @property mixed $id
 * @property int $category_id
 */
class Question extends Model
{
    use HasFactory, Searchable;

    public $useSearchType = 'Question';

    protected $connection = 'main';

    protected $fillable = [
        'community_id',
        'author_id',
        'uri_hash',
        'is_draft',
        'is_public',
        'c_enquiry',
        'context',
        'category_id'
    ];

    protected $table = 'knowledge.questions';

    /**
     *   Question::filter($filters)
     */
    public function scopeFilter(Builder $builder, QueryFilter $filters)
    {
        return $filters->apply($builder);
    }

    public function answer()
    {
        return $this->hasOne(Answer::class, 'question_id', 'id');
    }

    public function getShortAnswerAttribute()
    {
        $answer = $this->answer()->first();

        if (strlen($answer->context) > 200) {
            $answer->context = mb_substr($answer->context, 0, 199) . '...';
        }
        return $answer;
    }


    public function community()
    {
        return $this->belongsTo(Community::class, 'community_id');
    }

    public function getLink()
    {
        return $this->uri_hash;
    }

    public function getPublicLink()
    {
        $hash = PseudoCrypt::hash($this->community_id);
        $question = $this->id;

        return route('public.knowledge.view', compact('hash', 'question'));
    }

    public function isUnpublishable(): bool
    {
        return empty($this->context) || $this->is_draft;
    }

    /**
     * Автоматически снимает с публикации если статус черновика true
     * @param bool $draft
     * @return void
     */
    public function setDraft(bool $draft): void
    {
        $this->is_draft = $draft;
        if($draft) {
            $this->is_public = false;
        }
    }

    public function setPublic(bool $publish): void
    {
        $this->is_public = $publish;
        if($publish) {
            $this->is_draft = false;
        }
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
