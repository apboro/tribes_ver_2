<?php

namespace App\Http\Controllers\API;

use App\Exceptions\StatisticException;
use App\Filters\API\MembersChartFilter;
use App\Filters\API\MembersFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\TeleDialogStatRequest;
use App\Http\Resources\Statistic\MemberChartsResource;
use App\Http\Resources\Statistic\MemberResource;
use App\Http\Resources\Statistic\MembersResource;
use App\Repositories\Statistic\TeleDialogStatisticRepositoryContract;

use App\Services\File\FileSendService;
use Symfony\Component\HttpFoundation\StreamedResponse;


class TeleDialogStatisticController extends Controller
{
    private TeleDialogStatisticRepositoryContract $statisticRepository;
    private FileSendService $fileSendService;

    public function __construct(
        TeleDialogStatisticRepositoryContract $statisticRepository,
        FileSendService $fileSendService
    )
    {
        $this->statisticRepository = $statisticRepository;
        $this->fileSendService = $fileSendService;
    }

    public function members(TeleDialogStatRequest $request, MembersFilter $filter): MembersResource
    {
        $members = $this->statisticRepository->getMembersList($request->get('community_id'),$filter);

        return (new MembersResource($members))->forApi();
    }

    public function memberCharts(TeleDialogStatRequest $request, MembersChartFilter $filter): MemberChartsResource
    {
        $chartJoiningData = $this->statisticRepository->getJoiningMembersChart($request->get('community_id'),$filter);
        $chartExitingData = $this->statisticRepository->getExitingMembersChart($request->get('community_id'),$filter);
        $chartJoiningData->includeChart($chartExitingData,['users' => 'exit_users']);
        return (new MemberChartsResource($chartJoiningData));
    }

    /**
     * @throws StatisticException
     */
    public function exportMembers(TeleDialogStatRequest $request, MembersFilter $filter): StreamedResponse
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
                'attribute' => 'accession_date',
                'title' => 'Дата вступления'
            ],

            [
                'attribute' => 'exit_date',
                'title' => 'Дата выхода'
            ],

            [
                'attribute' => 'c_messages',
                'title' => 'Количество сообщений'
            ],
            [
                'attribute' => 'c_put_reactions',
                'title' => 'Количество реакций оставил'
            ],
            [
                'attribute' => 'c_got_reactions',
                'title' => 'Количество реакций получил'
            ],
        ];
        $type = $request->get('export_type');
        $membersBuilder = $this->statisticRepository->getMembersListForFile($request->get('community_id'),$filter);

        return $this->fileSendService->sendFile($membersBuilder,$columnNames,MemberResource::class,$type,'members');
    }
}