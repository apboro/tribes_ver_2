<?php

namespace App\Http\Controllers\APIv3\Statistic;

use App\Http\ApiRequests\ApiRequest;
use App\Http\ApiRequests\Statistic\ApiMessageExportStatisticRequest;
use App\Http\ApiRequests\Statistic\ApiMessageStatisticChartRequest;
use App\Http\ApiRequests\Statistic\ApiMessageUserStatisticRequest;
use App\Http\ApiResources\ExportMessageResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Community;
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

    public function messages(ApiMessageUserStatisticRequest $request)
    {

        $messages = $this->statisticRepository->getMessagesList($request->input('community_ids') ?? []);
        $count = $messages->count();
        $message_members_statistic = $messages->skip($request->input('offset') ?? 0)
            ->take($request->input('limit') ?? 15)
            ->get();

        return ApiResponse::listPagination(
            ['Access-Control-Expose-Headers' => 'Items-Count', 'Items-Count' => $count]
        )->items($message_members_statistic);
    }

    public function messageCharts(
        ApiMessageStatisticChartRequest $request
    ): ApiResponse
    {
        $chartMessagesData = $this->statisticRepository->getMessageChart(
            $request
        );
        $chartMessagesTonality = $this->statisticRepository->getMessagesTonality($request);
        return ApiResponse::common([
            'messages_tonality' => $chartMessagesTonality,
            'message_statistic' => $chartMessagesData,
            'total_messages' => $chartMessagesData->sum('messages'),
        ]);
    }

    public function exportMessages(
        ApiMessageExportStatisticRequest $request
    )
    {
        $columnNames = [
            [
                'attribute' => 'telegram_user_id',
                'title' => 'Telegram user id'
            ],
            [
                'attribute' => 'group_chat_id',
                'title' => 'Group chat id'
            ],
            [
                'attribute' => 'name',
                'title' => 'Имя'
            ],
            [
                'attribute' => 'nick_name',
                'title' => 'Никнейм'
            ],
            [
                'attribute' => 'count_messages',
                'title' => 'Количество сообщений'
            ],
        ];

        $builder = $this->statisticRepository->getMessagesListForFile(
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
