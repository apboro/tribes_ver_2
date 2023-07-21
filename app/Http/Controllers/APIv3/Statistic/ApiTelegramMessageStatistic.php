<?php

namespace App\Http\Controllers\APIv3\Statistic;

use App\Http\ApiRequests\Statistic\ApiMessageExportStatisticRequest;
use App\Http\ApiRequests\Statistic\ApiMessageStatisticChartRequest;
use App\Http\ApiRequests\Statistic\ApiMessageUserStatisticRequest;
use App\Http\ApiResources\ApiMemberChartsResource;
use App\Http\ApiResources\ExportMessageResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Statistic\MemberChartsResource;
use App\Repositories\Statistic\TelegramMessageStatisticRepository;
use App\Services\File\FilePrepareService;
use App\Services\File\FileSendService;

class ApiTelegramMessageStatistic extends Controller
{
    private TelegramMessageStatisticRepository $statisticRepository;
    private FilePrepareService $filePrepareService;

    public function __construct(
        TelegramMessageStatisticRepository $statisticRepository,
        FilePrepareService $filePrepareService
    )
    {
        $this->statisticRepository = $statisticRepository;
        $this->filePrepareService = $filePrepareService;
    }

    public function messageCharts(ApiMessageStatisticChartRequest $request): ApiResponse
    {
        $chartMessagesData = $this->statisticRepository->getMessageChart($request);
        $chartMessagesTonality = $this->statisticRepository->getMessagesTonality($request);

        $messages = $this->statisticRepository->getMessagesList($request->input('community_ids') ?? [], $request);
        $message_members_statistic = $messages->get();
        $user_messages_chart = !empty($request->telegram_users_id) ? $this->statisticRepository->getUserMessageChart($request) : null;

        return ApiResponse::common([
            'messages_tonality' => $chartMessagesTonality,
            'message_statistic' => $chartMessagesData,
            'total_messages' => $chartMessagesData->sum('messages'),
            'message_members_statistic' => $message_members_statistic,
            'user_messages_chart' => $user_messages_chart ? (new ApiMemberChartsResource($user_messages_chart)) : null,
        ]);
    }

    public function exportMessages(ApiMessageExportStatisticRequest $request)
    {
        $columnNames = $this->statisticRepository::EXPORT_FIELDS;

        $builder = $this->statisticRepository->getListForFile($request->input('community_ids') ?? [],$request);

        return $this->filePrepareService->prepareFile(
            $builder->orderBy('message_date'),
            $columnNames,
            ExportMessageResource::class,
            $request->get('export_type', 'csv'),
            'messages'
        );
    }

}
