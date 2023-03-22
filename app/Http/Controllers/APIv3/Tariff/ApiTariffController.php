<?php

namespace App\Http\Controllers\APIv3\Tariff;

use App\Http\ApiRequests\Community\ApiTariffsRequest;
use App\Http\Controllers\Controller;
use App\Repositories\Tariff\TariffRepositoryContract;

class ApiTariffController extends Controller
{

    private TariffRepositoryContract $tariffRepository;

    public function __construct(TariffRepositoryContract  $tariffRepository)
    {
        $this->tariffRepository = $tariffRepository;
    }

    public function index(ApiTariffsRequest $request)
    {
        $tariffs = $this->tariffRepository->getTariffVariantsByCommunities(['all']);
    }
}