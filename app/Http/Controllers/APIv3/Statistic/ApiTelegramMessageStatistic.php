<?php

namespace App\Http\Controllers\APIv3\Statistic;

use App\Helper\QueryHelper;
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
use Illuminate\Support\Carbon;
use Log;

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
        $period = QueryHelper::buildPeriodDates($request->input('period', 'day'));
        $communityId = $request->community_id ?? null;

//        $chartMessagesData = $this->statisticRepository->getMessageChart($request);
        $chartMessagesData = $this->statisticRepository->getMessagesStatistic($period['start'], $period['end'], $communityId);
        $chartMessagesTonality = $this->statisticRepository->getMessagesTonality($request);

        $messages = $this->statisticRepository->getMessagesList($request->input('community_ids') ?? [], $request);
        $message_members_statistic = $messages->get();
        $user_messages_chart = !empty($request->telegram_users_id) ? $this->statisticRepository->getUserMessageChart($request) : null;

        $totalMessages = array_reduce($chartMessagesData, function ($carry, $item) {
            return $carry + $item->count ?? 0;
        }) ?? 0;

        return ApiResponse::common([
            'messages_tonality' => $chartMessagesTonality,
            'message_statistic' => $chartMessagesData,
            'total_messages' => $totalMessages,
            'message_members_statistic' => $message_members_statistic,
            'user_messages_chart' => $user_messages_chart ? (new ApiMemberChartsResource($user_messages_chart)) : null,
        ]);
    }

    public function exportMessages(ApiMessageExportStatisticRequest $request)
    {
        $columnNames = $this->statisticRepository::EXPORT_FIELDS;

        $builder = $this->statisticRepository->getListForFile($request->input('community_ids') ?? [],$request);

        return $this->filePrepareService->prepareFile(
            $builder,
            $columnNames,
            ExportMessageResource::class,
            $request->get('export_type', 'csv'),
            'messages'
        );
    }

}
