<?php

namespace App\Http\Controllers\API;

use App\Filters\API\TeleMessagesChartFilter;
use App\Filters\API\TeleMessagesFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\TeleMessageStatRequest;
use App\Http\Resources\Statistic\TelegramMessages;
use App\Http\Resources\Statistic\MemberChartsResource;
use App\Repositories\Statistic\TeleMessageStatisticRepositoryContract;
use Illuminate\Http\Request;

class TeleMessageStatisticController extends Controller
{
    private TeleMessageStatisticRepositoryContract $statisticRepository;

    public function __construct(TeleMessageStatisticRepositoryContract $statisticRepository)
    {
        $this->statisticRepository = $statisticRepository;
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
}
