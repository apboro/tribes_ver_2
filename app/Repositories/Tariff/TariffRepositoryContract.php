<?php

namespace App\Repositories\Tariff;

use Illuminate\Http\Request;
use App\Filters\TariffFilter;

interface TariffRepositoryContract
{
    public function updateOrCreate($community, $data, $variantId = NULL);
    public function activate($variantId, $activate);
    public function settingsUpdate($community, $data);
    public function getList(TariffFilter $filters, $community);
    public function statisticView(Request $request, $community);
    public function perm(Request $request, $community);
    public function generateLink($variant);
}