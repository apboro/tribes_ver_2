<?php

namespace App\Http\Controllers\Shop;

use App\Bundle\SafeRoute\SafeRouteService;
use App\Http\ApiRequests\Shop\SafeRouteRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\LegaLInfoRequest;
use App\Models\Shop\ShopSafeRoute;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class SafeRouteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return ApiResponse
     */
    public function index(Request $request): ApiResponse
    {
        /** @var User $user */
        $user = $request->user();
        $shops = $user->shops()->get();
        $safeRouteList = [];

        foreach($shops as $shop){
            $safeRouteList[$shop->id] = $shop->safeRoute()->first();
        }

        if ($safeRouteList) {
            return ApiResponse::common($safeRouteList);
        }

        return ApiResponse::error('common.not_found');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SafeRouteRequest $request
     *
     * @return ApiResponse
     */
    public function store(SafeRouteRequest $request): ApiResponse
    {
        try {
            /** @var User $user */
            $user = $request->user();
            $shop = $user->getShopById($request->get('shop_id'));

            $shop->safeRoute()->create($request->validated());

            return ApiResponse::success('common.success');

        } catch (Exception $e) {
            log::error('store shop safe route error:' . $e);

            return ApiResponse::error('common.error');
        }
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
            $shop = $user->getShopById($id);
            $safeRoute = $shop->getSafeRoute();

            return ApiResponse::common($safeRoute->toArray());

        } catch (Exception $e) {
            log::error('store shop safe-route error:' . $e);

            return ApiResponse::error('common.error');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SafeRouteRequest $request
     * @param int $id
     *
     * @return ApiResponse
     */
    public function update(SafeRouteRequest $request, int $id): ApiResponse
    {
        try {
            /** @var User $user */
            $user = $request->user();
            $shop = $user->getShopById($id);
            $shop->getSafeRoute()->update($request->validated());

            return ApiResponse::success('common.success');

        } catch(Exception $e) {
            log::error('update shop safe-route error:'.  $e);

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
            $shop = $user->getShopById($id);
            $shop->getSafeRoute()->delete();

            return ApiResponse::success('common.success');

        } catch (Exception $e) {
            log::error('destroy shop safe-route error:' . $e);

            return ApiResponse::error('common.error');
        }
    }

    public function getWidget(Request $request, int $shop_id): string
    {
        try {
            $safeRoute = ShopSafeRoute::where('shop_id', $shop_id)->first();

            return SafeRouteService::build($safeRoute, $request->all());

        } catch (Exception $e) {
            log::error('shop_id safe-route error:' . $e);

            return 'Not Found';
        }
    }
}
