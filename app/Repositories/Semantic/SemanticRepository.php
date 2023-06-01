<?php

namespace App\Repositories\Semantic;

use App\Http\Requests\Semantic\ApiCalculateProbabilityRequest;
use App\Models\Community;
use App\Models\Semantic;
use App\Models\SemanticClass;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SemanticRepository
{
    const DAY = 'day';
    const WEEK = 'week';
    const MONTH = 'month';
    const YEAR = 'year';

    public function calculateProbability(ApiCalculateProbabilityRequest $request)
    {
        // Carbon период начала сбора статистики
        $start = $this->getStartDate($request->input('period') ?? 'week')->toDateTimeString();
        $end = $this->getEndDate()->toDateTimeString();

        $community = Community::find($request->get('community_id'));
        if (!$community){
            return false;
        }
        $chat_id = $community->connection->chat_id;

        $semanticsForPeriod = Semantic::query()
            ->where('chat_id', $chat_id)
            ->where('messages_from_datetime', '>=', $start)
            ->where('messages_from_datetime', '<=', $end)
            ->get();
        $result = [];
        foreach ($semanticsForPeriod as $semantic) {
            foreach ($semantic->classes as $class) {
                $key = $class['class_name'];
                if (!isset($result[$key])) {
                    $result[$key] = 0;
                }
                $result[$key] += $class['class_probability'];
            }
        }
        $total = 0;
        foreach ($result as $value) {
            $total += $value;
        }
        $totalResult = [];
        foreach ($result as $key => $value) {
            $totalResult[$key] = round($value / $total * 100);
        }

        return $totalResult;
    }

    public function getStartDate($value): ?Carbon
    {
        switch ($value) {
            case self::DAY:
                return $this->getEndDate()->sub('23 hours')->startOfHour();
            case self::MONTH:
                return $this->getEndDate()->sub('30 days')->startOfDay();
            case self::YEAR:
                return $this->getEndDate()->sub('11 months')->startOfMonth();
            case self::WEEK:
                return $this->getEndDate()->sub('6 days')->startOfDay();
        }
        return null;
    }

    public function getEndDate(): Carbon
    {
        return Carbon::now()->sub('1 day')->endOfDay();
    }

}
