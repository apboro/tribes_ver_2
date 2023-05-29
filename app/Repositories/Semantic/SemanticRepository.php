<?php

namespace App\Repositories\Semantic;

use App\Http\Requests\Semantic\CalculateProbabilityRequest;
use App\Models\Semantic;
use App\Models\SemanticClass;
use Carbon\Carbon;

class SemanticRepository
{
    public function calculateProbability(CalculateProbabilityRequest $request): array
    {
        $result = [];

        // Carbon период начала сбора статистики
        $period = $this->getDataByPeriod($request->get('period'));

        // N в формуле. количество записей в semantics за указанный период.
        $semanticsForPeriod = Semantic::query()->where('chat_id', $request->get('chat_id'))->where('messages_from_datetime', '>', $period)->count();

        // E в формуле. Сумма всех class_probability для class_name в заданный период.
        $uniqueNames = SemanticClass::query()->select('class_name')->distinct()->get()->toArray();
        foreach ($uniqueNames as $uniqueName) {
            $probability = SemanticClass::query()->where('class_name', $uniqueName['class_name'])->get()->sum('class_probability');
            $result[$uniqueName['class_name']] = number_format($probability / $semanticsForPeriod, 3);
        }

        return $result;
    }

    private function getDataByPeriod(string $period)
    {
        switch ($period) {
            case 'день':
                $date = Carbon::now()->subDay()->startOfDay();
                break;
            case 'неделя':
                $date = Carbon::now()->startOfWeek();
                break;
            case 'месяц':
                $date = Carbon::now()->startOfMonth();
                break;
            case 'год':
                $date = Carbon::now()->startOfYear();
                break;
            default:
                $date = null;
                break;
        }

        return $date;
    }
}
