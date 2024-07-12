<?php

namespace App\Http\Controllers\APIv3\Product;

use App\Domain\Entity\Shop\CheckShopIsAvailable;
use App\Http\ApiRequests\Product\ApiCategoryModify;
use App\Http\ApiRequests\Product\ApiCategoryShowRequest;
use App\Http\ApiRequests\Shop\ApiShopShowListRequest;
use App\Http\ApiRequests\Shop\ApiShopShowMyListRequest;
use App\Http\ApiRequests\Product\ApiCategoryStoreRequest;
use App\Http\ApiRequests\Product\ApiCategoryUpdateRequest;
use App\Http\ApiResources\Product\CategoryResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApiCategoryController extends Controller
{

    public const CATEGORY_DEFAULT=0;

    public function store(ApiCategoryStoreRequest $request): ApiResponse
    {
        $category = ProductCategory::create($request->validated());

        return ApiResponse::common(CategoryResource::make($category)->toArray($request));
    }

    public function show(ApiCategoryShowRequest $request, int $id): ApiResponse
    {
        $shop = Shop::find($request->input('shop_id'));

        if (CheckShopIsAvailable::isUnavailable($shop)) {
            return ApiResponse::forbidden('common.shop_unavailable');
        }

        $category = ProductCategory::findWithProductCount($id);

        if (!$category) {
            return ApiResponse::error('common.not_found');
        }

        return ApiResponse::common(CategoryResource::make($category)->toArray($request));
    }

    public function list(ApiCategoryShowRequest $request): ApiResponse
    {
        $filter = $request->validated();

        $shop = Shop::find($filter['shop_id']);

        if (CheckShopIsAvailable::isUnavailable($shop)) {
            return ApiResponse::forbidden('common.shop_unavailable');
        }

        $categories = ProductCategory::findByFilter($filter);
        $count = ProductCategory::countByFilter($filter);
        
        return ApiResponse::listPagination([
                'Access-Control-Expose-Headers' => 'Items-Count',
                'Items-Count' => $count
            ])->items((CategoryResource::collection($categories))->toArray($request));
    }

    public function update(ApiCategoryModify $request, int $id): ApiResponse
    {
        $category = ProductCategory::find($id)->fill($request->validated());
        $category->save();

       return ApiResponse::common(CategoryResource::make($category)->toArray($request));
    }

    public function destroy(ApiCategoryModify $request, int $id): ApiResponse
    {
        return ProductCategory::find($id)->remove() ? 
                ApiResponse::success() : ApiResponse::error('');
    }
}