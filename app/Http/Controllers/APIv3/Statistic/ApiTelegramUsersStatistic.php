<?php

namespace App\Http\Controllers\APIv3\Statistic;

use App\Exceptions\StatisticException;
use App\Http\ApiRequests\Statistic\ApiMemberStatisticChartsRequest;
use App\Http\ApiRequests\Statistic\ApiMemberStatisticExportRequest;
use App\Http\ApiRequests\Statistic\ApiMemberStatisticRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Statistic\MemberResource;
use App\Repositories\Statistic\TelegramMembersStatisticRepository;
use App\Services\File\FileSendService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApiTelegramUsersStatistic extends Controller
{
    private TelegramMembersStatisticRepository $statisticRepository;
    private FileSendService $fileSendService;

    /**
     * @param TelegramMembersStatisticRepository $statisticRepository
     * @param FileSendService $fileSendService
     */

    public function __construct(TelegramMembersStatisticRepository $statisticRepository, FileSendService $fileSendService)
    {
        $this->statisticRepository = $statisticRepository;
        $this->fileSendService = $fileSendService;
    }


    /**
     * @param ApiMemberStatisticChartsRequest $request
     * @return ApiResponse
     */

    public function members(ApiMemberStatisticChartsRequest $request): ApiResponse
    {

        $active_user = $this->statisticRepository->getActiveUsers(
            $request->input('community_ids') ?? [],
            $request
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

        $members = $this->statisticRepository->getMembersList($request->input('community_ids') ?? []);

        return ApiResponse::common([
            'totalMembers' => $current_members->max('users'),
            'activeMembers' => [
                'value' => $active_user->count(),
                'delta' => $current_members->max('users') > 0 ?
                    number_format($active_user->count() / $current_members->max('users') * 100, 2)
                    : 0,
            ],
            'joinMembers' => [
                'value' => $join_users->sum('users'),//$join_users->count(),
                'delta' => $current_members->max('users') > 0 ?
                    number_format($join_users->sum('users') / $current_members->max('users') * 100, 2)
                    : 0,
            ],
            'leftMembers' => [
                'value' => $exit_users->sum('users'),
                'delta' => $current_members->max('users') > 0 ?
                    number_format($exit_users->sum('users') / $current_members->max('users') * 100, 2)
                    : 0,
            ],
            'series' => [$current_members],
            'members' => $members->get()->unique('nick_name')->values()
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
