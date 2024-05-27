<?php

namespace App\Http\Controllers\APIv3\User;

use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\LegaLInfoRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class LegalInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request): ApiResponse
    {

        /** @var User $user */
        $user = $request->user();
        $userLegalInfo = $user->legalUserInfo();

        if($userLegalInfo) {
            return ApiResponse::common($userLegalInfo->toArray());
        }

        return ApiResponse::error('common.not_found');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param LegaLInfoRequest $request
     *
     * @return ApiResponse
     */
    public function store(LegaLInfoRequest $request): ApiResponse
    {
        /** @var User $user */
        $user = $request->user();
        if (!$user->legalUserInfo()) {
            $user->legalInfo()->create($request->validated());

            return ApiResponse::success('common.success');
        }

        return ApiResponse::error('common.success');
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param int $id
     *
     * @return ApiResponse
     */
    public function show(Request $request, int $id): ApiResponse
    {
        try {
            /** @var User $user */
            $user = $request->user();
            $userLegalInfo = $user->getLegalInfo($id)->toArray();

            return ApiResponse::common($userLegalInfo);

        } catch (Exception $e) {
            log::error('store user legal info error:' . $e);

            return ApiResponse::error('common.error');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param LegaLInfoRequest $request
     * @param int $id
     *
     * @return ApiResponse
     */
    public function update(LegaLInfoRequest $request, int $id): ApiResponse
    {
        try {
            /** @var User $user */
            $user = $request->user();
            $userLegalInfo = $user->getLegalInfo($id);
            $userLegalInfo->updateProps($request->validated());

            return ApiResponse::success('common.success');

        } catch(Exception $e) {
            log::error('update user legal info error:'.  $e);

            return  ApiResponse::error('common.error');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     *
     * @return ApiResponse
     */
    public function destroy(Request $request, int $id): ApiResponse
    {
        try {
            /** @var User $user */
            $user = $request->user();
            $user->getLegalInfo($id)->delete();

            return ApiResponse::success('common.success');

        } catch (Exception $e) {
            log::error('destroy user legal info error:' . $e);

            return ApiResponse::error('common.error');
        }
    }
}
