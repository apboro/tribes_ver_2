<?php

namespace App\Models\Knowledge;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $context
 * @property string $image
 */
class Answer extends Model
{
    use HasFactory, Searchable;

    public $useSearchType = 'Answer';

    protected $fillable =[
        'context',
        'image',
    ];

    function question(): BelongsTo
    {
        return $this->belongsTo(Question::class,'id', 'question_id');
    }
}
