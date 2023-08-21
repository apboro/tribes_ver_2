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
use App\Services\File\FilePrepareService;
use App\Services\File\FileSendService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApiTelegramUsersStatistic extends Controller
{
    private TelegramMembersStatisticRepository $statisticRepository;
    private FilePrepareService $filePrepareService;

    /**
     * @param TelegramMembersStatisticRepository $statisticRepository
     * @param FileSendService $fileSendService
     */

    public function __construct(TelegramMembersStatisticRepository $statisticRepository,
                                FilePrepareService $filePrepareService)
    {
        $this->statisticRepository = $statisticRepository;
        $this->filePrepareService = $filePrepareService;
    }


    /**
     * @param ApiMemberStatisticChartsRequest $request
     * @return ApiResponse
     */

    public function members(ApiMemberStatisticChartsRequest $request): ApiResponse
    {
        $communityIds = $request->input('community_ids') ?? [];

        $active_user = $this->statisticRepository->getActiveUsers($communityIds, $request);
        $current_members = $this->statisticRepository->currentMembersChart($communityIds, $request);
        $join_users = $this->statisticRepository->getJoiningMembersChart($communityIds, $request);
        $exit_users = $this->statisticRepository->getExitingMembersChart($communityIds, $request);

        $members = $this->statisticRepository->getMembersList($request->input('community_ids') ?? []);
        $totalMembers = $members->whereNull('exit_date')->get()->count();

        return ApiResponse::common([
            'totalMembers' => $totalMembers,
            'activeMembers' => $this->calcStatisticForMembers($active_user->count(), $totalMembers),
            'joinMembers' => $this->calcStatisticForMembers($join_users->sum('users'), $totalMembers),
            'leftMembers' => $this->calcStatisticForMembers($exit_users->sum('users'), $totalMembers),
            'series' => [$current_members],
            'members' => $members->whereNull('exit_date')->get()
        ]);
    }

    private function calcStatisticForMembers($count, int $total): array
    {
        $delta = $total > 0 ? number_format($count * 100 / $total, 2) : 0;
        return ['value' => $count, 'delta' => $delta];
    }

    
    /**
     * @throws StatisticException
     */
    public function exportMembers(ApiMemberStatisticExportRequest $request)
    {
        $columnNames = $this->statisticRepository::EXPORT_FIELDS;
        $membersBuilder = $this->statisticRepository->getListForFile($request->input('community_ids') ?? []);

        return $this->filePrepareService->prepareFile(
            $membersBuilder,
            $columnNames,
            MemberResource::class,
            $request->get('export_type', 'csv'),
            'members');
    }
}
