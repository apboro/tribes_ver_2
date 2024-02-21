<?php

namespace App\Http\Controllers\APIv3\User;

use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\LegaLInfoRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LegalInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): ApiResponse
    {
        /** @var User $user */
        $user = $request->user();
        $userLegalInfo = $user->legalInfoList();

        return ApiResponse::common($userLegalInfo->toArray());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LegaLInfoRequest $request): ApiResponse
    {
        try {
            /** @var User $user */
            $user = $request->user();
            $user->legalInfo()->create($request->validated());

            return ApiResponse::success('common.success');

        } catch(Exception $e) {
            log::error('store user legal info error:'.  $e);

            return  ApiResponse::error('common.error');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
