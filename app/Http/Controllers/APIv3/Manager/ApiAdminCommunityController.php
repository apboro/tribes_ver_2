<?php

namespace App\Http\Controllers\APIv3\Manager;

use App\Exceptions\StatisticException;
use App\Http\ApiRequests\Admin\ApiAdminCommunityExportRequest;
use App\Http\ApiRequests\Admin\ApiAdminCommunityListRequest;
use App\Http\ApiRequests\Admin\ApiAdminCommunityShowRequest;
use App\Http\ApiResources\Admin\AdminCommunityCollection;
use App\Http\ApiResources\Admin\AdminCommunityResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Manager\Filters\CommunityFilter;
use App\Http\Resources\Manager\CommunityResource;
use App\Models\Community;
use App\Services\File\FilePrepareService;

class ApiAdminCommunityController extends Controller
{

    private FilePrepareService $filePrepareService;

    public function __construct(
        FilePrepareService $filePrepareService
    )
    {
        $this->filePrepareService = $filePrepareService;
    }

    /**
     * @param ApiAdminCommunityShowRequest $request
     * @param int $id
     * @return ApiResponse
     */

    public function show(ApiAdminCommunityShowRequest $request, int $id): ApiResponse
    {

        $community = Community::where('id', '=', $id)->first();
        return ApiResponse::common(AdminCommunityResource::make($community)->toArray($request));
    }

    /**
     * @param ApiAdminCommunityListRequest $request
     * @param CommunityFilter $filter
     * @return ApiResponse
     */

    public function list(ApiAdminCommunityListRequest $request, CommunityFilter $filter): ApiResponse
    {
        $communities = Community::with('communityOwner', 'connection')->
        withCount('followers')->
        filter($filter);
        $count = $communities->count();
        return ApiResponse::listPagination(
            [
                'Access-Control-Expose-Headers' => 'Items-Count',
                'Items-Count' => $count
            ])->items((new AdminCommunityCollection($communities->skip($request->offset)->take($request->limit ?? 25)->orderBy('id')->get()))->toArray($request));
    }


    /**
     * @param ApiAdminCommunityExportRequest $request
     * @return ApiResponse
     * @throws StatisticException
     */
    public function export(ApiAdminCommunityExportRequest $request):ApiResponse
    {
        $names = [
            [
                'title' => 'id',
                'attribute' => 'id',
            ],
            [
                'title' => 'Название',
                'attribute' => 'title',
            ],
            [
                'title' => 'Владелец',
                'attribute' => 'owner_name',
            ],
            [
                'title' => 'Telegram',
                'attribute' => 'telegram',
            ],
            [
                'title' => 'Дата подключения',
                'attribute' => 'created_at',
            ],
            [
                'title' => 'Кол-во подписчиков',
                'attribute' => 'followers',
            ],
            [
                'title' => 'Сумма поступлений',
                'attribute' => 'balance',
            ],
        ];

        $prepare_result = $this->filePrepareService->prepareFile(
            Community::with('communityOwner', 'connection')->withCount('followers'),
            $names,
            CommunityResource::class,
            $request->get('type', 'csv'),
            'communities'
        );
        if (!$prepare_result['result']) {
            return ApiResponse::error($prepare_result['message']);
        }
        return ApiResponse::common([
            'file_path' => $prepare_result['file_path']
        ]);
    }

}
