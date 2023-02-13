<?php

namespace App\Models\Knowledge;

use App\Models\Community;
use App\Traits\Searchable;
use Database\Factories\Knowledge\AnswerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** @method AnswerFactory factory()
 * @property mixed $question_id
 * @property mixed $community_id
 * @property mixed $context
 * @property mixed $is_draft
 */
class Answer extends Model
{
    use HasFactory, Searchable;

    public $useSearchType = 'Answer';

    protected $fillable =[
        'question_id',
        'community_id',
        'is_draft',
        'context',
    ];
    //protected $connection = 'knowledge';

    protected $table = 'answers';

    protected $casts = [
        'tags' => 'json',
    ];

    function question()
    {
        return $this->hasOne(Question::class);
    }

    function community()
    {
        return $this->belongsTo(Community::class, 'community_id');
    }
}
