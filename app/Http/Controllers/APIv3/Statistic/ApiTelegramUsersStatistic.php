<?php

namespace App\Http\Controllers\APIv3\Statistic;

use App\Exceptions\StatisticException;
use App\Http\ApiRequests\Statistic\ApiMemberStatisticChartsRequest;
use App\Http\ApiRequests\Statistic\ApiMemberStatisticExportRequest;
use App\Http\ApiRequests\Statistic\ApiMemberStatisticRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Statistic\MemberResource;
use App\Repositories\Statistic\TelgramMembersStatisticRepository;
use App\Services\File\FileSendService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApiTelegramUsersStatistic extends Controller
{
    private TelgramMembersStatisticRepository $statisticRepository;
    private FileSendService $fileSendService;

    /**
     * @param TelgramMembersStatisticRepository $statisticRepository
     * @param FileSendService $fileSendService
     */

    public function __construct(
        TelgramMembersStatisticRepository $statisticRepository,
        FileSendService                   $fileSendService
    )
    {
        $this->statisticRepository = $statisticRepository;
        $this->fileSendService = $fileSendService;
    }


    /**
     * @param ApiMemberStatisticRequest $request
     * @return ApiResponse
     */

    public function members(
        ApiMemberStatisticRequest $request
    ): ApiResponse
    {

        $members = $this->statisticRepository->getMembersList(
            $request->input('community_ids') ?? [],
            $request
        );

        $count = $members->count();
        $members_statistic = $members->skip($request->input('offset') ?? 0)
            ->take($request->input('limit') ?? 15)
            ->get();

        return ApiResponse::listPagination(
            ['Access-Control-Expose-Headers' => 'Items-Count', 'Items-Count' => $count]
        )->items($members_statistic);
    }

    public function memberCharts(
        ApiMemberStatisticChartsRequest $request
    ): ApiResponse
    {

        $active_user = $this->statisticRepository->getActiveUsers(
            $request->input('community_ids') ?? []
        );
        $current_members = $this->statisticRepository->currentMembersChart(
            $request->input('community_ids') ?? [],
            $request
        );
        $join_users = $this->statisticRepository->getJoiningMembersChart(
            $request->input('community_ids') ?? [],
            $request
        );
        $exit_users = $this->statisticRepository->getExitingMembersChart(
            $request->input('community_ids') ?? [],
            $request);
        return ApiResponse::common([
            'current_members_by_days' => $current_members,
            'join_users_by_days' => $join_users,
            'exit_users_by_days' => $exit_users,
            'join_users_total' => $join_users->sum('users'),
            'exit_users_total' => $exit_users->sum('users'),
            'active_users' => $active_user->count(),
        ]);
    }

    /**
     * @throws StatisticException
     */
    public function exportMembers(
        ApiMemberStatisticExportRequest $request
    ): StreamedResponse
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
                'attribute' => 'comm_name',
                'title' => 'Название сообщества'
            ],
            [
                'attribute' => 'c_put_reactions',
                'title' => 'Количество реакций оставил'
            ],
            [
                'attribute' => 'c_got_reactions',
                'title' => 'Количество реакций получил'
            ],
            [
                'attribute' => 'utility',
                'title' => 'Полезность'
            ],
        ];

        $membersBuilder = $this->statisticRepository->getMembersListForFile($request->input('community_ids') ?? []);


        return $this->fileSendService->sendFile(
            $membersBuilder,
            $columnNames,
            MemberResource::class,
            $request->get('export_type', 'csv'),
            'members');
    }
}
