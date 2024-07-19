<?php

namespace App\Http\Controllers\APIv3\Export;

use App\Exports\ShopOrdersExport;
use App\Http\ApiRequests\Exports\ApiShopOrderExportRequest;
use App\Http\Controllers\Controller;
use App\Services\Excel\ExportService;

class ApiShopOrderExportController extends Controller
{
    public function export(ApiShopOrderExportRequest $request, ExportService $exportService)
    {
        $export = new ShopOrdersExport($request->input('shop_id'));

        return $exportService->setExport($export)->download();
    }
}
