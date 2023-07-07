<?php

namespace App\Http\Controllers\APIv3\Tariff;

use App\Http\ApiRequests\Community\ApiTariffsRequest;
use App\Http\ApiRequests\Tariffs\ApiTariffDestroyRequest;
use App\Http\ApiRequests\Tariffs\ApiTariffListRequest;
use App\Http\ApiRequests\Tariffs\ApiTariffShowRequest;
use App\Http\ApiRequests\Tariffs\ApiTariffStoreRequest;
use App\Http\ApiResources\ApiTariffResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Tariff;
use App\Repositories\Tariff\TariffRepositoryContract;

class ApiTariffController extends Controller
{

    private TariffRepositoryContract $tariffRepository;

    public function __construct(TariffRepositoryContract  $tariffRepository)
    {
        $this->tariffRepository = $tariffRepository;
    }

    public function list(ApiTariffListRequest $request)
    {
        $tariffs = Tariff::owned()->get();
        return ApiResponse::common(ApiTariffResource::collection($tariffs)->toArray($request));
    }

    public function store(ApiTariffStoreRequest $request)
    {
        $data = $request->all();
        $tariff = $this->tariffRepository->storeOrUpdate($data);
        return ApiResponse::common(ApiTariffResource::make($tariff));
    }

    public function show(ApiTariffShowRequest $request)
    {
        $tariff = Tariff::owned()->findOrFail($request->id);
        return ApiResponse::common(ApiTariffResource::make($tariff));
    }

    public function destroy(ApiTariffDestroyRequest $request)
    {
        $tariff = Tariff::owned()->findOrFail($request->id);
        $tariff->delete();
        return ApiResponse::success('common.success');
    }
}