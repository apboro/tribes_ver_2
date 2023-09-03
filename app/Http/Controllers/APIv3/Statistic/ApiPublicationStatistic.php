<?php

namespace App\Http\Controllers\APIv3\Statistic;

use App\Http\ApiRequests\Statistic\{
    ApiPublicationViewTimeRequest,
    ApiPublicationStatisticRequest,
    ApiPublicationStatisticExportRequest
    };
use App\Http\ApiResponses\ApiResponse;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Statistic\StatisticRepository;
use App\Services\File\PrepareXlsByCollectionService;

class ApiPublicationStatistic
{

    private StatisticRepository $statistic;

    public function __construct(StatisticRepository $statistic)
    {
        $this->statistic = $statistic;
    }

    public function saveViewTime(ApiPublicationViewTimeRequest $request)
    {
        $this->statistic->saveViewTimePublication($request->publication_id, $request->seconds);

        return ApiResponse::success('common.success');
    }
    
    private function getStatistic($request, $limit = 5)
    {
        $user = Auth::user();
        $sort = $request->sort ?? 'desc';
        $period = $request->period ??  'week';

        return $this->statistic->getStstisticPublication($user, $period, $sort, $limit);
    }

    public function statistic(ApiPublicationStatisticRequest $request)
    {
        return ApiResponse::common($this->getStatistic($request));
    }

    function export(ApiPublicationStatisticExportRequest $request, PrepareXlsByCollectionService $xls)
    {
        $data = $this->getStatistic($request, null);
        $listNames = ['host' => 'Посетители', 'view' => 'Просмотры', 'time' => 'Время'];
        $columnNames = [
            'host' => ['publication_id' => 'ID публикации', 'host' => 'Посетители', 'title' => 'Название публикации'],
            'view' => ['publication_id' => 'ID публикации', 'view' => 'Просмотры', 'title' => 'Название публикации'],
            'time' => ['publication_id' => 'ID публикации', 'seconds' => 'Время просмотра', 'title' => 'Название публикации']
        ];
        $fileName = 'Spodial_Аналитика_Публикации_' . date('Y-m-d-H-i-s') . '.xlsx';

        $xls->prepareManyPagesXLS($fileName, $data, $listNames, $columnNames);

        return [
            'result' => true,
            'file_path' => "/storage/statistic_files/" . $fileName
        ];
    }

}
