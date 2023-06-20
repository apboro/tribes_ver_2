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
            'total_users' => $current_members->max('users'),
            'active_user_percent' => number_format(
                ($active_user->count() / $current_members->max('users')) * 100, 2
            )
        ]);
    }

    /**
     * @throws StatisticException
     */
    public function exportMembers(
        ApiMemberStatisticExportRequest $request
    ): StreamedResponse
    {
        $columnNames = $this->statisticRepository::EXPORT_FIELDS;
        $membersBuilder = $this->statisticRepository->getListForFile($request->input('community_ids') ?? []);

        return $this->fileSendService->sendFile(
            $membersBuilder,
            $columnNames,
            MemberResource::class,
            $request->get('export_type', 'csv'),
            'members');
    }
}
