<?php

namespace App\Http\Controllers\API;

use App\Filters\API\TeleMessagesChartFilter;
use App\Filters\API\TeleMessagesFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\TeleDialogStatRequest;
use App\Http\Requests\API\TeleMessageStatRequest;
use App\Http\Resources\Statistic\TelegramMessageResource;
use App\Http\Resources\Statistic\TelegramMessages;
use App\Http\Resources\Statistic\MemberChartsResource;
use App\Repositories\Statistic\TeleMessageStatisticRepositoryContract;
use App\Services\File\FileSendService;
use Illuminate\Http\Request;

class TeleMessageStatisticController extends Controller
{
    private TeleMessageStatisticRepositoryContract $statisticRepository;
    private FileSendService $fileSendService;

    public function __construct(
        TeleMessageStatisticRepositoryContract $statisticRepository,
        FileSendService $fileSendService
    )
    {
        $this->statisticRepository = $statisticRepository;
        $this->fileSendService = $fileSendService;
    }

    public function messages(TeleMessageStatRequest $request, TeleMessagesFilter $filter)
    {
        $messages = $this->statisticRepository->getMessagesList($request->get('community_id'), $filter);
        return (new TelegramMessages($messages))->forApi();
    }

    public function messageCharts(TeleMessageStatRequest $request, TeleMessagesChartFilter $filter)
    {
        $chartMessagesData = $this->statisticRepository->getMessageChart($request->get('community_id'),$filter);
        $chartUtilityMessageData = $this->statisticRepository->getUtilityMessageChart($request->get('community_id'),$filter);
        $chartMessagesData->includeChart($chartUtilityMessageData,['messages' => 'utility_messages']);
        
        return (new MemberChartsResource($chartMessagesData));
    }

    public function exportMessages(TeleDialogStatRequest $request, TeleMessagesFilter $filter)
    {
        $columnNames = [
            [
                'attribute' => 'name',
                'title' => 'Имя'
            ],
            [
                'attribute' => 'nick_name',
                'title' => 'Никнейм'
            ],
            [
                'attribute' => 'text',
                'title' => 'Текст сообщения'
            ],

            [
                'attribute' => 'answers',
                'title' => 'Количество ответов'
            ],
            [
                'attribute' => 'utility',
                'title' => 'Полезность'
            ],
            [
                'attribute' => 'count_reactions',
                'title' => 'Количество реакций'
            ],
            [
                'attribute' => 'message_date',
                'title' => 'Дата публикации'
            ],
        ];
        $type = $request->get('export_type');
        $builder = $this->statisticRepository->getMessagesListForFile($request->get('community_id'),$filter);

        return $this->fileSendService->sendFile($builder,TelegramMessageResource::class,$columnNames,$type,'messages');
    }
}
