<?php

namespace App\Models\Knowledge;

use App\Models\Community;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionAI extends Model
{
    use HasFactory;

    protected $table = 'questions_ai';

    public function communities()
    {
        return $this->hasOne(Community::class, 'id', 'community_id');
    }

    public static function setMovedQuestionStatus(int $questionAiId, int $questionId, int $communityId): void
    {
        $questionAi = self::where('id', '=', $questionAiId)->first();
        $questionAi->questions_id = $questionId;
        $questionAi->status = 2;
        $questionAi->community_id = $communityId;
        $questionAi->save();
    }
}
