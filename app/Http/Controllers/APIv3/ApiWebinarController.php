<?php

namespace App\Http\Controllers\APIv3;

use App\Http\ApiRequests\Webinars\ApiWebinarsDeleteRequest;
use App\Http\ApiRequests\Webinars\ApiWebinarsListRequest;
use App\Http\ApiRequests\Webinars\ApiWebinarsShowRequest;
use App\Http\ApiRequests\Webinars\ApiWebinarsStoreRequest;
use App\Http\ApiRequests\Webinars\ApiWebinarsUpdateRequest;
use App\Http\ApiResources\Webinar\WebinarCollection;
use App\Http\ApiResources\Webinar\WebinarResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Repositories\Webinar\WebinarRepository;

class ApiWebinarController extends Controller
{

    private WebinarRepository $webinarRepository;

    /**
     * @param WebinarRepository $webinarRepository
     */

    public function __construct(WebinarRepository $webinarRepository)
    {
        $this->webinarRepository = $webinarRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param ApiWebinarsListRequest $request
     * @return ApiResponse
     */
    public function list(ApiWebinarsListRequest $request): ApiResponse
    {
        $webinars = $this->webinarRepository->list();
        $count = $webinars->count();
        return ApiResponse::listPagination(
            [
                'Access-Control-Expose-Headers' => 'Items-Count',
                'Items-Count' => $count
            ])->items((
        new WebinarCollection($webinars->skip($request->offset ?? 0)->take($request->limit ?? 3)->orderBy('created_at', 'DESC')->get()
        ))->toArray($request));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param ApiWebinarsStoreRequest $request
     * @return ApiResponse
     */
    public function store(ApiWebinarsStoreRequest $request): ApiResponse
    {
        $webinar = $this->webinarRepository->add($request);
        if ($webinar === null) {
            return ApiResponse::error('add_error');
        }
        return ApiResponse::common(WebinarResource::make($webinar)->toArray($request));
    }

    /**
     * Display the specified resource.
     *
     * @param ApiWebinarsShowRequest $request
     * @param int $id
     * @return ApiResponse
     */
    public function show(ApiWebinarsShowRequest $request, int $id): ApiResponse
    {
        $webinar = $this->webinarRepository->show($id);
        if ($webinar === null) {
            return ApiResponse::notFound('common.not_found');
        }
        return ApiResponse::common(WebinarResource::make($webinar)->toArray($request));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ApiWebinarsUpdateRequest $request
     * @param int $id
     * @return ApiResponse
     */
    public function update(ApiWebinarsUpdateRequest $request, int $id): ApiResponse
    {
        $webinar = $this->webinarRepository->update($request, $id);
        if ($webinar === null) {
            return ApiResponse::error('add_error');
        }
        return ApiResponse::common(WebinarResource::make($webinar)->toArray($request));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ApiWebinarsDeleteRequest $request
     * @param int $id
     * @return ApiResponse
     */
    public function destroy(ApiWebinarsDeleteRequest $request, int $id): ApiResponse
    {
        $result = $this->webinarRepository->delete($id);
        if (!$result) {
            return ApiResponse::error('Ошибка удаления');
        }
        return ApiResponse::success();
    }
}
