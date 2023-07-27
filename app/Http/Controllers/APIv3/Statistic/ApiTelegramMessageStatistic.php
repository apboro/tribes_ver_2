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
        $period = $this->getCurrentPeriodDates($request->input('period', 'day'));

//        $chartMessagesData = $this->statisticRepository->getMessageChart($request);
        $chartMessagesData = $this->statisticRepository->getMessagesStatistic($period['start'], $period['end']);
        $chartMessagesTonality = $this->statisticRepository->getMessagesTonality($request);

        $messages = $this->statisticRepository->getMessagesList($request->input('community_ids') ?? [], $request);
        $message_members_statistic = $messages->get();
        $user_messages_chart = !empty($request->telegram_users_id) ? $this->statisticRepository->getUserMessageChart($request) : null;

        return ApiResponse::common([
            'messages_tonality' => $chartMessagesTonality,
            'message_statistic' => $chartMessagesData,
            'total_messages' => count($chartMessagesData),
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

    /**
     * get current period start end data
     *
     * @param string $criteria
     *
     * @return array
     */
    private function getCurrentPeriodDates(string $criteria): array
    {
        $now = Carbon::now();
        log::info('criteria:' . $criteria);
        switch ($criteria) {
            case 'week':
                $start  = $now->copy()->startOfWeek();
                $end = $now->copy()->endOfWeek();
                break;
            case 'month':
                $start  = $now->copy()->firstOfMonth();
                $end = $now->copy()->endOfMonth();
                break;
            case 'year':
                $start  = $now->copy()->startOfYear();
                $end = $now->copy()->endOfYear();
                break;
            default: // day
                $start = $now;
                $end = $now;
        }

        return [
            'start' => $start->format('d-m-Y 00:00:00'),
            'end' => $end->format('d-m-Y 23:59:59'),
        ];
    }
}
