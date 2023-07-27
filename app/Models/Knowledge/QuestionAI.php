<?php

namespace App\Models\Knowledge;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionAI extends Model
{
    use HasFactory;

    protected $table = 'questions_ai';

    public static function setMovedQuestionStatus(int $questionAiId, int $questionId ): void
    {
        $questionAi = self::where('id', '=', $questionAiId)->first();
        $questionAi->questions_id = $questionId;
        $questionAi->status = 2;
        $questionAi->save();
    }
}
