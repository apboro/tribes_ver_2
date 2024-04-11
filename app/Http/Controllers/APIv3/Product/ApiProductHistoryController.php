<?php

namespace App\Http\Controllers\APIv3\Product;

use App\Http\ApiRequests\Product\ApiProductHistoryListRequest;
use App\Http\ApiResources\Product\ProductResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\VisitedProduct;

class ApiProductHistoryController extends Controller
{
    public function list(ApiProductHistoryListRequest $request): ApiResponse
    {
        $visited = VisitedProduct::getVisitedProducts($request->shop_id, $request->telegram_id);
        $products = $visited ? Product::findByList($visited->products) : [];

        return ApiResponse::listPagination(
            [
                'Access-Control-Expose-Headers' => 'Items-Count',
                'Items-Count' => count($products)
            ]
        )->items((ProductResource::collection($products))->toArray($request));
    }
}