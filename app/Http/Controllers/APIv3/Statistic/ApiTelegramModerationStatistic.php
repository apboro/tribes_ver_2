<?php

namespace App\Http\Controllers\APIv3\Statistic;

use App\Http\ApiRequests\Statistic\ApiModerationStatisticChartRequest;
use App\Http\ApiRequests\Statistic\ApiModerationStatisticExportRequest;
use App\Http\ApiRequests\Statistic\ApiModerationStatisticRequest;
use App\Http\ApiResources\ExportModerationResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Repositories\Statistic\TelegramModerationStatisticRepository;
use App\Services\File\FileSendService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApiTelegramModerationStatistic extends Controller
{
    private TelegramModerationStatisticRepository $statisticRepository;
    private FileSendService $fileSendService;

    /**
     * @param TelegramModerationStatisticRepository $statisticRepository
     * @param FileSendService $fileSendService
     */

    public function __construct(
        TelegramModerationStatisticRepository $statisticRepository,
        FileSendService                       $fileSendService
    )
    {
        $this->statisticRepository = $statisticRepository;
        $this->fileSendService = $fileSendService;
    }


    /**
     * @param ApiModerationStatisticChartRequest $request
     * @return ApiResponse
     */

    public function userList(ApiModerationStatisticChartRequest $request): ApiResponse
    {

        $members = $this->statisticRepository->getMemberList($request->input('community_ids') ?? [], $request);

        $current_moderation_chart = $this->statisticRepository->getModerationChart($request);

        return ApiResponse::common([$current_moderation_chart, 'members' => $members->get()]);
    }

    public function exportModeration(ApiModerationStatisticExportRequest $request): StreamedResponse
    {
        $columnNames = $this->statisticRepository::EXPORT_FIELDS;

        $membersBuilder = $this->statisticRepository->getListForFile($request->input('community_ids') ?? []);


        return $this->fileSendService->sendFile(
            $membersBuilder,
            $columnNames,
            ExportModerationResource::class,
            $request->get('export_type', 'csv'),
            'moderation');
    }
}
