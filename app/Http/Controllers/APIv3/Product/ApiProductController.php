<?php

namespace App\Http\Controllers\APIv3\Product;

use App\Http\ApiRequests\Product\ApiProductDeleteRequest;
use App\Http\ApiRequests\Product\ApiProductListRequest;
use App\Http\ApiRequests\Product\ApiProductPublicListRequest;
use App\Http\ApiRequests\Product\ApiProductPublicShowRequest;
use App\Http\ApiRequests\Product\ApiProductRemoveImageRequest;
use App\Http\ApiRequests\Product\ApiProductSetFirstImageRequest;
use App\Http\ApiRequests\Product\ApiProductShowRequest;
use App\Http\ApiRequests\Product\ApiProductStoreImageRequest;
use App\Http\ApiRequests\Product\ApiProductStoreRequest;
use App\Http\ApiRequests\Product\ApiProductUpdateRequest;
use App\Http\ApiResources\Product\ProductResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ApiProductController extends Controller
{
    private function prepareImages($request): array
    {
        $images = [];
        if ($request->file('images')) {
            foreach ($request->file('images') as $key => $file) {
                $images[] = Product::prepareImageRecord($key+1, Storage::disk('public')->putFile('product_images/' . $request->shop_id, $file));
            }
        }

        return ['images' => $images];
    }

    private function prepareProduct($request): array
    {
        $productArray = [
            'description' => $request->input('description') ?? null,
            'title' => $request->input('title'),
            'price' => $request->input('price'),
            'buyable' => $request->input('buyable') == 'false' ? false : true,
            'category_id' => $request->input('category_id'),
        ];

        return $productArray;
    }

    public function store(ApiProductStoreRequest $request): ApiResponse
    {
        $product = Product::create(['shop_id' => $request->shop_id] + $this->prepareProduct($request) + $this->prepareImages($request));

        return ApiResponse::common(ProductResource::make($product)->toArray($request));
    }

    public function storeImage(ApiProductStoreImageRequest $request): ApiResponse
    {
        $product = Product::find($request->id);
        $path = Storage::disk('public')->putFile('product_images/' . $product->shop_id, $request->file('image'));
        $product->addImage($path);

        return ApiResponse::common(ProductResource::make($product)->toArray($request));
    }

    public function removeImage(ApiProductRemoveImageRequest $request): ApiResponse
    {
        $product = Product::find($request->id);
        $product->removeImage($request->image_id);

        return ApiResponse::common(ProductResource::make($product)->toArray($request));
    }

    public function show(ApiProductShowRequest $request, $id): ApiResponse
    {
        $product = Product::find($id);

        return ApiResponse::common(ProductResource::make($product)->toArray($request));
    }

    private function showList($request): ApiResponse
    {
        $filter = $request->validated();
        $products = Product::findByFilter($filter);
        $count = Product::countByFilter($filter);

        return ApiResponse::listPagination(
            [
                'Access-Control-Expose-Headers' => 'Items-Count',
                'Items-Count' => $count
            ]
        )->items((ProductResource::collection($products))->toArray($request));
    }

    public function list(ApiProductListRequest $request): ApiResponse
    {
        return $this->showList($request);
    }

    public function publicList(ApiProductPublicListRequest $request): ApiResponse
    {
        return $this->showList($request);
    }

    public function destroy(ApiProductDeleteRequest $request, int $id): ApiResponse
    {
        Product::find($id)->delete();

        return ApiResponse::success();
    }

    public function update(ApiProductUpdateRequest $request, int $id): ApiResponse
    {
        $product = Product::find($id)->fill($this->prepareProduct($request));
        $product->save();

        return ApiResponse::common(ProductResource::make($product)->toArray($request));
    }

    public function publicShow(ApiProductPublicShowRequest $request, string $id)
    {
        $product = Product::find($id);

        return ApiResponse::common(ProductResource::make($product)->toArray($request));
    }

    public function setFirstImage(ApiProductSetFirstImageRequest $request, string $id): ApiResponse
    {
        $product = Product::find($id);
        $product->setFirstImage($request->image_id);

        return ApiResponse::common(ProductResource::make($product)->toArray($request));
    }

}
