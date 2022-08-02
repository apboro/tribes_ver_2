<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Community;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class StatisticController extends Controller
{
    protected $rank = [
        'y' => 'year',
        'm' => 'month',
        'd' => 'day'
    ];


    public function getHostsPeriod(Community $community, $count, $rank, $beforeTime = NULL)
    {   
        $method = 'getHostsPeriod';
        return json_encode($this->getData($method, $community, $count, $rank, $beforeTime));
    }

    public function getTotalTariff(Community $community, $count, $rank, $beforeTime = NULL)
    {   
        $method = 'getTotalTariffPeriod';
        return json_encode($this->getData($method, $community, $count, $rank, $beforeTime));
    }

    public function getTotalDonate(Community $community, $count, $rank, $beforeTime = NULL)
    {   
        $method = 'getTotalDonatePeriod';
        return json_encode($this->getData($method, $community, $count, $rank, $beforeTime));
    }

    public function getSumTariff(Community $community, Request $request)
    {
        $count = $request['count'];
        $rank = $request['rank'];
        $beforeTime = $request['beforeTime'];
        $method = 'getTariffSumPeriod';
        return response()->json($this->getData($method, $community, $count, $rank, $beforeTime));
    }

    public function getSumDonate(Community $community, $count, $rank, $beforeTime = NULL)
    {   
        $method = 'getDonateSumPeriod';
        return json_encode($this->getData($method, $community, $count, $rank, $beforeTime));
    }

    protected function getData($method, $community, $count, $rank, $beforeTime = NULL)
    {
        switch ($rank) {
            case 'd':
                $format = 'Y-m-d';
                $formatEnd = 'Y-m-d';
                break;
            case 'm':
                $format = 'Y-m-1';
                $formatEnd = 'Y-m-t';
                break;
            case 'y': 
                $format = 'Y-01-01';
                $formatEnd = 'Y-12-t';
                break;
            default:
                $format = 'Y-m-d';
                $formatEnd = 'Y-m-d';
                break;
        }

        $endTime = ($beforeTime) ? $beforeTime : date('Y-m-d');
            $dateStart = Carbon::parse($endTime);
            $dateEnd = Carbon::parse($endTime);
            
            $data = [];
            for ($i = 1; $i <= $count; $i++) {
                $timeStart = $dateStart->format($format);
                $timeEnd = $dateEnd->format($formatEnd);
                $data[] = [
                    'date' => $timeEnd,
                    'value' => $community->statistic->repository()->$method($timeStart, $timeEnd)
                ];
                $dateStart->modify('-1' . $this->rank[$rank]);
                $dateEnd->modify('-1' . $this->rank[$rank]);
            }
            
            if (count($data) > 30) {
                return 'Запрос не может привышать 30 значений';
            } else return $data;

    }
}
