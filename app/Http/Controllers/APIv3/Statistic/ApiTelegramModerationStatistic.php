<?php

namespace App\Http\Controllers\APIv3\Statistic;

use App\Http\ApiRequests\Statistic\ApiModerationStatisticChartRequest;
use App\Http\ApiRequests\Statistic\ApiModerationStatisticExportRequest;
use App\Http\ApiRequests\Statistic\ApiModerationStatisticRequest;
use App\Http\ApiResources\ExportModerationResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Repositories\Statistic\TelegramModerationStatisticRepository;
use App\Services\File\FilePrepareService;
use App\Services\File\FileSendService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApiTelegramModerationStatistic extends Controller
{
    private TelegramModerationStatisticRepository $statisticRepository;
    private FilePrepareService $filePrepareService;

    /**
     * @param TelegramModerationStatisticRepository $statisticRepository
     * @param FileSendService $fileSendService
     */

    public function __construct(
        TelegramModerationStatisticRepository $statisticRepository,
        FilePrepareService $filePrepareService
    )
    {
        $this->statisticRepository = $statisticRepository;
        $this->filePrepareService = $filePrepareService;
    }


    /**
     * @param ApiModerationStatisticChartRequest $request
     * @return ApiResponse
     */

    public function userList(ApiModerationStatisticChartRequest $request): ApiResponse
    {

        $members = $this->statisticRepository->getMemberList($request->input('community_ids') ?? [], $request);

        $current_moderation_chart = $this->statisticRepository->getModerationChart($request);
        $current_moderation_chart['members'] = $members->get();

        return ApiResponse::common($current_moderation_chart);
    }

    public function exportModeration(ApiModerationStatisticExportRequest $request)
    {
        $columnNames = $this->statisticRepository::EXPORT_FIELDS;

        $membersBuilder = $this->statisticRepository->getListForFile($request->input('community_ids') ?? [], $request);

        return $this->filePrepareService->prepareFile(
            $membersBuilder,
            $columnNames,
            ExportModerationResource::class,
            $request->get('export_type', 'csv'),
            'moderation');
    }
}
