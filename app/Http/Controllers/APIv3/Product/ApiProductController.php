<?php

namespace App\Http\Controllers\APIv3\Product;

use App\Http\ApiRequests\Product\ApiProductDeleteRequest;
use App\Http\ApiRequests\Product\ApiProductListRequest;
use App\Http\ApiRequests\Product\ApiProductPublicListRequest;
use App\Http\ApiRequests\Product\ApiProductShowByUUIDRequest;
use App\Http\ApiRequests\Product\ApiProductShowRequest;
use App\Http\ApiRequests\Product\ApiProductStoreRequest;
use App\Http\ApiRequests\Product\ApiProductUpdateRequest;
use App\Http\ApiResources\Product\ProductResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Repositories\Product\ProductRepository;
use App\Services\Pay\PayService;

class ApiProductController extends Controller
{

    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function store(ApiProductStoreRequest $request): ApiResponse
    {
        $product = $this->productRepository->store($request);

        return ApiResponse::common(ProductResource::make($product)->toArray($request));
    }

    public function show(ApiProductShowRequest $request, $id): ApiResponse
    {
        $product = Product::find($id);

        return ApiResponse::common(ProductResource::make($product)->toArray($request));
    }

    public function list(ApiProductListRequest $request): ApiResponse
    {
        return $this->productRepository->showList($request);
    }

    public function publicList(ApiProductPublicListRequest $request): ApiResponse
    {
        return $this->productRepository->showList($request);
    }

    public function destroy(ApiProductDeleteRequest $request, int $id): ApiResponse
    {
        Product::find($id)->delete();

        return ApiResponse::success();
    }

    public function update(ApiProductUpdateRequest $request, int $id): ApiResponse
    {
        $product = $this->productRepository->update($request, $id);

        return ApiResponse::common(ProductResource::make($product)->toArray($request));
    }

    public function showByUuid(ApiProductShowByUUIDRequest $request, string $uuid)
    {
        $product = Product::findByUUID($uuid);

        return ApiResponse::common(ProductResource::make($product)->toArray($request));
    }

    public function pay($request)
    {

    }
}
