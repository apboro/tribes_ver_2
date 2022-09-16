<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Tariff\TariffService;

class TestTariffController extends Controller
{
    private $tariffService;

    public function __construct(TariffService $tariffService)
    {
        $this->tariffService = $tariffService;
    }

    public function test()
    {
        $this->tariffService->sendMessageAboutDeactivatedTariff();
    }
}
