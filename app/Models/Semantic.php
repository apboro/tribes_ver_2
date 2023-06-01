<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $chat_id
 * @property Carbon $messages_from_datetime
 * @property Carbon $messages_to_datetime
 * @property string $llm_answer
 * @property float $sentiment
 * @property string $sentiment_label
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Semantic extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'messages_from_datetime',
        'messages_to_datetime',
        'llm_answer',
        'sentiment',
        'sentiment_label',
    ];
}
