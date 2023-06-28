<?php

namespace App\Http\Controllers\APIv3\Statistic;

use App\Http\ApiRequests\Statistic\ApiMessageExportStatisticRequest;
use App\Http\ApiRequests\Statistic\ApiMessageStatisticChartRequest;
use App\Http\ApiRequests\Statistic\ApiMessageUserStatisticRequest;
use App\Http\ApiResources\ExportMessageResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Repositories\Statistic\TelegramMessageStatisticRepository;
use App\Services\File\FileSendService;

class ApiTelegramMessageStatistic extends Controller
{
    private TelegramMessageStatisticRepository $statisticRepository;
    private FileSendService $fileSendService;

    public function __construct(
        TelegramMessageStatisticRepository $statisticRepository,
        FileSendService                    $fileSendService
    )
    {
        $this->statisticRepository = $statisticRepository;
        $this->fileSendService = $fileSendService;
    }

    public function messageCharts(ApiMessageStatisticChartRequest $request): ApiResponse
    {
        $chartMessagesData = $this->statisticRepository->getMessageChart($request);
        $chartMessagesTonality = $this->statisticRepository->getMessagesTonality($request);

        $messages = $this->statisticRepository->getMessagesList($request->input('community_ids') ?? [], $request);
        $message_members_statistic = $messages->get();

        return ApiResponse::common([
            'messages_tonality' => $chartMessagesTonality,
            'message_statistic' => $chartMessagesData,
            'total_messages' => $chartMessagesData->sum('messages'),
            'message_members_statistic' => $message_members_statistic
        ]);
    }

    public function exportMessages(ApiMessageExportStatisticRequest $request)
    {
        $columnNames = $this->statisticRepository::EXPORT_FIELDS;

        $builder = $this->statisticRepository->getListForFile(
            $request->input('community_ids') ?? []
        );

        return $this->fileSendService->sendFile(
            $builder->orderBy('count_messages', 'DESC'),
            $columnNames,
            ExportMessageResource::class,
            $request->get('export_type', 'csv'),
            'messages'
        );
    }

}
